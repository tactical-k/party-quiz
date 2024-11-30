<script>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

export default {
    name: 'Welcome',
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
    <div>
        <h1>参加するには名前を入力してください</h1>
        <form @submit.prevent="submit">
            <input v-model="name" type="text" placeholder="名前" required />
            <button type="submit">参加</button>
        </form>
    </div>
</template>
