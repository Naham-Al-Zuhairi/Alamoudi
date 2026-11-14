<script setup lang="ts">
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
defineProps<{
    chart: {
        title_ar: string;
        title_en: string;
        body_ar: string;
        body_en: string;
    } | null;
}>();
const ar = computed(() => usePage().url.startsWith('/ar'));
</script>
<template>
    <Head :title="ar ? 'فريقنا' : 'Our team'" />
    <section class="page-banner">
        <div class="container">
            <p class="eyebrow">AL-AMOUDI</p>
            <h1>{{ ar ? 'فريقنا' : 'Our team' }}</h1>
            <p>
                {{
                    ar
                        ? 'تعرّف على الهيكل التنظيمي للمؤسسة.'
                        : 'Explore the foundation’s organization chart.'
                }}
            </p>
        </div>
    </section>
    <section v-reveal class="section team-section container">
        <h2>{{ chart ? (ar ? chart.title_ar : chart.title_en) : '' }}</h2>
        <img
            v-if="chart"
            class="team-chart"
            :src="ar ? chart.body_ar : chart.body_en"
            :alt="ar ? 'الهيكل التنظيمي' : 'Organization chart'"
        />
    </section>
</template>
