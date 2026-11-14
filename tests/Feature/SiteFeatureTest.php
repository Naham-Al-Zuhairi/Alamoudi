<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\HeroSlide;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_in_both_languages(): void
    {
        foreach (['ar', 'en'] as $locale) {
            foreach (['', '/projects', '/team', '/contact'] as $path) {
                $this->get("/$locale$path")->assertOk();
            }
        }
    }

    public function test_contact_validation_and_unread_creation(): void
    {
        $this->post('/en/contact', [])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->post('/ar/contact', [])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->post('/en/contact', ['name' => 'Visitor', 'email' => 'visitor@example.com', 'subject' => 'Inquiry', 'message' => 'Please contact me about your services.'])
            ->assertRedirect();
        $this->assertDatabaseHas('contact_messages', ['email' => 'visitor@example.com', 'read_at' => null]);
    }

    public function test_admin_boundary_and_inbox_transition(): void
    {
        $message = ContactMessage::create(['name' => 'Visitor', 'email' => 'visitor@example.com', 'subject' => 'Inquiry', 'message' => 'Hello from a visitor']);
        $this->get('/admin/inbox')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/admin/inbox')->assertForbidden();
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin/inbox')->assertOk();
        $this->patch("/admin/inbox/{$message->id}", ['read' => true])->assertRedirect();
        $this->assertNotNull($message->fresh()->read_at);
        $this->patch("/admin/inbox/{$message->id}", ['read' => false])->assertRedirect();
        $this->assertNull($message->fresh()->read_at);
    }

    public function test_draft_slide_is_hidden_and_admin_can_edit(): void
    {
        $slide = HeroSlide::create(['slug' => 'test-slide', 'title_ar' => 'تجربة', 'title_en' => 'Test', 'position' => 1, 'is_published' => false]);
        $this->get('/en')->assertInertia(fn ($page) => $page->component('public/Home')->where('slides', []));
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->put("/admin/content/slides/{$slide->id}", [
            'slug' => 'test-slide', 'title_ar' => 'تجربة', 'title_en' => 'Test', 'position' => 1, 'is_published' => true,
        ])->assertRedirect();
        $this->assertTrue($slide->fresh()->is_published == true);
    }

    public function test_contact_honeypot_and_inbox_filter(): void
    {
        $this->post('/ar/contact', ['name' => 'Visitor', 'email' => 'visitor@example.com', 'subject' => 'Inquiry', 'message' => 'A valid length message', 'contact_ref_code' => 'spam.example'])
            ->assertSessionHasErrors('contact_ref_code');
        $this->assertDatabaseCount('contact_messages', 0);
        ContactMessage::create(['name' => 'One', 'email' => 'one@example.com', 'subject' => 'Unread', 'message' => 'Hello from one']);
        ContactMessage::create(['name' => 'Two', 'email' => 'two@example.com', 'subject' => 'Read', 'message' => 'Hello from two', 'read_at' => now()]);
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin/inbox?filter=unread')->assertInertia(fn ($page) => $page->component('admin/Inbox')->has('messages.data', 1));
        $this->get('/admin/inbox?filter=read')->assertInertia(fn ($page) => $page->component('admin/Inbox')->has('messages.data', 1));
    }

    public function test_legacy_autofilled_website_field_does_not_block_contact_message(): void
    {
        $this->post('/en/contact', [
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'subject' => 'Inquiry',
            'message' => 'Please contact me about your services.',
            'website' => 'autofilled.example',
        ])->assertRedirect();

        $this->assertDatabaseHas('contact_messages', ['email' => 'visitor@example.com', 'read_at' => null]);
    }

    public function test_cms_creates_content_visible_on_public_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->post('/admin/content/slides', ['slug' => 'new-slide', 'title_ar' => 'عنوان', 'title_en' => 'Title', 'position' => 2, 'is_published' => true])->assertRedirect();
        $this->assertDatabaseHas('hero_slides', ['slug' => 'new-slide', 'title_en' => 'Title']);
        $this->get('/en')->assertInertia(fn ($page) => $page->component('public/Home')->has('slides', 1));
        $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml');
    }

    public function test_admin_upload_and_project_gallery(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $project = Project::create(['slug' => 'gallery', 'title_ar' => 'معرض', 'title_en' => 'Gallery', 'is_published' => true]);
        $this->actingAs($admin)->post('/admin/media', ['image' => UploadedFile::fake()->image('photo.jpg')])->assertRedirect();
        $this->post("/admin/projects/{$project->id}/images", ['image' => UploadedFile::fake()->image('gallery.jpg')])->assertRedirect();
        $this->assertDatabaseCount('project_images', 1);
        $this->post('/admin/media', ['image' => UploadedFile::fake()->create('bad.php', 12, 'text/plain')])->assertSessionHasErrors('image');
        $image = $project->images()->first();
        $this->delete("/admin/projects/{$project->id}/images/{$image->id}")->assertRedirect();
        $this->assertDatabaseCount('project_images', 0);
    }

    public function test_admin_can_add_multiple_gallery_images_without_changing_featured_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $project = Project::create(['slug' => 'gallery-multiple', 'title_ar' => 'معرض', 'title_en' => 'Gallery', 'image' => '/media/works/solar1.webp', 'is_published' => true]);

        $this->actingAs($admin)->post("/admin/projects/{$project->id}/images", [
            'images' => [UploadedFile::fake()->image('one.jpg'), UploadedFile::fake()->image('two.png')],
        ])->assertRedirect();

        $this->assertDatabaseCount('project_images', 2);
        $this->assertSame('/media/works/solar1.webp', $project->fresh()->image);
        $this->get('/en/projects/gallery-multiple')->assertInertia(fn ($page) => $page->component('public/Project')->has('project.images', 2)->where('project.image', '/media/works/solar1.webp'));
    }

    public function test_cms_rejects_unsafe_image_references(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $fields = ['slug' => 'safe-slide', 'title_ar' => 'عنوان', 'title_en' => 'Title', 'position' => 1, 'is_published' => true];

        $this->actingAs($admin)->post('/admin/content/slides', $fields + ['image' => 'https://example.com/tracker.png'])
            ->assertSessionHasErrors('image');
        $this->assertDatabaseMissing('hero_slides', ['slug' => 'safe-slide']);

        $this->post('/admin/content/slides', $fields + ['image' => '/media/slider-1.webp'])->assertRedirect();
        $this->assertDatabaseHas('hero_slides', ['slug' => 'safe-slide', 'image' => '/media/slider-1.webp']);
    }

    public function test_contact_submission_is_rate_limited(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.45']);
        $fields = ['name' => 'Visitor', 'email' => 'visitor@example.com', 'subject' => 'Inquiry', 'message' => 'Please call me about a project.'];
        for ($i = 0; $i < 10; $i++) {
            $this->post('/en/contact', $fields)->assertRedirect();
        }
        $this->post('/en/contact', $fields)->assertStatus(429);
    }

    public function test_legacy_statistics_are_editable_and_public(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->post('/admin/content/statistics', [
            'slug' => 'sample', 'title_ar' => 'عدد', 'title_en' => 'Count', 'value' => 12,
            'position' => 1, 'is_published' => true,
        ])->assertRedirect();
        $this->get('/en')->assertInertia(fn ($page) => $page->component('public/Home')->has('statistics', 1));
    }
}
