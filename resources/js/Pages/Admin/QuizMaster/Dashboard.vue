<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
const { props } = usePage();
const event = props.event;
const successMessage = props.flash.successMessage || '';
const errorMessage = props.flash.errorMessage || '';
const showSubmitQuestionModal = ref(false);
const showDisplayAnswerModal = ref(false);
const selectedQuestionId = ref(null);

const submitQuestion = (questionId) => {
    selectedQuestionId.value = questionId;
    showSubmitQuestionModal.value = true;
}

const displayAnswer = (questionId) => {
    selectedQuestionId.value = questionId;
    showDisplayAnswerModal.value = true;
}

const confirmSubmitAnswerSubmit = async () => {
    try {
        const response = await axios.post(`/submitQuestion/${selectedQuestionId.value}`);
        if (response.data.status === 'success') {
            alert('質問を出題しました。');
            // 質問のis_submittedをtrueにする
            event.questions.find(question => question.id === selectedQuestionId.value).is_submitted = true;
        } else {
            alert('質問の出題に失敗しました。');
        }
    } catch (error) {
        console.error('Error submitting question:', error);
    } finally {
        showSubmitQuestionModal.value = false;
    }
}

const confirmDisplayAnswer = async () => {
    try {
        const response = await axios.post(`/displayAnswer/${selectedQuestionId.value}`);
        if (response.data.status === 'success') {
            alert('回答を表示しました。');
        } else {
            alert('回答の表示に失敗しました。');
        }
    } catch (error) {
        console.error('Error displaying answer:', error);
    } finally {
        showDisplayAnswerModal.value = false;
    }
}

const showClearQuestionModal = ref(false);

const openClearQuestionModal = () => {
    showClearQuestionModal.value = true;
}

const confirmClearQuestion = async () => {
    try {
        const response = await axios.post(`/clearQuestion/${event.uuid}`);
        if (response.data.status === 'success') {
            alert('質問をクリアしました。');
            window.location.reload();
        } else {
            alert('質問のクリアに失敗しました。');
        }
    } catch (error) {
        console.error('Error clearing question:', error);
    } finally {
        showClearQuestionModal.value = false;
    }
}

const goToSummary = () => {
    console.log(event.uuid);
    router.visit(`/summary/${event.uuid}`);
}
</script>

<template>
    <Head title="クイズ主催画面" />
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
                <h1 class="text-2xl font-bold">{{ event.name }}</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="question in event.questions" :key="question.id">
                    <div class="card bg-base-100 w-full shadow-xl">
                        <div class="card-body">
                            <h2 class="card-title">{{ question.text }}</h2>
                            <div class="card-actions justify-end">
                                <button class="btn btn-primary" :disabled="question.is_submitted" @click="submitQuestion(question.id)">
                                    {{ question.is_submitted ? '出題済み' : '出題する' }}
                                </button>
                                <button class="btn btn-secondary" :disabled="!question.is_submitted" @click="displayAnswer(question.id)">
                                    回答を表示
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- フローティングボタン -->
            <div class="fixed bottom-4 right-4 flex flex-col space-y-2">
                <button class="btn btn-primary btn-lg rounded-full" @click="goToSummary">
                    回答集計
                </button>
                <button class="btn btn-error btn-lg rounded-full" @click="openClearQuestionModal">
                    クイズを最初からやり直す
                </button>
            </div>
            <div v-if="showSubmitQuestionModal" class="modal modal-open">
                <div class="modal-box">
                    <h2 class="font-bold">確認</h2>
                    <p>この質問を出題しますか？</p>
                    <div class="modal-action">
                        <button class="btn" @click="showSubmitQuestionModal = false">いいえ</button>
                        <button class="btn btn-primary" @click="confirmSubmitAnswerSubmit">はい</button>
                    </div>
                </div>
            </div>
            <div v-if="showDisplayAnswerModal" class="modal modal-open">
                <div class="modal-box">
                    <h2 class="font-bold">確認</h2>
                    <p>この質問の回答を表示しますか？</p>
                    <div class="modal-action">
                        <button class="btn" @click="showDisplayAnswerModal = false">いいえ</button>
                        <button class="btn btn-primary" @click="confirmDisplayAnswer">はい</button>
                    </div>
                </div>
            </div>
            <div v-if="showClearQuestionModal" class="modal modal-open">
                <div class="modal-box">
                    <h2 class="font-bold">確認</h2>
                    <p>このクイズを最初からやり直しますか？</p>
                    <p>※この操作は取り消すことができません。</p>
                    <div class="modal-action">
                        <button class="btn" @click="showClearQuestionModal = false">いいえ</button>
                        <button class="btn btn-error" @click="confirmClearQuestion">はい</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>