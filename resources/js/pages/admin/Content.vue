<script setup lang="ts">
import { nextTick, ref, watch } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
type Row = {
    id: number;
    slug: string;
    title_ar: string | null;
    title_en: string | null;
    body_ar: string | null;
    body_en: string | null;
    image: string | null;
    position: number;
    is_published: boolean;
    project_category_id?: number | null;
    email?: string | null;
    phone?: string | null;
    fax?: string | null;
    value?: number;
    images?: { id: number; path: string }[];
};
const props = defineProps<{
    type: string;
    rows: {
        data: Row[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    categories: Row[];
}>();
const selected = ref<Row | null>(null);
const blank = () => ({
    slug: '',
    title_ar: '',
    title_en: '',
    body_ar: '',
    body_en: '',
    image: '',
    position: 0,
    is_published: true,
    project_category_id: null as number | null,
    email: '',
    phone: '',
    fax: '',
    value: 0,
});
const form = useForm(blank());
const uploadForm = useForm<{ image: File | null }>({ image: null });
const galleryForm = useForm<{ images: File[] }>({ images: [] });
const galleryInput = ref<HTMLInputElement | null>(null);
const gallerySection = ref<HTMLElement | null>(null);
const openGallery = async (row: Row) => {
    selected.value = row;
    await nextTick();
    gallerySection.value?.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
    });
};
const addGallery = () => {
    if (selected.value)
        galleryForm.post(`/admin/projects/${selected.value.id}/images`, {
            forceFormData: true,
            onSuccess: () => {
                galleryForm.reset();
                if (galleryInput.value) galleryInput.value.value = '';
            },
        });
};
const removeGallery = (id: number) => {
    if (selected.value && confirm('Remove this image?'))
        router.delete(`/admin/projects/${selected.value.id}/images/${id}`);
};
watch(
    () => props.rows.data,
    (rows) => {
        if (selected.value)
            selected.value =
                rows.find((row) => row.id === selected.value?.id) || null;
    },
);
const page = usePage();
watch(
    () => page.props.flash,
    (flash) => {
        const path = (flash as { uploadedImage?: string })?.uploadedImage;
        if (path) form.image = path;
    },
);
const upload = () =>
    uploadForm.post('/admin/media', {
        forceFormData: true,
        onSuccess: () => uploadForm.reset(),
    });
watch(selected, (value) => {
    form.clearErrors();
    Object.assign(form, value ? { ...blank(), ...value } : blank());
});
const save = () => {
    if (selected.value)
        form.put(`/admin/content/${props.type}/${selected.value.id}`, {
            onSuccess: () => (selected.value = null),
        });
    else
        form.post(`/admin/content/${props.type}`, {
            onSuccess: () => form.reset(),
        });
};
const remove = (row: Row) => {
    if (confirm(`Delete ${row.slug}?`))
        router.delete(`/admin/content/${props.type}/${row.id}`);
};
</script>
<template>
    <Head :title="`Edit ${type}`" />
    <div class="admin-page">
        <div class="section-heading">
            <h1>{{ type }}</h1>
            <button
                class="button"
                type="button"
                @click="
                    selected = null;
                    form.reset();
                "
            >
                New
            </button>
        </div>
        <div class="admin-grid">
            <div>
                <p v-if="!rows.data.length">No entries yet.</p>
                <table v-else>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in rows.data" :key="row.id">
                            <td>
                                {{ row.title_en || row.slug
                                }}<small>{{ row.title_ar }}</small>
                            </td>
                            <td>
                                {{ row.is_published ? 'Published' : 'Draft' }}
                            </td>
                            <td>
                                <button type="button" @click="selected = row">
                                    Edit</button
                                ><button
                                    v-if="type === 'projects'"
                                    type="button"
                                    @click="openGallery(row)"
                                >
                                    Gallery ({{
                                        row.images?.length || 0
                                    }})</button
                                ><button type="button" @click="remove(row)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <nav class="pagination">
                    <Link
                        v-for="(link, i) in rows.links"
                        :key="i"
                        v-show="link.url"
                        :href="link.url || ''"
                        >{{
                            i === 0
                                ? '‹'
                                : i === rows.links.length - 1
                                  ? '›'
                                  : link.label
                        }}</Link
                    >
                </nav>
            </div>
            <div>
                <form class="editor-form" @submit.prevent="save">
                    <h2>{{ selected ? 'Edit entry' : 'New entry' }}</h2>
                    <label
                        >Key / slug<input
                            v-model="form.slug"
                            required
                            pattern="[A-Za-z0-9_-]+" /></label
                    ><label
                        >Arabic title<input
                            v-model="form.title_ar"
                            dir="rtl" /></label
                    ><label
                        >English title<input v-model="form.title_en" /></label
                    ><label v-if="type !== 'statistics'"
                        >Arabic body<textarea
                            v-model="form.body_ar"
                            dir="rtl"
                            rows="5"
                        /></label
                    ><label v-if="type !== 'statistics'"
                        >English body<textarea
                            v-model="form.body_en"
                            rows="5"
                        /></label
                    ><label v-if="type !== 'statistics'"
                        >Image path<input
                            v-model="form.image"
                            placeholder="/media/..." /></label
                    ><label
                        >Order<input
                            v-model.number="form.position"
                            type="number"
                            min="0" /></label
                    ><label v-if="type === 'statistics'"
                        >Value<input
                            v-model.number="form.value"
                            type="number"
                            min="0"
                            required /></label
                    ><label v-if="type === 'projects'"
                        >Category<select v-model="form.project_category_id">
                            <option :value="null">None</option>
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.title_en }}
                            </option>
                        </select></label
                    ><template v-if="type === 'locations'"
                        ><label
                            >Email<input
                                v-model="form.email"
                                type="email" /></label
                        ><label>Phone<input v-model="form.phone" /></label
                        ><label
                            >Fax<input v-model="form.fax" /></label></template
                    ><label class="checkbox"
                        ><input v-model="form.is_published" type="checkbox" />
                        Published</label
                    >
                    <p
                        v-for="(error, key) in form.errors"
                        :key="key"
                        role="alert"
                        class="error"
                    >
                        {{ key }}: {{ error }}
                    </p>
                    <button
                        class="button"
                        type="submit"
                        :disabled="form.processing"
                    >
                        Save
                    </button>
                </form>
                <section
                    v-if="type === 'projects' && selected"
                    ref="gallerySection"
                    class="editor-form gallery-editor"
                >
                    <p class="eyebrow">PROJECT MEDIA</p>
                    <h2>
                        Gallery for {{ selected.title_en || selected.slug }}
                    </h2>
                    <p>
                        The featured image stays on project cards. Gallery
                        images appear only on the project detail page.
                    </p>
                    <div class="featured-preview" v-if="selected.image">
                        <img
                            :src="selected.image"
                            alt="Featured project image"
                        /><span>Featured image</span>
                    </div>
                    <div
                        v-if="selected.images?.length"
                        class="gallery-editor-grid"
                    >
                        <div
                            v-for="image in selected.images"
                            :key="image.id"
                            class="gallery-editor-item"
                        >
                            <img
                                :src="image.path"
                                alt="Project gallery image"
                            />
                            <button
                                type="button"
                                @click="removeGallery(image.id)"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                    <p v-else class="gallery-empty">
                        No gallery images yet. Add images below.
                    </p>
                    <form class="gallery-upload" @submit.prevent="addGallery">
                        <label for="gallery-files"
                            >Add gallery images (up to 12 at once)</label
                        >
                        <input
                            id="gallery-files"
                            ref="galleryInput"
                            type="file"
                            multiple
                            accept="image/png,image/jpeg,image/webp"
                            @change="
                                galleryForm.images = Array.from(
                                    ($event.target as HTMLInputElement).files ||
                                        [],
                                )
                            "
                        />
                        <p v-if="galleryForm.images.length">
                            {{ galleryForm.images.length }} image{{
                                galleryForm.images.length === 1 ? '' : 's'
                            }}
                            selected
                        </p>
                        <p
                            v-for="(error, key) in galleryForm.errors"
                            :key="key"
                            role="alert"
                            class="error"
                        >
                            {{ error }}
                        </p>
                        <button
                            class="button"
                            :disabled="
                                !galleryForm.images.length ||
                                galleryForm.processing
                            "
                        >
                            {{
                                galleryForm.processing
                                    ? 'Uploading…'
                                    : 'Upload to gallery'
                            }}
                        </button>
                    </form>
                </section>
                <form
                    class="editor-form"
                    style="margin-top: 20px"
                    @submit.prevent="upload"
                >
                    <h2>Upload image</h2>
                    <input
                        type="file"
                        accept="image/png,image/jpeg,image/webp"
                        @change="
                            uploadForm.image =
                                ($event.target as HTMLInputElement)
                                    .files?.[0] || null
                        "
                    />
                    <p v-if="uploadForm.errors.image" role="alert">
                        {{ uploadForm.errors.image }}
                    </p>
                    <button
                        class="button"
                        :disabled="uploadForm.processing || !uploadForm.image"
                    >
                        Upload and use path
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
