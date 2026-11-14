<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowUpRight, FolderKanban, Inbox, Sparkles } from '@lucide/vue';
defineProps<{
    counts: { projects: number; messages: number; unread: number };
}>();
</script>
<template>
    <Head title="Admin dashboard" />
    <div class="admin-page">
        <div class="admin-welcome">
            <div>
                <p class="eyebrow">CONTENT STUDIO</p>
                <h1>Dashboard</h1>
                <p>Manage the bilingual website and keep every page current.</p>
            </div>
            <Link href="/ar" class="admin-view-site"
                >View live site <ArrowUpRight :size="18" aria-hidden="true"
            /></Link>
        </div>
        <div class="admin-section-title">
            <div>
                <p class="eyebrow">OVERVIEW</p>
                <h2>At a glance</h2>
            </div>
        </div>
        <div class="admin-stats">
            <Link href="/admin/content/projects"
                ><FolderKanban :size="24" aria-hidden="true" /><strong>{{
                    counts.projects
                }}</strong
                ><span>Projects</span
                ><ArrowUpRight
                    class="admin-stat-arrow"
                    :size="18"
                    aria-hidden="true" /></Link
            ><Link href="/admin/inbox"
                ><Inbox :size="24" aria-hidden="true" /><strong>{{
                    counts.messages
                }}</strong
                ><span>Messages</span
                ><ArrowUpRight
                    class="admin-stat-arrow"
                    :size="18"
                    aria-hidden="true" /></Link
            ><Link href="/admin/inbox?filter=unread"
                ><Sparkles :size="24" aria-hidden="true" /><strong>{{
                    counts.unread
                }}</strong
                ><span>Unread</span
                ><ArrowUpRight
                    class="admin-stat-arrow"
                    :size="18"
                    aria-hidden="true"
            /></Link>
        </div>
        <div class="admin-quick-actions">
            <div>
                <p class="eyebrow">QUICK ACCESS</p>
                <h2>Continue editing</h2>
                <p>Update Arabic and English content from one workspace.</p>
            </div>
            <div>
                <Link href="/admin/content/slides"
                    >Hero slides
                    <ArrowUpRight :size="18" aria-hidden="true" /></Link
                ><Link href="/admin/content/projects"
                    >Projects
                    <ArrowUpRight :size="18" aria-hidden="true" /></Link
                ><Link href="/admin/inbox"
                    >Inbox <ArrowUpRight :size="18" aria-hidden="true"
                /></Link>
            </div>
        </div>
    </div>
</template>
