<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import RespondentLayout from '@/Layouts/RespondentLayout.vue';

const props = defineProps({
    event_id: String,
    event_name: String,
});

const name = ref('');

const submit = () => {
    if (name.value.trim() === '') return alert('名前を入力してください');
    localStorage.setItem('participantName', name.value);
    router.visit(`/events/${props.event_id}/quiz`, { data: { name: name.value } });
};
</script>

<template>
    <RespondentLayout :event_name="event_name">
        <h2 class="text-gray-800 dark:text-white">名前を入力してクイズに参加しよう！</h2>
        <form @submit.prevent="submit" class="mt-4">
            <input v-model="name" type="text" placeholder="名前" required class="input input-bordered w-full mb-4" />
            <button type="submit" class="btn btn-primary w-full">参加</button>
        </form>
    </RespondentLayout>
</template>
