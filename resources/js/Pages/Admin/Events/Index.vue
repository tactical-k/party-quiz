<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
const { props } = usePage();
const events = props.events;
import { ref, computed } from 'vue';
const successMessage = props.flash.successMessage || '';
const errorMessage = props.flash.errorMessage || '';
</script>

<template>
    <Head title="イベント一覧" />
    <AuthenticatedLayout>
      <div class="container mx-auto">
        <div class="toast toast-top toast-end">
          <div v-if="successMessage" class="alert alert-success mb-4">
            {{ successMessage }}
          </div>
          <div v-if="errorMessage" class="alert alert-error mb-4">
            {{ errorMessage }}
          </div>
        </div>
        <div class="flex justify-between items-center m-4">
          <h1 class="text-2xl font-bold">イベント一覧</h1>
          <Link :href="'/events/create'" class="btn btn-primary">イベントを作成</Link>
        </div>
          <div v-if="events.length === 0" class="text-center py-4">
              <p class="text-gray-500">イベントが設定されていません。</p>
          </div>
          <table v-else class="table table-zebra w-full bg-white dark:bg-gray-800 border border-gray-300 rounded-lg">
              <thead>
                  <tr>
                      <th class="text-left">イベント名</th>
                      <th class="text-left">ユーザー名</th>
                      <th class="text-left">アクション</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="event in events" :key="event.uuid">
                      <td>{{ event.name }}</td>
                      <td>{{ event.user.name }}</td>
                      <td>
                          <Link :href="`/events/${event.uuid}`" class="btn btn-primary">詳細</Link>
                      </td>
                  </tr>
              </tbody>
          </table>
        </div>
    </AuthenticatedLayout>
</template>
