<template>
  <div class="flex flex-col min-h-screen bg-gray-100 dark:bg-gray-900">
      <header class="bg-white dark:bg-gray-800 shadow">
          <div class="max-w-2xl mx-auto py-4 px-4">
              <h1 class="text-2xl font-bold text-center text-gray-800 dark:text-white">Quiz Layout Header</h1>
          </div>
      </header>
      <main class="flex-grow max-w-2xl mx-auto p-4">
          <slot></slot>
      </main>
      <footer class="bg-white dark:bg-gray-800 shadow">
          <div class="max-w-2xl mx-auto py-4 px-4 text-center">
              <p class="text-gray-600 dark:text-gray-400">Quiz Layout Footer</p>
          </div>
      </footer>
  </div>
</template>

<script setup>
</script>

<style scoped>
/* スマートフォン向けのスタイルを追加する場合はここに記述 */
</style>