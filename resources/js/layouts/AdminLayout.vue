<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    BarChart3,
    ChevronRight,
    FolderKanban,
    Images,
    Inbox,
    LayoutDashboard,
    LogOut,
    MapPin,
    Menu,
    Settings2,
    ShieldCheck,
    Sparkles,
    UsersRound,
    X,
} from '@lucide/vue';
const page = usePage();
const open = ref(false);
const user = computed(
    () =>
        page.props.auth?.user as { name?: string; email?: string } | undefined,
);
const nav = [
    { label: 'Dashboard', href: '/admin', icon: LayoutDashboard },
    { label: 'Hero slides', href: '/admin/content/slides', icon: Images },
    { label: 'Page sections', href: '/admin/content/sections', icon: Sparkles },
    {
        label: 'Achievements',
        href: '/admin/content/achievements',
        icon: ShieldCheck,
    },
    { label: 'Statistics', href: '/admin/content/statistics', icon: BarChart3 },
    {
        label: 'Project categories',
        href: '/admin/content/categories',
        icon: FolderKanban,
    },
    { label: 'Projects', href: '/admin/content/projects', icon: FolderKanban },
    { label: 'Locations', href: '/admin/content/locations', icon: MapPin },
    {
        label: 'Site settings',
        href: '/admin/content/settings',
        icon: Settings2,
    },
    { label: 'Inbox', href: '/admin/inbox', icon: Inbox },
];
const active = (href: string) =>
    href === '/admin' ? page.url === href : page.url.startsWith(href);
const logout = () => router.post('/logout');
onMounted(() => {
    document.documentElement.lang = 'en';
    document.documentElement.dir = 'ltr';
});
</script>
<template>
    <div class="admin-shell">
        <aside id="admin-sidebar" :class="{ open }">
            <Link href="/admin" class="admin-brand" @click="open = false"
                ><img src="/media/logo-light.png" alt="Al-Amoudi" /><span
                    >Content studio<small>AL-AMOUDI</small></span
                ></Link
            >
            <p class="admin-nav-label">WORKSPACE</p>
            <nav aria-label="Admin navigation">
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    :class="{ active: active(item.href) }"
                    @click="open = false"
                    ><component
                        :is="item.icon"
                        :size="18"
                        aria-hidden="true" /><span>{{ item.label }}</span
                    ><ChevronRight
                        :size="15"
                        class="admin-nav-chevron"
                        aria-hidden="true"
                /></Link>
            </nav>
            <div class="admin-sidebar-footer">
                <Link href="/ar" @click="open = false"
                    >View public site <span aria-hidden="true">↗</span></Link
                >
            </div>
        </aside>
        <div v-if="open" class="admin-backdrop" @click="open = false"></div>
        <div class="admin-content">
            <header class="admin-topbar">
                <button
                    class="admin-menu-toggle"
                    type="button"
                    :aria-expanded="open"
                    aria-controls="admin-sidebar"
                    :aria-label="open ? 'Close menu' : 'Open menu'"
                    @click="open = !open"
                >
                    <X v-if="open" :size="22" /><Menu v-else :size="22" />
                </button>
                <div class="admin-breadcrumb">
                    <span>AL-AMOUDI</span
                    ><ChevronRight :size="15" aria-hidden="true" /><strong
                        >Content management</strong
                    >
                </div>
                <div class="admin-user">
                    <UsersRound :size="17" aria-hidden="true" /><span>{{
                        user?.name || 'Administrator'
                    }}</span>
                </div>
                <button type="button" class="admin-logout" @click="logout">
                    <LogOut :size="17" aria-hidden="true" /><span>Log out</span>
                </button>
            </header>
            <main><slot /></main>
        </div>
    </div>
</template>
