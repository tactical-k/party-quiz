<script>
import { ref, onMounted } from 'vue';
import { database } from '../firebase'; // Firebaseの初期化スクリプト
import { ref as dbRef, onValue, push } from 'firebase/database';

export default {
    name: 'Quiz',
    setup() {
        const name = ref(localStorage.getItem('participantName') || '匿名');
        const question = ref(null);
        const answer = ref('');

        // 質問をリアルタイムで取得
        onMounted(() => {
            const questionRef = dbRef(database, 'currentQuestion');
            console.log(questionRef);
            onValue(questionRef, (snapshot) => {
                const data = snapshot.val();
                if (data) {
                    question.value = data;
                }
            });
        });

        // 回答を送信
        const submitAnswer = () => {
            const answersRef = dbRef(database, 'answers');
            push(answersRef, {
                name: name.value,
                answer: answer.value,
            });
            answer.value = '';
        };

        return { name, question, answer, submitAnswer };
    },
};
</script>

<template>
    <div>
        <h1>ようこそ {{ name }} さん</h1>
        <div v-if="question">
            <h2>{{ question.text }}</h2>
            <div>
                <input v-model="answer" type="text" placeholder="回答を入力" />
                <button @click="submitAnswer">送信</button>
            </div>
        </div>
        <p v-else>クイズが始まるのを待っています...</p>
    </div>
</template>
