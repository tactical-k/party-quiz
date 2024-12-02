<script setup>
defineProps({
    value: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>

<style scoped>
/* 必要に応じて追加のスタイルをここに記述 */
</style>
