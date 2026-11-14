import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name.startsWith('public/'):
                return PublicLayout;
            case name.startsWith('admin/'):
                return AdminLayout;
            case name === 'Welcome':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    withApp: (app) => {
        const revealObservers = new WeakMap<
            HTMLElement,
            IntersectionObserver
        >();
        app.directive('reveal', {
            mounted(el: HTMLElement) {
                if (
                    !('IntersectionObserver' in window) ||
                    window.matchMedia('(prefers-reduced-motion: reduce)')
                        .matches
                )
                    return;
                el.classList.add('reveal-pending');
                const observer = new IntersectionObserver(
                    (entries) => {
                        if (entries.some((entry) => entry.isIntersecting)) {
                            el.classList.add('revealed');
                            observer.disconnect();
                        }
                    },
                    { threshold: 0.08 },
                );
                revealObservers.set(el, observer);
                observer.observe(el);
            },
            unmounted(el: HTMLElement) {
                revealObservers.get(el)?.disconnect();
                revealObservers.delete(el);
            },
        });
        app.directive('focus', {
            mounted: (el: HTMLElement, shouldFocus) => {
                if (shouldFocus.value !== false) {
                    el.focus();
                }
            },
        });
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
