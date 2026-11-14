<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @php
            $publicRoute = request()->routeIs('public.*');
            $routeKey = request()->routeIs('public.project') ? 'projects' : str_replace('public.', '', request()->route()?->getName() ?? '');
            $seo = $publicRoute && \Illuminate\Support\Facades\Schema::hasTable('site_settings') ? \App\Models\SiteSetting::where('slug', 'seo-'.$routeKey)->where('is_published', true)->first() : null;
            $locale = app()->getLocale();
            $project = request()->routeIs('public.project') ? request()->route('project') : null;
            $seoTitle = $project?->{'title_'.$locale} ?? $seo?->{'title_'.$locale} ?? 'Al-Amoudi';
            $seoDescription = $project?->{'body_'.$locale} ?? $seo?->{'body_'.$locale} ?? '';
            $otherLocale = $locale === 'ar' ? 'en' : 'ar';
            $otherPath = preg_replace('#^(ar|en)(/|$)#', $otherLocale.'$2', request()->path());
        @endphp
        @if($publicRoute)
            <meta name="description" content="{{ \Illuminate\Support\Str::limit($seoDescription, 160) }}">
            <meta property="og:title" content="{{ $seoTitle }}">
            <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($seoDescription, 160) }}">
            <link rel="canonical" href="{{ url()->current() }}">
            <link rel="alternate" hreflang="{{ $otherLocale }}" href="{{ url($otherPath) }}">
        @else
            <meta name="robots" content="noindex,nofollow">
        @endif

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ config('app.name', 'Laravel') }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
