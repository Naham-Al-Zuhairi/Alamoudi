<script setup lang="ts">
import { computed, ref, watchEffect } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
const page = usePage();
const locale = computed(() => (page.url.split('/')[1] === 'en' ? 'en' : 'ar'));
const ar = computed(() => locale.value === 'ar');
const other = computed(
    () =>
        '/' +
        (ar.value ? 'en' : 'ar') +
        page.url.replace(/^\/(ar|en)/, '').split('?')[0],
);
const menu = ref(false);
const footer = computed(
    () =>
        page.props.siteFooter as { body_ar?: string; body_en?: string } | null,
);
watchEffect(() => {
    document.documentElement.lang = locale.value;
    document.documentElement.dir = ar.value ? 'rtl' : 'ltr';
});
const links = computed(() => [
    { href: `/${locale.value}`, label: ar.value ? 'الرئيسية' : 'Home' },
    { href: `/${locale.value}#about`, label: ar.value ? 'من نحن' : 'About us' },
    { href: `/${locale.value}/team`, label: ar.value ? 'فريقنا' : 'Our team' },
    {
        href: `/${locale.value}/projects`,
        label: ar.value ? 'مشاريعنا' : 'Projects',
    },
    {
        href: `/${locale.value}/contact`,
        label: ar.value ? 'تواصل معنا' : 'Contact',
    },
]);
</script>
<template>
    <div :lang="locale" :dir="ar ? 'rtl' : 'ltr'" class="public-shell">
        <header class="site-header">
            <div class="nav-row container">
                <Link :href="`/${locale}`" class="logo"
                    ><img src="/media/logo.png" alt="Al-Amoudi"
                /></Link>
                <button
                    class="menu-toggle"
                    type="button"
                    :aria-expanded="menu"
                    aria-controls="main-nav"
                    @click="menu = !menu"
                >
                    {{ ar ? 'القائمة' : 'Menu' }}
                </button>
                <nav
                    id="main-nav"
                    :class="{ open: menu }"
                    :aria-label="ar ? 'التنقل الرئيسي' : 'Main navigation'"
                >
                    <Link
                        v-for="item in links"
                        :key="item.href"
                        :href="item.href"
                        @click="menu = false"
                        >{{ item.label }}</Link
                    >
                    <Link :href="other" hreflang="en" class="language">{{
                        ar ? 'English' : 'العربية'
                    }}</Link>
                </nav>
            </div>
        </header>
        <main><slot /></main>
        <footer class="site-footer">
            <div class="footer-grid container">
                <img src="/media/logo-light.png" alt="Al-Amoudi" />
                <div>
                    <p>
                        {{
                            footer ? (ar ? footer.body_ar : footer.body_en) : ''
                        }}
                    </p>
                    <Link :href="`/${locale}/contact`">{{
                        ar ? 'تواصل معنا' : 'Contact us'
                    }}</Link>
                </div>
                <small>© {{ new Date().getFullYear() }} Al-Amoudi</small>
            </div>
        </footer>
    </div>
</template>
