<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    event: Object, // 親から渡されるeventプロパティを定義
});
</script>

<template>
    <Head title="イベント詳細" />
    <AuthenticatedLayout>
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold mb-4">イベント詳細</h1>
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-semibold">イベント名: {{ event.name }}</h2>
                <p class="mt-2"><strong>ユーザーID:</strong> {{ event.user_id }}</p>
                <p class="mt-2"><strong>作成日時:</strong> {{ new Date(event.created_at).toLocaleString() }}</p>
                <p class="mt-2"><strong>更新日時:</strong> {{ new Date(event.updated_at).toLocaleString() }}</p>
                <div class="mt-4">
                    <inertia-link :href="`/events/${event.id}/edit`" class="text-blue-500">編集</inertia-link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
