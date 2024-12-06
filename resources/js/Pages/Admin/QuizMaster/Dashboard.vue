<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
const { props } = usePage();
const event = props.event;
const successMessage = props.flash.successMessage || '';
const errorMessage = props.flash.errorMessage || '';

const showModal = ref(false);
const selectedQuestionId = ref(null);

const submitQuestion = (questionId) => {
    selectedQuestionId.value = questionId;
    showModal.value = true;
}

const confirmSubmit = async () => {
    try {
        const response = await axios.post(`/submitQuestion/${selectedQuestionId.value}`);
        console.log(response.data.status);
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
        showModal.value = false;
    }
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
            <div v-for="question in event.questions" :key="question.id">
              <div class="card bg-base-100 w-96 shadow-xl">
                  <div class="card-body">
                      <h2 class="card-title">{{ question.text }}</h2>
                      <div class="card-actions justify-end">
                          <button class="btn btn-primary" :disabled="question.is_submitted" @click="submitQuestion(question.id)">
                              {{ question.is_submitted ? '出題済み' : '出題する' }}
                          </button>
                      </div>
                  </div>
              </div>
            </div>
            <div v-if="showModal" class="modal modal-open">
                <div class="modal-box">
                    <h2 class="font-bold">確認</h2>
                    <p>この質問を出題しますか？</p>
                    <div class="modal-action">
                      <button class="btn" @click="showModal = false">いいえ</button>
                      <button class="btn btn-primary" @click="confirmSubmit">はい</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>