<script setup>
import { ref, onMounted } from 'vue';
import { database } from '../firebase'; // Firebaseの初期化スクリプト
import { ref as dbRef, onValue, push } from 'firebase/database';
import RespondentLayout from '@/Layouts/RespondentLayout.vue';
import axios from 'axios';

const name = ref(localStorage.getItem('participantName') || '匿名');
const question = ref(null);
const answer = ref('');

const props = defineProps({
    event_id: String,
});

// 質問をリアルタイムで取得
onMounted(() => {
    // 問題の取得元はFirebase
    const questionRef = dbRef(database, `${props.event_id}/currentQuestion`);
    onValue(questionRef, (snapshot) => {
        const data = snapshot.val();
        if (data) {
            question.value = data;
        }
    });
});

// 回答を送信
const submitAnswer = async () => {
    try {
        console.log(answer.value);
        console.log(props.event_id);
        console.log(question.value.question_id);
        console.log(name.value);
        const response = await axios.post(`/events/${props.event_id}/quiz`, {
            answer: answer.value,
            event_id: props.event_id,
            question_id: question.value.question_id,
            name: name.value,
        });

        // ステータスに応じた処理
        if (response.data.status === 'success') {
            alert('回答が正常に送信されました');
            question.value = null; // 質問をリセット
        } else {
            alert('回答の送信に失敗しました。再試行してください。');
        }
    } catch (error) {
        console.error('Error response:', error.response);
        alert('エラーが発生しました。再試行してください。');
    }
};
</script>

<template>
    <RespondentLayout>
        <h1>ようこそ {{ name }} さん</h1>
        <div v-if="question">
            <h2 class="text-2xl font-bold mb-4">{{ question.text }}</h2>
            <div v-for="choice in question.choices" :key="choice">
                <div class="flex items-center mb-2">
                    <input type="radio" :id="choice" :value="choice" v-model="answer" class="mr-2 radio radio-primary" />
                    <label :for="choice" class="cursor-pointer">{{ choice }}</label>
                </div>
            </div>
            <div>
                <button @click="submitAnswer" class="btn btn-primary w-full">送信</button>
            </div>
        </div>
        <div v-else>
            <div class="text-center">クイズの出題を待っています...</div>
            <div class="loading loading-spinner loading-lg mx-auto mt-4 justify-center"></div>
        </div>
    </RespondentLayout>
</template>
