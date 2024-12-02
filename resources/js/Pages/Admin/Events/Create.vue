<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const form = useForm({
    name: '', // イベント名
});

const successMessage = ref(''); // 成功メッセージ
const errorMessage = ref(''); // エラーメッセージ

const submit = () => {
    form.post('/events', {
        onSuccess: () => {
            successMessage.value = '登録に成功しました'; // 成功メッセージを設定
        },
        onError: () => {
            errorMessage.value = '登録に失敗しました'; // エラーメッセージを設定
        },
    });
};
</script>

<template>
    <Head title="イベント作成" />
    <AuthenticatedLayout>
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold mb-4">新しいイベントを作成</h1>
            <div v-if="successMessage" class="alert alert-info mb-4">
                {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="alert alert-error mb-4">
                {{ errorMessage }}
            </div>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">イベント名</label>
                    <input v-model="form.name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">作成</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>