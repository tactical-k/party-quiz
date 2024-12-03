<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { defineProps } from 'vue';

const props = defineProps({
    event: Object, // 親から渡されるeventプロパティを定義
});

const form = useForm({
    name: props.event.name, // イベント名を初期値として設定
});

const submit = () => {
    form.put(route('events.update', { event: props.event.uuid }), {
        onSuccess: () => {
            // 成功時の処理
        },
        onError: () => {
            // エラー時の処理
        },
    });
};
</script>

<template>
    <Head title="イベント編集" />
    <AuthenticatedLayout>
        <div class="container mx-auto">
            <div class="flex justify-between items-center m-4">
                <h1 class="text-2xl font-bold">イベント編集</h1>
            </div>
            <form @submit.prevent="submit">
                <div class="mt-4 flex justify-end">
                    <Link :href="`/events/${event.uuid}`" class="btn btn-neutral mr-2">戻る</Link>
                    <button type="submit" class="btn btn-primary">更新</button>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">イベント名</label>
                    <input v-model="form.name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
