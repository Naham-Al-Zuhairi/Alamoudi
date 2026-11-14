<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContentRequest;
use App\Models\Achievement;
use App\Models\ContactLocation;
use App\Models\ContactMessage;
use App\Models\HeroSlide;
use App\Models\PageSection;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use App\Models\SiteSetting;
use App\Models\Statistic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    private const MODELS = [
        'slides' => HeroSlide::class, 'sections' => PageSection::class, 'achievements' => Achievement::class,
        'categories' => ProjectCategory::class, 'projects' => Project::class, 'locations' => ContactLocation::class,
        'settings' => SiteSetting::class,
        'statistics' => Statistic::class,
    ];

    private function model(string $type): string
    {
        abort_unless(isset(self::MODELS[$type]), 404);

        return self::MODELS[$type];
    }

    public function dashboard(): Response
    {
        return Inertia::render('admin/Dashboard', [
            'counts' => ['projects' => Project::count(), 'messages' => ContactMessage::count(), 'unread' => ContactMessage::whereNull('read_at')->count()],
        ]);
    }

    public function index(string $type): Response
    {
        $model = $this->model($type);

        return Inertia::render('admin/Content', [
            'type' => $type, 'rows' => $model::when($type === 'projects', fn ($q) => $q->with('images'))->orderBy('position')->paginate(20),
            'categories' => $type === 'projects' ? ProjectCategory::orderBy('position')->get() : [],
        ]);
    }

    public function store(ContentRequest $request, string $type): RedirectResponse
    {
        $model = $this->model($type);
        $model::create($request->validated());

        return back();
    }

    public function update(ContentRequest $request, string $type, int $id): RedirectResponse
    {
        $model = $this->model($type);
        $model::findOrFail($id)->update($request->validated());

        return back();
    }

    public function destroy(string $type, int $id): RedirectResponse
    {
        $model = $this->model($type);
        $model::findOrFail($id)->delete();

        return back();
    }

    public function inbox(Request $request): Response
    {
        return Inertia::render('admin/Inbox', [
            'messages' => ContactMessage::query()->when($request->filter === 'unread', fn ($q) => $q->whereNull('read_at'))->when($request->filter === 'read', fn ($q) => $q->whereNotNull('read_at'))->latest()->paginate(20)->withQueryString(),
            'unread' => ContactMessage::whereNull('read_at')->count(), 'filter' => $request->filter,
        ]);
    }

    public function message(ContactMessage $message): Response
    {
        return Inertia::render('admin/Message', ['message' => $message]);
    }

    public function mark(Request $request, ContactMessage $message): RedirectResponse
    {
        $data = $request->validate(['read' => 'required|boolean']);
        $message->update(['read_at' => $data['read'] ? now() : null]);

        return back();
    }

    public function upload(Request $request): RedirectResponse
    {
        $data = $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:max_width=5000,max_height=5000']);
        $path = $data['image']->store('cms', 'public');

        return back()->with('uploadedImage', Storage::url($path));
    }

    public function addImage(Request $request, Project $project): RedirectResponse
    {
        $imageRules = ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:max_width=5000,max_height=5000'];
        $data = $request->validate([
            'image' => ['required_without:images', ...$imageRules],
            'images' => 'required_without:image|array|min:1|max:12',
            'images.*' => $imageRules,
        ]);
        $files = $data['images'] ?? [$data['image']];
        $stored = [];
        try {
            DB::transaction(function () use ($project, $files, &$stored): void {
                $position = (int) $project->images()->max('position');
                foreach ($files as $file) {
                    $path = $file->store('cms', 'public');
                    $stored[] = $path;
                    $project->images()->create(['path' => Storage::url($path), 'position' => ++$position]);
                }
            });
        } catch (\Throwable $exception) {
            foreach ($stored as $path) {
                Storage::disk('public')->delete($path);
            }
            throw $exception;
        }

        return back();
    }

    public function removeImage(Project $project, ProjectImage $image): RedirectResponse
    {
        abort_unless($image->project_id === $project->id, 404);
        $image->delete();
        if (str_starts_with($image->path, '/storage/cms/')) {
            Storage::disk('public')->delete(substr($image->path, strlen('/storage/')));
        }

        return back();
    }
}
