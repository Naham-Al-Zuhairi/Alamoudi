<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Achievement;
use App\Models\ContactLocation;
use App\Models\ContactMessage;
use App\Models\HeroSlide;
use App\Models\PageSection;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\SiteSetting;
use App\Models\Statistic;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PublicSiteController extends Controller
{
    public function home(string $locale): Response
    {
        return Inertia::render('public/Home', [
            'slides' => HeroSlide::published()->get(),
            'sections' => PageSection::published()->get(),
            'achievements' => Achievement::published()->get(),
            'statistics' => Statistic::published()->get(),
            'projects' => Project::published()->with('category')->limit(6)->get(),
            'footer' => SiteSetting::where('slug', 'footer')->first(),
        ]);
    }

    public function projects(string $locale): Response
    {
        $category = request('category');

        return Inertia::render('public/Projects', [
            'projects' => Project::published()->with('category')->when($category, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $category)))->paginate(12)->withQueryString(),
            'categories' => ProjectCategory::published()->get(), 'activeCategory' => $category,
        ]);
    }

    public function project(string $locale, Project $project): Response
    {
        abort_unless($project->is_published, 404);

        return Inertia::render('public/Project', ['project' => $project->load('category', 'images')]);
    }

    public function team(string $locale): Response
    {
        return Inertia::render('public/Team', ['chart' => SiteSetting::published()->where('slug', 'team-chart')->first()]);
    }

    public function contact(string $locale): Response
    {
        return Inertia::render('public/Contact', ['locations' => ContactLocation::published()->get()]);
    }

    public function send(ContactRequest $request, string $locale): RedirectResponse
    {
        ContactMessage::create($request->safe()->except('contact_ref_code'));

        return back()->with('success', $locale === 'ar' ? 'تم إرسال رسالتك بنجاح.' : 'Your message has been sent.');
    }
}
