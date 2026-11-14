<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
type Message = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    subject: string;
    message: string;
    created_at: string;
    read_at: string | null;
};
defineProps<{ message: Message }>();
const mark = (id: number, read: boolean) =>
    router.patch(`/admin/inbox/${id}`, { read });
</script>
<template>
    <Head :title="message.subject" />
    <div class="admin-page message-detail">
        <Link href="/admin/inbox">← Inbox</Link>
        <h1>{{ message.subject }}</h1>
        <p>
            <strong>{{ message.name }}</strong> ·
            <a :href="`mailto:${message.email}`">{{ message.email }}</a> ·
            {{ message.phone }}
        </p>
        <time>{{ new Date(message.created_at).toLocaleString() }}</time>
        <p class="message-body">{{ message.message }}</p>
        <button
            class="button"
            type="button"
            @click="mark(message.id, !message.read_at)"
        >
            {{ message.read_at ? 'Mark unread' : 'Mark read' }}
        </button>
    </div>
</template>
