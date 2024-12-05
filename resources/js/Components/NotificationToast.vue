<template>
  <div class="toast toast-top toast-end">
    <div v-if="successMessage" class="alert alert-success mb-4">
      {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="alert alert-error mb-4">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script setup>
defineProps(['successMessage', 'errorMessage']); // プロパティを定義
</script>