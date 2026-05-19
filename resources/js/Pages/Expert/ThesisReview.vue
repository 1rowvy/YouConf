<template>
    <div class="flex flex-col gap-8 max-w-3xl">
        <Link
            href="/expert/theses"
            class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-black transition-all group w-fit"
        >
            <span class="mr-2 transform group-hover:-translate-x-1 transition-all">&larr;</span>
            Назад к тезисам
        </Link>

        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            <div class="mb-6">
                <span
                    :class="statusClasses[thesis.status_id]"
                    class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase"
                >
                    {{ thesis.status.name }}
                </span>
            </div>

            <h1 class="text-2xl font-extrabold text-gray-900 mb-2">{{ thesis.title }}</h1>

            <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-6">
                <span>
                    <span class="font-semibold text-gray-700">Автор:</span>
                    {{ thesis.user.last_name }} {{ thesis.user.first_name }}
                    <span v-if="thesis.user.patronymic">{{ thesis.user.patronymic }}</span>
                </span>
                <span>
                    <span class="font-semibold text-gray-700">Секция:</span>
                    {{ thesis.section.name }}
                </span>
                <span>
                    <span class="font-semibold text-gray-700">Правки:</span>
                    {{ thesis.revision_count }} / {{ thesis.section.revision_limit }}
                </span>
            </div>

            <div class="bg-gray-50 rounded-xl p-5 mb-8">
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ thesis.description }}</p>
            </div>

            <div v-if="mediaFiles && mediaFiles.length" class="mb-8">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Файлы</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div
                        v-for="file in mediaFiles"
                        :key="file.id"
                        class="group bg-gray-50 rounded-xl p-3 cursor-pointer hover:bg-gray-100 transition-all duration-200"
                        @click="downloadFile(file)"
                    >
                        <div v-if="isImage(file)" class="aspect-square rounded-lg overflow-hidden mb-2">
                            <img :src="file.original_url" alt="" class="w-full h-full object-cover" />
                        </div>
                        <div v-else class="aspect-square rounded-lg bg-gray-100 flex items-center justify-center mb-2">
                            <span class="text-2xl" v-html="getFileIcon(file)"></span>
                        </div>
                        <p class="text-xs font-medium text-gray-500 truncate">{{ file.name }}</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submitReview">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Статус</label>
                        <select
                            v-model="form.status_id"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                        >
                            <option
                                v-for="status in statuses"
                                :key="status.id"
                                :value="status.id"
                            >
                                {{ status.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Рецензия
                            <span class="font-normal text-gray-400">(необязательно)</span>
                        </label>
                        <textarea
                            v-model="form.review_comment"
                            rows="6"
                            placeholder="Напишите комментарий или замечания к тезису..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-900 placeholder-gray-400 resize-none focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                        ></textarea>
                        <p class="text-xs text-gray-400 mt-1 text-right">{{ form.review_comment.length }} / 5000</p>
                    </div>

                    <div v-if="errorMessage" class="px-4 py-3 bg-red-50 border border-red-100 rounded-xl text-sm text-red-600">
                        {{ errorMessage }}
                    </div>

                    <div class="flex justify-end pt-2">
                        <button
                            type="submit"
                            :disabled="submitting"
                            class="px-8 py-3 bg-black text-white rounded-full font-bold text-sm hover:bg-gray-800 transition-all shadow-lg shadow-black/10 disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            {{ submitting ? 'Сохранение...' : 'Сохранить рецензию' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/inertia-vue3'
import ExpertLayout from '../../Common/ExpertLayout.vue'

defineOptions({
    meta: {
        layout: ExpertLayout,
    },
})

const props = defineProps({
    thesis: {
        type: Object,
        required: true,
    },
    statuses: {
        type: Array,
        default: () => [],
    },
    mediaFiles: {
        type: Array,
        default: () => [],
    },
})

const submitting = ref(false)
const errorMessage = ref('')

const form = useForm({
    status_id: props.thesis.status_id,
    review_comment: props.thesis.review_comment ?? '',
    redirect_to: '/expert/theses',
})

const statusClasses = {
    1: 'bg-orange-50 text-orange-600',
    2: 'bg-emerald-50 text-emerald-700',
    3: 'bg-blue-50 text-blue-600',
    4: 'bg-red-50 text-red-500',
}

function isImage(file) {
    return file.mime_type?.startsWith('image/')
}

function getFileIcon(file) {
    const ext = file.file_name?.split('.').pop()
    return ({ pdf: '📄', doc: '📝', docx: '📝' }[ext] || '📁')
}

function downloadFile(file) {
    const link = document.createElement('a')
    link.href = file.original_url
    link.download = file.name
    link.target = '_blank'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
}

function submitReview() {
    errorMessage.value = ''
    submitting.value = true
    form.post(`/theses/${props.thesis.id}/status`, {
        onSuccess: () => {
            submitting.value = false
        },
        onError: (errors) => {
            submitting.value = false
            errorMessage.value = errors.message || 'Произошла ошибка при сохранении.'
        },
        onFinish: () => {
            submitting.value = false
        },
    })
}
</script>
