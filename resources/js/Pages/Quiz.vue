<script setup>
import { ref, onMounted } from 'vue';
import { database } from '../firebase'; // Firebaseの初期化スクリプト
import { ref as dbRef, onValue, push } from 'firebase/database';
import RespondentLayout from '@/Layouts/RespondentLayout.vue';
import axios from 'axios';

const name = ref(localStorage.getItem('participantName') || '匿名');
const question = ref(null);
const answer = ref('');//回答Post用
const previousAnswer = ref('');// 答え合わせ用

const props = defineProps({
    event_id: String,
});

// 質問をリアルタイムで取得
onMounted(() => {
    // リロードされても前回の回答が残るようにローカルストレージに保存しておく
    previousAnswer.value = localStorage.getItem('previous_answer') || '';
    // 問題の取得元はFirebase
    const questionRef = dbRef(database, `${props.event_id}/currentDisplay`);
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
        const response = await axios.post(`/events/${props.event_id}/quiz`, {
            answer: answer.value,
            event_id: props.event_id,
            question_id: question.value.question_id,
            name: name.value,
        });

        // ステータスに応じた処理
        if (response.data.status === 'success') {
            alert('回答が正常に送信されました');
            previousAnswer.value = answer.value;
            // リロードされても前回の回答が残るようにローカルストレージに保存しておく
            localStorage.setItem('previous_answer', answer.value);
            question.value = null; // 質問をリセット
        } else if (response.data.status === 'already_answered') {
            alert('すでに回答済みです。次の問題までお待ちください。');
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
            <!-- 質問の場合 -->
            <div v-if="question.type === 'question'">
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
            <!-- 回答の場合 -->
            <div v-else class="quiz-container bg-base-100 p-4 rounded-lg">
                <div class="text-lg text-center">正解は...</div>
                <div class="text-2xl font-bold mb-4 text-center">{{ question.text }}</div>
                <div class="text-lg text-center">でした。</div>
                <div class="result-message text-center mt-4">
                    <span class="result-text badge" :class="{
                        'badge-success': question.text === previousAnswer,
                        'badge-error': question.text !== previousAnswer
                    }">
                        {{ question.text === previousAnswer ? '正解です！' : '不正解です。' }}
                    </span>
                    <p class="mt-2">あなたの回答: <strong>{{ previousAnswer }}</strong></p>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="text-center">そのままお待ちください...</div>
            <div class="loading loading-spinner loading-lg mx-auto mt-4 justify-center"></div>
        </div>
    </RespondentLayout>
</template>