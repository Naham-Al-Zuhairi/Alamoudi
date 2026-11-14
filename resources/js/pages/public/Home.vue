<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    ArrowLeft,
    ArrowRight,
    ArrowUpRight,
    Building2,
    HardHat,
    Settings2,
    UsersRound,
} from '@lucide/vue';
type RecordItem = {
    id: number;
    slug: string;
    title_ar: string;
    title_en: string;
    body_ar?: string;
    body_en?: string;
    image?: string;
    category?: RecordItem;
    value?: number;
};
const props = defineProps<{
    slides: RecordItem[];
    sections: RecordItem[];
    achievements: RecordItem[];
    statistics: RecordItem[];
    projects: RecordItem[];
}>();
const page = usePage();
const ar = computed(() => page.url.startsWith('/ar'));
const locale = computed(() => (ar.value ? 'ar' : 'en'));
const title = (x: RecordItem) => (ar.value ? x.title_ar : x.title_en);
const body = (x: RecordItem) => (ar.value ? x.body_ar : x.body_en);
const slide = ref(0);
const achievement = ref(0);
const heroPaused = ref(false);
const achievementsPaused = ref(false);
const serviceIcons = [Building2, HardHat, Settings2, UsersRound];
let heroTimer: ReturnType<typeof setInterval> | undefined;
let achievementTimer: ReturnType<typeof setInterval> | undefined;
const nextSlide = () => {
    if (props.slides.length > 1)
        slide.value = (slide.value + 1) % props.slides.length;
};
const moveAchievement = (direction: number) => {
    if (props.achievements.length > 1)
        achievement.value =
            (achievement.value + direction + props.achievements.length) %
            props.achievements.length;
};
onMounted(() => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    heroTimer = setInterval(() => {
        if (!heroPaused.value && !document.hidden) nextSlide();
    }, 6500);
    achievementTimer = setInterval(() => {
        if (!achievementsPaused.value && !document.hidden) moveAchievement(1);
    }, 7500);
});
onUnmounted(() => {
    if (heroTimer) clearInterval(heroTimer);
    if (achievementTimer) clearInterval(achievementTimer);
});
</script>
<template>
    <Head :title="ar ? 'العمودي للمقاولات' : 'Al-Amoudi Construction'" />
    <section
        v-if="slides.length"
        class="hero hero-carousel"
        aria-roledescription="carousel"
        :aria-label="ar ? 'معرض الصور الرئيسي' : 'Featured slides'"
        @mouseenter="heroPaused = true"
        @mouseleave="heroPaused = false"
        @focusin="heroPaused = true"
        @focusout="heroPaused = false"
    >
        <div
            v-for="(item, index) in slides"
            :key="item.id"
            class="hero-frame"
            :class="{ active: slide === index }"
            :aria-hidden="slide !== index"
        >
            <div
                class="hero-image"
                :style="{ backgroundImage: `url('${item.image}')` }"
            ></div>
        </div>
        <div class="hero-shade"></div>
        <div class="hero-inner container">
            <div class="hero-content" :key="slides[slide].id">
                <p class="eyebrow">{{ ar ? 'العمودي' : 'AL-AMOUDI' }}</p>
                <h1>{{ title(slides[slide]) }}</h1>
                <p v-if="body(slides[slide])" class="hero-description">
                    {{ body(slides[slide]) }}
                </p>
                <Link class="button" :href="`/${locale}/projects`"
                    >{{ ar ? 'عرض المشاريع' : 'View projects' }}
                    <ArrowUpRight :size="18" aria-hidden="true"
                /></Link>
            </div>
            <div class="hero-bottom">
                <div class="hero-progress" aria-hidden="true">
                    <span :key="slide"></span>
                </div>
                <div class="slide-controls">
                    <button
                        v-for="(_, i) in slides"
                        :key="i"
                        type="button"
                        :aria-label="`Slide ${i + 1}`"
                        :aria-current="slide === i"
                        @click="slide = i"
                    >
                        {{ String(i + 1).padStart(2, '0') }}
                    </button>
                </div>
                <span class="hero-count"
                    >{{ String(slide + 1).padStart(2, '0') }} /
                    {{ String(slides.length).padStart(2, '0') }}</span
                >
            </div>
        </div>
    </section>
    <section id="about" v-reveal class="section about-section">
        <div class="about-layout container">
            <div class="about-heading">
                <p class="eyebrow">{{ ar ? 'من نحن' : 'ABOUT US' }}</p>
                <h2>
                    {{
                        ar
                            ? 'مؤسسة محمد حسين أحمد العمودي'
                            : 'Mohamed Hussain Ahmed Al-Amoudi'
                    }}
                </h2>
                <span class="accent-rule"></span>
            </div>
            <div class="about-grid">
                <p
                    v-for="part in sections.filter((x) =>
                        x.slug.startsWith('about-'),
                    )"
                    :key="part.id"
                >
                    {{ body(part) }}
                </p>
                <Link class="text-link" :href="`/${locale}/contact`"
                    >{{ ar ? 'تواصل معنا' : 'Get in touch' }}
                    <ArrowUpRight :size="18" aria-hidden="true"
                /></Link>
            </div>
        </div>
    </section>
    <section v-reveal class="section pale pattern-section services-section">
        <div class="container">
            <p class="eyebrow">{{ ar ? 'خدماتنا' : 'OUR SERVICES' }}</p>
            <h2>{{ ar ? 'خدماتنا' : 'Our services' }}</h2>
            <div class="services-grid">
                <article
                    v-for="(service, index) in sections.filter((x) =>
                        x.slug.startsWith('service-'),
                    )"
                    :key="service.id"
                    class="service-card"
                >
                    <div class="service-icon">
                        <component
                            :is="serviceIcons[index % serviceIcons.length]"
                            :size="30"
                            :stroke-width="1.5"
                            aria-hidden="true"
                        />
                    </div>
                    <span class="service-number">{{
                        String(index + 1).padStart(2, '0')
                    }}</span>
                    <h3>{{ title(service) }}</h3>
                    <p>{{ body(service) }}</p>
                </article>
            </div>
        </div>
    </section>
    <section v-if="statistics.length" v-reveal class="stats-section">
        <div class="stats-grid container">
            <div v-for="stat in statistics" :key="stat.id">
                <strong>{{ stat.value }}</strong
                ><span>{{ title(stat) }}</span>
            </div>
            <small>{{
                ar
                    ? 'الأرقام كما وردت في الموقع السابق'
                    : 'Figures from the previous website'
            }}</small>
        </div>
    </section>
    <section v-reveal class="section work-section container">
        <div class="section-heading">
            <div>
                <p class="eyebrow">{{ ar ? 'أعمالنا' : 'OUR WORK' }}</p>
                <h2>{{ ar ? 'مشاريعنا' : 'Our projects' }}</h2>
            </div>
            <Link :href="`/${locale}/projects`"
                >{{ ar ? 'عرض جميع المشاريع' : 'All projects' }} ↗</Link
            >
        </div>
        <div class="project-grid">
            <Link
                v-for="project in projects"
                :key="project.id"
                class="project-card"
                :href="`/${locale}/projects/${project.slug}`"
                ><img
                    :src="project.image"
                    :alt="title(project)"
                    loading="lazy"
                />
                <div>
                    <small>{{
                        project.category ? title(project.category) : ''
                    }}</small>
                    <h3>{{ title(project) }}</h3>
                </div></Link
            >
        </div>
    </section>
    <section
        v-if="achievements.length"
        v-reveal
        class="section dark-section achievements-section"
    >
        <div class="container">
            <div class="achievements-intro">
                <p class="eyebrow">{{ ar ? 'الإنجازات' : 'ACHIEVEMENTS' }}</p>
                <h2>
                    {{
                        ar
                            ? 'إنجازات مؤسسة محمد حسين العمودي'
                            : 'The achievements of Al-Amoudi foundation'
                    }}
                </h2>
            </div>
            <div
                class="achievement-carousel"
                aria-roledescription="carousel"
                :aria-label="ar ? 'إنجازاتنا' : 'Our achievements'"
                @mouseenter="achievementsPaused = true"
                @mouseleave="achievementsPaused = false"
                @focusin="achievementsPaused = true"
                @focusout="achievementsPaused = false"
            >
                <div class="achievement-backdrop" aria-hidden="true">
                    {{ String(achievement + 1).padStart(2, '0') }}
                </div>
                <Transition name="achievement-swap" mode="out-in">
                    <article
                        :key="achievements[achievement].id"
                        class="achievement-card"
                        aria-live="polite"
                    >
                        <span class="achievement-mark" aria-hidden="true"
                            >“</span
                        >
                        <p>{{ title(achievements[achievement]) }}</p>
                        <div class="achievement-footer">
                            <span>{{ ar ? 'إنجاز' : 'Achievement' }}</span
                            ><strong
                                >{{ String(achievement + 1).padStart(2, '0') }}
                                <small
                                    >/
                                    {{
                                        String(achievements.length).padStart(
                                            2,
                                            '0',
                                        )
                                    }}</small
                                ></strong
                            >
                        </div>
                    </article>
                </Transition>
                <div class="carousel-arrows">
                    <button
                        type="button"
                        :aria-label="ar ? 'السابق' : 'Previous achievement'"
                        @click="moveAchievement(-1)"
                    >
                        <ArrowLeft :size="20" aria-hidden="true" />
                    </button>
                    <button
                        type="button"
                        :aria-label="ar ? 'التالي' : 'Next achievement'"
                        @click="moveAchievement(1)"
                    >
                        <ArrowRight :size="20" aria-hidden="true" />
                    </button>
                </div>
                <div
                    class="achievement-progress"
                    :aria-label="ar ? 'اختيار الإنجاز' : 'Choose achievement'"
                >
                    <button
                        v-for="(_, index) in achievements"
                        :key="index"
                        type="button"
                        :aria-label="`${ar ? 'الإنجاز' : 'Achievement'} ${index + 1}`"
                        :aria-current="achievement === index"
                        @click="achievement = index"
                    >
                        <span
                            v-if="achievement === index"
                            :key="achievement"
                        ></span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>
