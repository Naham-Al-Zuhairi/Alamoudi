<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyContentSeeder extends Seeder
{
    public function run(): void
    {
        $copy = json_decode(file_get_contents(__DIR__.'/legacy-content.json'), true, 512, JSON_THROW_ON_ERROR);
        $now = now();
        $upsert = function (string $table, string $slug, array $fields) use ($now): void {
            DB::table($table)->updateOrInsert(['slug' => $slug], $fields + ['created_at' => $now, 'updated_at' => $now]);
        };
        for ($i = 1; $i <= 3; $i++) {
            $upsert('hero_slides', "slide-$i", [
                'title_ar' => $copy['carousel']['ar']["title$i"] ?? '',
                'title_en' => $copy['carousel']['en']["title$i"] ?? '',
                'image' => "/media/slider-$i.webp", 'position' => $i, 'is_published' => true,
            ]);
        }
        foreach (['description1', 'description2'] as $i => $key) {
            $upsert('page_sections', 'about-'.($i + 1), [
                'title_ar' => $copy['about']['ar']['about_us'] ?? '',
                'title_en' => $copy['about']['en']['about_us'] ?? '',
                'body_ar' => $copy['about']['ar'][$key] ?? '',
                'body_en' => $copy['about']['en'][$key] ?? '',
                'position' => $i, 'is_published' => true,
            ]);
        }
        for ($i = 1; $i <= 4; $i++) {
            $upsert('page_sections', "service-$i", [
                'title_ar' => $copy['about']['ar']["flaticon_title$i"] ?? '',
                'title_en' => $copy['about']['en']["flaticon_title$i"] ?? '',
                'body_ar' => $copy['about']['ar']["flaticon_description$i"] ?? '',
                'body_en' => $copy['about']['en']["flaticon_description$i"] ?? '',
                'position' => $i, 'is_published' => true,
            ]);
        }
        for ($i = 1; $i <= 20; $i++) {
            $upsert('achievements', "achievement-$i", [
                'title_ar' => $copy['achievements']['ar']["achievement$i"] ?? '',
                'title_en' => $copy['achievements']['en']["achievement$i"] ?? '',
                'position' => $i, 'is_published' => true,
            ]);
        }
        foreach (['experience' => 17, 'projects' => 27] as $index => $value) {
            DB::table('statistics')->updateOrInsert(['slug' => $index], [
                'title_ar' => $copy['counter']['ar'][$index] ?? '',
                'title_en' => $copy['counter']['en'][$index] ?? '',
                'value' => $value, 'position' => $index === 'experience' ? 0 : 1,
                'is_published' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        $upsert('site_settings', 'team-chart', [
            'title_ar' => $copy['page']['ar']['team_des'] ?? '',
            'title_en' => $copy['page']['en']['team_des'] ?? '',
            'body_ar' => '/media/team_ar.png', 'body_en' => '/media/team_en.png',
        ]);
        $upsert('site_settings', 'footer', [
            'body_ar' => $copy['footer']['ar']['footer_des'] ?? '',
            'body_en' => $copy['footer']['en']['footer_des'] ?? '',
        ]);
        foreach (['home' => ['العمودي', 'Al-Amoudi', 'about'], 'projects' => ['مشاريعنا', 'Our projects', 'projects'], 'team' => ['فريقنا', 'Our team', 'page'], 'contact' => ['تواصل معنا', 'Contact us', 'page']] as $slug => [$arTitle, $enTitle, $group]) {
            $upsert('site_settings', 'seo-'.$slug, [
                'title_ar' => $arTitle,
                'title_en' => $enTitle,
                'body_ar' => $group === 'about' ? ($copy['about']['ar']['description1'] ?? '') : ($copy[$group]['ar'][$slug === 'team' ? 'team_des' : ($slug === 'contact' ? 'contact' : 'project_des')] ?? ''),
                'body_en' => $group === 'about' ? ($copy['about']['en']['description1'] ?? '') : ($copy[$group]['en'][$slug === 'team' ? 'team_des' : ($slug === 'contact' ? 'contact' : 'project_des')] ?? ''),
            ]);
        }
        foreach (['madinah', 'jazan'] as $index => $slug) {
            $upsert('contact_locations', $slug, [
                'title_ar' => $slug === 'madinah' ? 'المدينة المنورة' : 'جازان',
                'title_en' => $slug === 'madinah' ? 'Al-Madinah' : 'Jazan',
                'body_ar' => $copy['page']['ar']['contact_address'.($index + 1)] ?? '',
                'body_en' => $copy['page']['en']['contact_address'.($index + 1)] ?? '',
                'email' => $slug === 'madinah' ? 'y2007d@hotmail.com' : 'm.h.al_amoudi@hotmail.com',
                'phone' => $slug === 'madinah' ? '+966 01 484 88881' : '+966 01 731 75088',
                'fax' => $slug === 'madinah' ? '106' : '+966 01 731 75088',
                'position' => $index, 'is_published' => true,
            ]);
        }
        foreach (['water' => ['مشروع المياة', 'Water projects'], 'solar' => ['مشروع الطاقة الشمسية', 'Solar projects'], 'architecture' => ['هندسة معمارية', 'Architecture'], 'housing' => ['مشاريع الإسكان', 'Housing projects']] as $slug => [$ar, $en]) {
            $upsert('project_categories', $slug, ['title_ar' => $ar, 'title_en' => $en]);
        }
        $images = ['solar1', 'solar2', 'solar3', 'solar4', 'solar5', 'solar6', 'solar7', 'project-1', 'project-2', 'project-3', 'project-4', 'project-5', 'project-6', 'villa11', 'villa12', 'villa10', 'villa9', 'villa8', 'villa7', 'villa6', 'villa5'];
        foreach ($images as $position => $image) {
            $category = str_starts_with($image, 'villa') ? 'housing' : (str_starts_with($image, 'project') ? 'architecture' : (in_array($image, ['solar4', 'solar6', 'solar7']) ? 'solar' : 'water'));
            $upsert('projects', $image, [
                'title_ar' => DB::table('project_categories')->where('slug', $category)->value('title_ar'),
                'title_en' => DB::table('project_categories')->where('slug', $category)->value('title_en'),
                'image' => "/media/works/$image.webp", 'position' => $position,
                'project_category_id' => DB::table('project_categories')->where('slug', $category)->value('id'),
                'is_published' => true,
            ]);
        }
    }
}
