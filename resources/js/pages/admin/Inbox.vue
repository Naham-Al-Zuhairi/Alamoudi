<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
type Message = {
    id: number;
    name: string;
    email: string;
    subject: string;
    created_at: string;
    read_at: string | null;
};
defineProps<{
    messages: {
        data: Message[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    unread: number;
    filter?: string;
}>();
</script>
<template>
    <Head title="Inbox" />
    <div class="admin-page">
        <h1>
            Inbox <small>{{ unread }} unread</small>
        </h1>
        <nav class="filters">
            <Link href="/admin/inbox">All</Link
            ><Link href="/admin/inbox?filter=unread">Unread</Link
            ><Link href="/admin/inbox?filter=read">Read</Link>
        </nav>
        <p v-if="!messages.data.length">No messages in this view.</p>
        <table v-else>
            <thead>
                <tr>
                    <th>Sender</th>
                    <th>Subject</th>
                    <th>Received</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="message in messages.data" :key="message.id">
                    <td>
                        {{ message.name }}<small>{{ message.email }}</small>
                    </td>
                    <td>
                        <Link :href="`/admin/inbox/${message.id}`">{{
                            message.subject
                        }}</Link>
                    </td>
                    <td>{{ new Date(message.created_at).toLocaleString() }}</td>
                    <td>{{ message.read_at ? 'Read' : 'Unread' }}</td>
                </tr>
            </tbody>
        </table>
        <nav class="pagination">
            <Link
                v-for="(link, i) in messages.links"
                :key="i"
                v-show="link.url"
                :href="link.url || ''"
                >{{
                    i === 0
                        ? '‹'
                        : i === messages.links.length - 1
                          ? '›'
                          : link.label
                }}</Link
            >
        </nav>
    </div>
</template>
