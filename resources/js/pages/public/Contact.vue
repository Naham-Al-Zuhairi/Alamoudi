<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Mail, MapPin, Phone, Send } from '@lucide/vue';
type Location = {
    id: number;
    title_ar: string;
    title_en: string;
    body_ar: string;
    body_en: string;
    email: string;
    phone: string;
    fax: string;
};
defineProps<{ locations: Location[] }>();
const page = usePage();
const ar = computed(() => page.url.startsWith('/ar'));
const l = computed(() => (ar.value ? 'ar' : 'en'));
const form = useForm({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
    contact_ref_code: '',
});
const success = computed(
    () => (page.props.flash as { success?: string } | undefined)?.success,
);
const submitted = ref(false);
const failed = ref(false);
const fieldLabels = computed<Record<string, string>>(() =>
    ar.value
        ? {
              name: 'الاسم الكامل',
              email: 'البريد الإلكتروني',
              phone: 'الهاتف',
              subject: 'الموضوع',
              message: 'الرسالة',
              contact_ref_code: 'التحقق من النموذج',
          }
        : {
              name: 'Full name',
              email: 'Email address',
              phone: 'Phone',
              subject: 'Subject',
              message: 'Message',
              contact_ref_code: 'Form verification',
          },
);
const errorFields = computed(() =>
    Object.keys(form.errors).map((key) => fieldLabels.value[key] || key),
);
const submit = () => {
    submitted.value = false;
    failed.value = false;
    form.post(`/${l.value}/contact`, {
        preserveScroll: true,
        onSuccess: () => {
            submitted.value = true;
            form.reset();
        },
        onError: () => {
            failed.value = true;
        },
    });
};
</script>
<template>
    <Head :title="ar ? 'تواصل معنا' : 'Contact us'" />
    <section class="page-banner">
        <div class="container">
            <p class="eyebrow">AL-AMOUDI</p>
            <h1>{{ ar ? 'تواصل معنا' : 'Contact us' }}</h1>
            <p>
                {{
                    ar
                        ? 'يسعدنا استقبال استفساراتكم والتواصل معكم.'
                        : 'We welcome your inquiries and look forward to hearing from you.'
                }}
            </p>
        </div>
    </section>
    <section v-reveal class="section contact-section pattern-section">
        <div class="contact-grid container">
            <div class="contact-info">
                <p class="eyebrow">
                    {{ ar ? 'لنبقَ على تواصل' : 'GET IN TOUCH' }}
                </p>
                <h2>{{ ar ? 'فروعنا' : 'Our offices' }}</h2>
                <p class="contact-intro">
                    {{
                        ar
                            ? 'يمكنك التواصل معنا عبر بيانات الفروع أو إرسال رسالة مباشرة.'
                            : 'Reach our offices directly or send us a message using the form.'
                    }}
                </p>
                <article
                    v-for="location in locations"
                    :key="location.id"
                    class="location-card"
                >
                    <h3>
                        <MapPin :size="19" aria-hidden="true" />
                        {{ ar ? location.title_ar : location.title_en }}
                    </h3>
                    <p>{{ ar ? location.body_ar : location.body_en }}</p>
                    <a
                        v-if="location.email"
                        :href="`mailto:${location.email}`"
                        dir="ltr"
                        ><Mail :size="16" aria-hidden="true" />{{
                            location.email
                        }}</a
                    >
                    <a
                        v-if="location.phone"
                        :href="`tel:${location.phone.replace(/\s+/g, '')}`"
                        dir="ltr"
                        ><Phone :size="16" aria-hidden="true" />{{
                            location.phone
                        }}</a
                    >
                </article>
            </div>
            <div class="contact-form-panel">
                <p class="eyebrow">{{ ar ? 'راسلنا' : 'SEND AN INQUIRY' }}</p>
                <h2>{{ ar ? 'أرسل رسالة' : 'Send a message' }}</h2>
                <p>
                    {{
                        ar
                            ? 'نستخدم البيانات التي ترسلها للرد على استفسارك، ويمكن للموظفين المخولين الاطلاع عليها.'
                            : 'We use the details you submit to respond to your inquiry. Authorized staff can view your message.'
                    }}
                </p>
                <form @submit.prevent="submit" class="contact-form">
                    <div class="honeypot" aria-hidden="true">
                        <input
                            v-model="form.contact_ref_code"
                            type="text"
                            tabindex="-1"
                            autocomplete="off"
                            readonly
                        />
                    </div>
                    <label
                        >{{ ar ? 'الاسم الكامل' : 'Full name'
                        }}<input
                            v-model="form.name"
                            required
                            maxlength="120"
                        /><small v-if="form.errors.name" role="alert">{{
                            form.errors.name
                        }}</small></label
                    ><label
                        >{{ ar ? 'البريد الإلكتروني' : 'Email address'
                        }}<input
                            v-model="form.email"
                            type="email"
                            required
                        /><small v-if="form.errors.email" role="alert">{{
                            form.errors.email
                        }}</small></label
                    ><label
                        >{{ ar ? 'الهاتف (اختياري)' : 'Phone (optional)'
                        }}<input v-model="form.phone" type="tel" /><small
                            v-if="form.errors.phone"
                            role="alert"
                            >{{ form.errors.phone }}</small
                        ></label
                    ><label
                        >{{ ar ? 'الموضوع' : 'Subject'
                        }}<input v-model="form.subject" required /><small
                            v-if="form.errors.subject"
                            role="alert"
                            >{{ form.errors.subject }}</small
                        ></label
                    ><label
                        >{{ ar ? 'الرسالة' : 'Message'
                        }}<textarea
                            v-model="form.message"
                            required
                            minlength="10"
                            rows="6"
                        /><small v-if="form.errors.message" role="alert">{{
                            form.errors.message
                        }}</small></label
                    ><button
                        class="button"
                        type="submit"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing
                                ? ar
                                    ? 'جارٍ الإرسال...'
                                    : 'Sending...'
                                : ar
                                  ? 'إرسال الرسالة'
                                  : 'Send message'
                        }}
                        <Send :size="18" aria-hidden="true" />
                    </button>
                    <div v-if="failed" role="alert" class="form-error-summary">
                        <strong>{{
                            ar
                                ? 'يرجى مراجعة البيانات التالية:'
                                : 'Please check these fields:'
                        }}</strong>
                        <ul v-if="errorFields.length">
                            <li v-for="field in errorFields" :key="field">
                                {{ field }}
                            </li>
                        </ul>
                        <p v-else>
                            {{
                                ar
                                    ? 'تعذر إرسال الرسالة. يرجى المحاولة مرة أخرى.'
                                    : 'Your message could not be sent. Please try again.'
                            }}
                        </p>
                    </div>
                    <p
                        v-if="submitted || success"
                        role="status"
                        class="success"
                    >
                        {{
                            ar
                                ? 'تم إرسال رسالتك بنجاح. شكراً لتواصلك معنا.'
                                : 'Your message was sent successfully. Thank you for contacting us.'
                        }}
                    </p>
                </form>
            </div>
        </div>
    </section>
</template>
