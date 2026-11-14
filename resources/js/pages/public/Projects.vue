<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
type Item = {
    id: number;
    slug: string;
    title_ar: string;
    title_en: string;
    image: string;
    category?: Item;
};
defineProps<{
    projects: {
        data: Item[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    categories: Item[];
    activeCategory?: string;
}>();
const ar = computed(() => usePage().url.startsWith('/ar'));
const l = computed(() => (ar.value ? 'ar' : 'en'));
const t = (x: Item) => (ar.value ? x.title_ar : x.title_en);
</script>
<template>
    <Head :title="ar ? 'مشاريعنا' : 'Our projects'" />
    <section class="page-banner">
        <div class="container">
            <p class="eyebrow">AL-AMOUDI</p>
            <h1>{{ ar ? 'مشاريعنا' : 'Our projects' }}</h1>
            <p>
                {{
                    ar
                        ? 'استكشف أعمالنا حسب فئة المشروع.'
                        : 'Explore our work by project category.'
                }}
            </p>
        </div>
    </section>
    <div v-reveal class="section projects-listing container">
        <nav class="filters" aria-label="Project categories">
            <Link
                :href="`/${l}/projects`"
                :class="{ active: !activeCategory }"
                >{{ ar ? 'الكل' : 'All' }}</Link
            ><Link
                v-for="category in categories"
                :key="category.id"
                :href="`/${l}/projects?category=${category.slug}`"
                :class="{ active: activeCategory === category.slug }"
                >{{ t(category) }}</Link
            >
        </nav>
        <div v-if="projects.data.length" class="project-grid">
            <Link
                v-for="project in projects.data"
                :key="project.id"
                :href="`/${l}/projects/${project.slug}`"
                class="project-card"
                ><img :src="project.image" :alt="t(project)" loading="lazy" />
                <div>
                    <small>{{
                        project.category ? t(project.category) : ''
                    }}</small>
                    <h2>{{ t(project) }}</h2>
                </div></Link
            >
        </div>
        <p v-else>
            {{
                ar
                    ? 'لا توجد مشاريع في هذه الفئة.'
                    : 'No projects in this category.'
            }}
        </p>
        <nav class="pagination" aria-label="Pages">
            <Link
                v-for="(link, i) in projects.links"
                :key="i"
                v-show="link.url"
                :href="link.url || ''"
                :aria-current="link.active ? 'page' : undefined"
                >{{
                    i === 0
                        ? '‹'
                        : i === projects.links.length - 1
                          ? '›'
                          : link.label
                }}</Link
            >
        </nav>
    </div>
</template>
