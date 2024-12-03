<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const props = defineProps({
    event: Object, // 親から渡されるeventプロパティを定義
});

const showDialog = ref(false); // ダイアログの表示状態を管理

const confirmDelete = () => {
    showDialog.value = true; // ダイアログを表示
};

const submitDelete = () => {
    // 削除処理を実行
    router.delete(route('events.destroy', { event: props.event.uuid }));
};

const cancelDelete = () => {
    showDialog.value = false; // ダイアログを非表示
};
</script>

<template>
    <Head title="イベント詳細" />
    <AuthenticatedLayout>
        <div class="container mx-auto">
            <div class="flex justify-between items-center m-4">
                <h1 class="text-2xl font-bold">イベント詳細</h1>
                <div class="mt-4 flex justify-end">
                    <Link :href="`/events`" class="btn btn-neutral mr-2">一覧</Link>
                    <button class="btn btn-error mr-2" @click="confirmDelete">削除</button>
                    <Link :href="`/events/${event.uuid}/edit`" class="btn btn-primary">編集</Link>
                </div>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-semibold">イベント名: {{ event.name }}</h2>
            </div>
            <!-- 削除確認モーダル -->
            <div v-if="showDialog" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="modal modal-open">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg">削除確認</h3>
                        <p class="py-4">本当に「{{ event.name }}」を削除しますか？</p>
                        <div class="modal-action">
                            <button @click="cancelDelete" class="btn btn-neutral btn-outline">キャンセル</button>
                            <button @click="submitDelete" class="btn btn-error">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
