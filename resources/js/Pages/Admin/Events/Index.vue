<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const { props } = usePage();
const successMessage = props.flash.successMessage || '';
const errorMessage = props.flash.errorMessage || '';
const events = props.events;
console.log(successMessage);
</script>

<template>
    <Head title="イベント一覧" />
    <AuthenticatedLayout>
      <div class="container mx-auto">
          <div v-if="successMessage" class="alert alert-success mb-4">
            {{ successMessage }}
          </div>
          <div v-if="errorMessage" class="alert alert-error mb-4">
            {{ errorMessage }}
          </div>
          <div class="flex justify-between items-center m-4">
            <h1 class="text-2xl font-bold">イベント一覧</h1>
            <Link :href="'/events/create'" class="btn btn-primary">イベントを作成</Link>
          </div>
            <div v-if="events.length === 0" class="text-center py-4">
                <p class="text-gray-500">イベントが設定されていません。</p>
            </div>
            <table v-else class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">イベント名</th>
                        <th class="py-2 px-4 border-b">ユーザー名</th>
                        <th class="py-2 px-4 border-b">アクション</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="event in events" :key="event.id">
                        <td class="py-2 px-4 border-b">{{ event.name }}</td>
                        <td class="py-2 px-4 border-b">{{ event.user.name }}</td>
                        <td class="py-2 px-4 border-b">
                            詳細
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
