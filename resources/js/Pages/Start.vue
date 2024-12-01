<script>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import RespondentLayout from '@/Layouts/RespondentLayout.vue';

export default {
    name: 'Welcome',
    layout: RespondentLayout,
    setup() {
        const name = ref('');

        const submit = () => {
            if (name.value.trim() === '') return alert('名前を入力してください');
            localStorage.setItem('participantName', name.value);
            router.visit('/quiz', { data: { name: name.value } });
        };

        return { name, submit };
    },
};
</script>

<template>
    <RespondentLayout>
        <h2>名前を入力してクイズに参加しよう！</h2>
        <form @submit.prevent="submit">
            <input v-model="name" type="text" placeholder="名前" required class="input input-bordered w-full mb-4" />
            <button type="submit" class="btn btn-primary w-full">参加</button>
        </form>
    </RespondentLayout>
</template>
