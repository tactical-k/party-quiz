<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

// Chart.jsのコンポーネントを登録
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const { props } = usePage();
const summary = props.summary;

// グラフデータの準備
const labels = Array.isArray(summary) ? summary.map(item => item.name) : [];
const data = Array.isArray(summary) ? summary.map(item => item.correct_count) : [];
// ランキングのスコアを取得(重複を削除して降順にソート index+1で順位-valueで得点)
const ranking_score = Array.from(new Set(data)).sort((a, b) => b - a);

const chartData = {
    labels: labels,
    datasets: [
        {
            label: '正解数',
            data: data,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
        },
    ],
};

// Chart options
const chartOptions = {
    responsive: true,
    maintainAspectRatio: true,
    indexAxis: 'y',
};
</script>

<template>
    <Head title="回答集計" />
    <AuthenticatedLayout>
        <div class="container mx-auto">
          <div class="flex justify-between items-center m-4">
            <h1 class="text-2xl font-bold mb-4">回答集計</h1>
          </div>
            <Bar
                id="my-chart-id"
                :data="chartData"
                :options="chartOptions"
            />
            <div class="ranking-list mt-6 overflow-x-auto">
                <h2 class="text-xl font-semibold mb-2">ランキング</h2>
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th class="border-b-2 border-gray-300 px-4 py-2 text-left">順位</th>
                            <th class="border-b-2 border-gray-300 px-4 py-2 text-left">名前</th>
                            <th class="border-b-2 border-gray-300 px-4 py-2 text-left">得点</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in summary" :key="index" class="">
                            <td class="border-b border-gray-200 px-4 py-2">{{ ranking_score.findIndex(score => score === item.correct_count) + 1 }}位</td>
                            <td class="border-b border-gray-200 px-4 py-2">{{ item.name }}</td>
                            <td class="border-b border-gray-200 px-4 py-2">{{ item.correct_count }}点</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.container {
    height: 400px; /* グラフの高さを指定 */
}

</style>
