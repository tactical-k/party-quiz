<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-white"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg transition-transform transform hover:scale-105">
                        <Link :href="route('events.index')" class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="text-xl font-semibold m-2">イベント一覧</div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* 追加のスタイルをここに記述 */
</style>
