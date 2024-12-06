<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia'; // Inertiaをインポート
import { useForm } from '@inertiajs/inertia-vue3'; // useFormをインポート
import { usePage } from '@inertiajs/vue3'; // usePageをインポート
import NotificationToast from '@/Components/NotificationToast.vue'; // コンポーネントをインポート

const appUrl = import.meta.env.VITE_APP_URL || 'http://localhost';

const props = defineProps({
    event: Object, // 親から渡されるeventプロパティを定義
});

const { props: pageProps } = usePage(); // usePageを使用してpropsを取得
const flashMessages = {
    successMessage: pageProps.flash.successMessage || '',
    errorMessage: pageProps.flash.errorMessage || '',
}; // フラッシュメッセージをオブジェクトにまとめる

const showDeleteDialog = ref(false); // ダイアログの表示状態を管理
const showQuestionModal = ref(false); // 問題追加/編集モーダルの表示状態を管理
const isEditMode = ref(false); // 編集モードかどうかを管理
const editingQuestion = ref(null); // 編集中の問題を保持
const questionToDelete = ref(null); // 削除対象の問題を保持

const confirmDelete = () => {
    showDeleteDialog.value = true; // ダイアログを表示
};

const confirmDeleteQuestion = (question) => {
    questionToDelete.value = question; // 削除対象の問題を設定
    showDeleteDialog.value = true; // ダイアログを表示
};

const cancelDelete = () => {
    showDeleteDialog.value = false; // ダイアログを非表示
    questionToDelete.value = null; // 削除対象をリセット
};

const submitDelete = () => {
    if (questionToDelete.value) {
        // 削除APIを実行
        router.delete(route('questions.destroy', { question_id: questionToDelete.value.id }), {
            onSuccess: () => {
                showDeleteDialog.value = false; // ダイアログを非表示
                questionToDelete.value = null; // 削除対象をリセット
                // ここでリストを更新するための処理を追加することができます
            },
            onError: (errors) => {
                console.error('削除に失敗しました。', errors); // エラーハンドリング
            }
        });
    }
};

const form = useForm({
    event_id: props.event.uuid,
    text: '', // 問題文
    choices: [{ text: '', is_correct: false }], // 選択肢の配列
});

const openQuestionModal = (question = null) => {
    if (question && question.text) {
        isEditMode.value = true; // 編集モード
        editingQuestion.value = question; // 編集する問題を設定
        form.text = question.text; // 問題文を設定
        form.choices = question.choices ? question.choices.map(choice => ({ text: choice.text, is_correct: choice.is_correct })) : [{ text: '', is_correct: false }]; // 選択肢を設定
    } else {
        isEditMode.value = false; // 追加モード
        form.text = ''; // 問題文をリセット
        form.choices = [{ text: '', is_correct: false }]; // 選択肢をリセット
    }
    showQuestionModal.value = true; // モーダルを表示
};

const closeQuestionModal = () => {
    showQuestionModal.value = false; // モーダルを非表示
    editingQuestion.value = null; // 編集中の問題をリセット
};

const addChoice = () => {
    if (form.choices.length < 5) { // 最大5択まで制限
        form.choices.push({ text: '', is_correct: false }); // 新しい選択肢を追加
    } else {
        alert('選択肢は最大5つまでです。'); // 制限を超えた場合の警告
    }
};

const removeChoice = (index) => {
    form.choices.splice(index, 1); // 指定したインデックスの選択肢を削除
};

const submitForm = () => {
    // バリデーション
    if (form.text === '') {
        alert('問題文を入力してください。'); // 問題文が空の場合の警告
        return;
    }
    if (form.choices.some(choice => choice.text === '')) {
        alert('選択肢を入力してください。'); // 選択肢が空の場合の警告
        return;
    }
    if (form.choices.length < 2) {
        alert('選択肢は2つ以上必要です。'); // 選択肢が2つ未満の場合の警告
        return;
    }

    const hasCorrectChoice = form.choices.some(choice => choice.is_correct);
    if (!hasCorrectChoice) {
        alert('少なくとも1つの選択肢は正解である必要があります。'); // 正解がない場合の警告
        return;
    }

    if (isEditMode.value) {
        // 編集の場合
        form.put(route('questions.update', { question_id: editingQuestion.value.id }), {
            onSuccess: () => {
                closeQuestionModal(); // モーダルを閉じる
            },
            onError: (errors) => {
                console.error('更新に失敗しました。', errors); // エラーハンドリング
            }
        });
    } else {
        // 追加の場合
        form.post(route('questions.store'), {
            onSuccess: () => {
                closeQuestionModal(); // モーダルを閉じる
            },
            onError: (errors) => {
                console.error('登録に失敗しました。', errors); // エラーハンドリング
            }
        });
    }
};

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text).then(() => {
        alert('クリップボードにコピーしました！'); // コピー成功時のメッセージ
    }).catch(err => {
        console.error('コピーに失敗しました:', err); // エラーハンドリング
    });
};

</script>

<template>
    <Head title="イベント詳細" />
    <AuthenticatedLayout>
        <NotificationToast :successMessage="flashMessages.successMessage" :errorMessage="flashMessages.errorMessage" />
        <div class="container mx-auto">
            <div class="flex justify-between items-center m-4">
                <h1 class="text-2xl font-bold">イベント詳細</h1>
                <div class="mt-4 flex justify-end space-x-2">
                    <Link :href="`/events`" class="btn btn-neutral mr-2">イベント一覧</Link>
                    <button class="btn btn-error mr-2" @click="confirmDelete">このイベントを削除</button>
                    <Link :href="`/events/${event.uuid}/edit`" class="btn btn-primary">このイベントを編集</Link>
                </div>
            </div>
            <div class="bg-base-100 p-4 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold">イベント名: {{ event.name }}</h2>
                <p class="text-gray-500 cursor-pointer" @click="copyToClipboard(appUrl + `/${event.uuid}`)">参加者向けURLをコピー📋</p>
            </div>
            <div class="flex justify-center" v-if="event.questions.length === 0">
                <p class="text-gray-500">問題が登録されていません。</p>
            </div>
            <div v-else class="mt-4">
                <div v-for="question in event.questions" :key="question.id" class="card bg-base-300 mb-4">
                    <div class="card-body">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold">{{ question.text }}</h3>
                            <div class="flex space-x-2">
                                <button class="btn btn-error" @click="confirmDeleteQuestion(question)">削除</button>
                                <button class="btn btn-primary" @click="openQuestionModal(question)">編集</button>
                            </div>
                        </div>
                        <div v-for="choice in question.choices" :key="choice.id" class="ml-4">
                            <p class="text-gray-700">{{ choice.text }}</p>
                            <p class="text-gray-500">{{ choice.is_correct ? 'O' : 'X' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 削除確認モーダル -->
            <div v-if="showDeleteDialog" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="modal modal-open">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg">削除確認</h3>
                        <p class="py-4">本当に「{{ questionToDelete.text }}」を削除しますか？</p>
                        <div class="modal-action">
                            <button @click="cancelDelete" class="btn btn-neutral btn-outline">キャンセル</button>
                            <button @click="submitDelete" class="btn btn-error">OK</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- フローティングボタン -->
            <div class="fixed bottom-4 right-4 flex flex-col space-y-2">
                <button class="btn btn-success btn-lg rounded-full" @click="openQuestionModal">
                    問題を追加する
                </button>
                <Link :href="`/quizMaster/${event.uuid}`" class="btn btn-primary btn-lg rounded-full">
                    クイズを開始する
                </Link>
            </div>
            <!-- 問題追加/編集モーダル -->
            <div v-if="showQuestionModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="modal modal-open">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg">{{ isEditMode ? '問題を編集' : '問題を追加' }}</h3>
                        <form @submit.prevent="submitForm">
                            <div class="form-control mb-4">
                                <label class="label">
                                    <span class="label-text">問題文</span>
                                </label>
                                <textarea v-model="form.text" class="textarea textarea-bordered" placeholder="問題文を入力してください"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="label">
                                    <span class="label-text">選択肢</span>
                                </label>
                                <div class="grid grid-cols-3 gap-4 mb-2">
                                    <div class="font-bold">選択肢</div>
                                    <div class="font-bold">正解</div>
                                    <div></div> <!-- 空のセル（削除ボタン用） -->
                                </div>
                                <div v-for="(choice, index) in form.choices" :key="index" class="grid grid-cols-3 gap-4 items-center mb-2">
                                    <input type="text" v-model="choice.text" class="input input-bordered w-full" placeholder="選択肢を入力" />
                                    <input type="checkbox" v-model="choice.is_correct" class="checkbox" />
                                    <button type="button" class="btn btn-error btn-sm w-24" @click="removeChoice(index)">削除</button>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm" @click="addChoice">選択肢を追加</button> <!-- 選択肢追加ボタン -->
                            </div>
                            <div class="modal-action">
                                <button type="button" class="btn btn-neutral" @click="closeQuestionModal">キャンセル</button>
                                <button type="submit" class="btn btn-primary">{{ isEditMode ? '更新' : '登録' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
