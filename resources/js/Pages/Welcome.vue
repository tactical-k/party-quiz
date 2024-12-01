<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Welcome" />   
    <div class="flex items-center justify-center h-screen bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div class="text-center">
            <Link
                v-if="canLogin"
                :href="route('login')"
                class="btn btn-primary mb-4"
            >
                ログイン
            </Link>

            <Link
                v-if="canRegister"
                :href="route('register')"
                class="btn btn-accent"
            >
                会員登録
            </Link>
        </div>
    </div>
</template>
