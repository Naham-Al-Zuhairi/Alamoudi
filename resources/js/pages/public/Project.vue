<script setup lang="ts">
import { computed, nextTick, ref, watch } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowLeft, ArrowRight, Expand, X } from '@lucide/vue';
type Item = {
    id: number;
    title_ar: string;
    title_en: string;
    body_ar?: string;
    body_en?: string;
    image: string;
    category?: { title_ar: string; title_en: string } | null;
    images: { id: number; path: string }[];
};
const props = defineProps<{ project: Item }>();
const ar = computed(() => usePage().url.startsWith('/ar'));
const l = computed(() => (ar.value ? 'ar' : 'en'));
const gallery = computed(() =>
    [
        props.project.image,
        ...props.project.images.map((image) => image.path),
    ].filter((path, index, all) => path && all.indexOf(path) === index),
);
const selectedIndex = ref(0);
const detailsExpanded = ref(false);
const detailText = computed(
    () => (ar.value ? props.project.body_ar : props.project.body_en) || '',
);
const canExpandDetails = computed(() => detailText.value.length > 350);
const selectedImage = computed(
    () => gallery.value[selectedIndex.value] || props.project.image,
);
const lightbox = ref<HTMLDialogElement | null>(null);
const expandButton = ref<HTMLButtonElement | null>(null);
const thumbnailStrip = ref<HTMLElement | null>(null);
const imageLabel = (index: number) =>
    index === 0
        ? ar.value
            ? 'الصورة الرئيسية'
            : 'Featured image'
        : ar.value
          ? `صورة المشروع ${index + 1}`
          : `Project image ${index + 1}`;
const selectImage = async (index: number) => {
    selectedIndex.value = index;
    await nextTick();
    thumbnailStrip.value
        ?.querySelector('[aria-current="true"]')
        ?.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'center',
        });
};
const moveImage = (direction: number) => {
    if (gallery.value.length > 1)
        void selectImage(
            (selectedIndex.value + direction + gallery.value.length) %
                gallery.value.length,
        );
};
const openLightbox = () => lightbox.value?.showModal();
const closeLightbox = () => {
    if (lightbox.value?.open) lightbox.value.close();
};
const handleGalleryKeys = (event: KeyboardEvent) => {
    if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
        event.preventDefault();
        moveImage(
            event.key === 'ArrowRight'
                ? ar.value
                    ? -1
                    : 1
                : ar.value
                  ? 1
                  : -1,
        );
    }
};
watch(
    () => props.project.id,
    () => {
        selectedIndex.value = 0;
        detailsExpanded.value = false;
        closeLightbox();
    },
);
</script>
<template>
    <Head :title="ar ? project.title_ar : project.title_en" />
    <section class="page-banner">
        <div class="container">
            <Link :href="`/${l}/projects`" class="banner-back"
                ><ArrowLeft :size="17" aria-hidden="true" />{{
                    ar ? 'المشاريع' : 'Projects'
                }}</Link
            >
            <h1>{{ ar ? project.title_ar : project.title_en }}</h1>
            <p v-if="project.category">
                {{ ar ? project.category.title_ar : project.category.title_en }}
            </p>
        </div>
    </section>
    <div v-reveal class="section project-detail container">
        <div class="project-gallery-viewer">
            <div class="project-image-stage" aria-live="polite">
                <img
                    :key="selectedImage"
                    class="detail-image"
                    :src="selectedImage"
                    :alt="imageLabel(selectedIndex)"
                />
                <span class="project-image-count" dir="ltr"
                    >{{ String(selectedIndex + 1).padStart(2, '0') }} /
                    {{ String(gallery.length).padStart(2, '0') }}</span
                >
                <button
                    ref="expandButton"
                    class="project-expand"
                    type="button"
                    :aria-label="
                        ar
                            ? 'عرض الصورة بالحجم الكامل'
                            : 'View image full screen'
                    "
                    @click="openLightbox"
                >
                    <Expand :size="19" aria-hidden="true" />{{
                        ar ? 'عرض الصورة' : 'View image'
                    }}
                </button>
                <div v-if="gallery.length > 1" class="project-stage-controls">
                    <button
                        type="button"
                        :aria-label="ar ? 'الصورة السابقة' : 'Previous image'"
                        @click="moveImage(-1)"
                    >
                        <ArrowLeft :size="22" aria-hidden="true" />
                    </button>
                    <button
                        type="button"
                        :aria-label="ar ? 'الصورة التالية' : 'Next image'"
                        @click="moveImage(1)"
                    >
                        <ArrowRight :size="22" aria-hidden="true" />
                    </button>
                </div>
            </div>
            <div v-if="gallery.length > 1" class="project-image-strip-wrap">
                <p class="eyebrow">
                    {{ ar ? 'تصفح صور المشروع' : 'EXPLORE THE GALLERY' }}
                </p>
                <div
                    ref="thumbnailStrip"
                    class="project-image-strip"
                    :aria-label="
                        ar ? 'اختيار صورة المشروع' : 'Choose a project image'
                    "
                >
                    <button
                        v-for="(path, index) in gallery"
                        :key="path"
                        type="button"
                        :aria-label="imageLabel(index)"
                        :aria-current="selectedIndex === index"
                        @click="selectImage(index)"
                    >
                        <img
                            :src="path"
                            :alt="imageLabel(index)"
                            loading="lazy"
                        />
                        <span>{{ String(index + 1).padStart(2, '0') }}</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="project-detail-copy">
            <div>
                <p class="eyebrow">
                    {{ ar ? 'تفاصيل المشروع' : 'PROJECT DETAILS' }}
                </p>
                <h2>{{ ar ? project.title_ar : project.title_en }}</h2>
                <p v-if="project.category" class="project-category-label">
                    {{
                        ar
                            ? project.category.title_ar
                            : project.category.title_en
                    }}
                </p>
                <p
                    v-if="detailText"
                    class="project-description"
                    :class="{ collapsed: canExpandDetails && !detailsExpanded }"
                >
                    {{ detailText }}
                </p>
                <button
                    v-if="canExpandDetails"
                    type="button"
                    class="project-read-more"
                    :aria-expanded="detailsExpanded"
                    @click="detailsExpanded = !detailsExpanded"
                >
                    {{
                        detailsExpanded
                            ? ar
                                ? 'عرض أقل'
                                : 'Show less'
                            : ar
                              ? 'اقرأ المزيد'
                              : 'Read more'
                    }}
                </button>
            </div>
            <Link class="text-link" :href="`/${l}/projects`"
                >{{ ar ? 'العودة إلى المشاريع' : 'Back to projects' }}
                <ArrowLeft :size="18" aria-hidden="true"
            /></Link>
        </div>
    </div>
    <dialog
        ref="lightbox"
        class="project-lightbox"
        :dir="ar ? 'rtl' : 'ltr'"
        :aria-label="ar ? 'عرض صور المشروع' : 'Project image viewer'"
        @keydown="handleGalleryKeys"
        @close="expandButton?.focus()"
        @click.self="closeLightbox"
    >
        <div class="project-lightbox-toolbar">
            <span
                >{{ ar ? project.title_ar : project.title_en }} ·
                {{ selectedIndex + 1 }} / {{ gallery.length }}</span
            ><button
                type="button"
                :aria-label="ar ? 'إغلاق العرض' : 'Close viewer'"
                @click="closeLightbox"
            >
                <X :size="25" aria-hidden="true" />
            </button>
        </div>
        <div class="project-lightbox-image">
            <button
                v-if="gallery.length > 1"
                type="button"
                :aria-label="ar ? 'الصورة السابقة' : 'Previous image'"
                @click="moveImage(-1)"
            >
                <ArrowLeft :size="26" aria-hidden="true" />
            </button>
            <img :src="selectedImage" :alt="imageLabel(selectedIndex)" />
            <button
                v-if="gallery.length > 1"
                type="button"
                :aria-label="ar ? 'الصورة التالية' : 'Next image'"
                @click="moveImage(1)"
            >
                <ArrowRight :size="26" aria-hidden="true" />
            </button>
        </div>
        <p class="project-lightbox-caption">{{ imageLabel(selectedIndex) }}</p>
    </dialog>
</template>
