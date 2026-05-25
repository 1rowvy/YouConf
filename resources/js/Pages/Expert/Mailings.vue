<template>
    <div class="flex flex-col gap-8 max-w-2xl">
        <header>
            <p class="text-sm text-gray-400 mb-1">Рассылки</p>
            <h1 class="text-2xl font-bold text-gray-900">
                Отправка писем участникам
            </h1>
        </header>

        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            <form @submit.prevent="submit">
                <div class="space-y-6">
                    <div>
                        <p class="text-sm font-bold text-gray-700 mb-3">Кому</p>
                        <div class="space-y-2">
                            <label
                                class="flex items-center gap-3 cursor-pointer"
                            >
                                <input
                                    type="radio"
                                    value="all"
                                    v-model="form.type"
                                    class="w-4 h-4 accent-black"
                                />
                                <span class="text-sm text-gray-700"
                                    >Все участники конференции</span
                                >
                            </label>
                            <label
                                class="flex items-center gap-3 cursor-pointer"
                            >
                                <input
                                    type="radio"
                                    value="sections"
                                    v-model="form.type"
                                    class="w-4 h-4 accent-black"
                                />
                                <span class="text-sm text-gray-700"
                                    >Участники секций</span
                                >
                            </label>
                            <label
                                class="flex items-center gap-3 cursor-pointer"
                            >
                                <input
                                    type="radio"
                                    value="selected"
                                    v-model="form.type"
                                    class="w-4 h-4 accent-black"
                                />
                                <span class="text-sm text-gray-700"
                                    >Выбранные участники</span
                                >
                            </label>
                        </div>
                    </div>

                    <div v-if="form.type === 'sections'" class="pl-7">
                        <p
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2"
                        >
                            Выберите секции
                        </p>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                            <label
                                v-for="section in sections"
                                :key="section.id"
                                class="flex items-center gap-3 cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50"
                            >
                                <input
                                    type="checkbox"
                                    :value="section.id"
                                    v-model="form.section_ids"
                                    class="w-4 h-4 accent-black rounded"
                                />
                                <span class="text-sm text-gray-700">{{
                                    section.name
                                }}</span>
                            </label>
                        </div>
                        <p
                            v-if="form.errors.section_ids"
                            class="text-xs text-red-500 mt-1"
                        >
                            {{ form.errors.section_ids }}
                        </p>
                    </div>

                    <div v-if="form.type === 'selected'" class="pl-7">
                        <p
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2"
                        >
                            Выберите участников
                        </p>
                        <div class="space-y-1.5 max-h-56 overflow-y-auto pr-1">
                            <label
                                v-for="participant in participants"
                                :key="participant.id"
                                class="flex items-center gap-3 cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50"
                            >
                                <input
                                    type="checkbox"
                                    :value="participant.id"
                                    v-model="form.user_ids"
                                    class="w-4 h-4 accent-black rounded"
                                />
                                <span class="text-sm text-gray-700">
                                    {{ participant.last_name }}
                                    {{ participant.first_name }}
                                    <span class="text-gray-400"
                                        >— {{ participant.email }}</span
                                    >
                                </span>
                            </label>
                            <p
                                v-if="participants.length === 0"
                                class="text-sm text-gray-400 px-3 py-2"
                            >
                                Участников не найдено.
                            </p>
                        </div>
                        <p
                            v-if="form.errors.user_ids"
                            class="text-xs text-red-500 mt-1"
                        >
                            {{ form.errors.user_ids }}
                        </p>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2"
                            >Тема</label
                        >
                        <input
                            v-model="form.subject"
                            type="text"
                            placeholder="Тема письма"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                        />
                        <p
                            v-if="form.errors.subject"
                            class="text-xs text-red-500 mt-1"
                        >
                            {{ form.errors.subject }}
                        </p>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2"
                            >Сообщение</label
                        >
                        <textarea
                            v-model="form.body"
                            rows="6"
                            placeholder="Текст письма..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-900 placeholder-gray-400 resize-none focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                        ></textarea>
                        <p
                            v-if="form.errors.body"
                            class="text-xs text-red-500 mt-1"
                        >
                            {{ form.errors.body }}
                        </p>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-8 py-3 bg-black text-white rounded-full font-bold text-sm hover:bg-gray-800 transition-all shadow-lg shadow-black/10 disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            {{ form.processing ? "Отправка..." : "Отправить" }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import ExpertLayout from "../../Common/ExpertLayout.vue";

defineOptions({
    meta: {
        layout: ExpertLayout,
    },
});

defineProps({
    sections: {
        type: Array,
        default: () => [],
    },
    participants: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    type: "all",
    subject: "",
    body: "",
    section_ids: [],
    user_ids: [],
});

function submit() {
    form.post("/expert/mailings/send");
}
</script>
