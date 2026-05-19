<template>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <Link
            href="/sections"
            class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-black mb-8 transition-all group"
        >
            <span
                class="mr-2 transform group-hover:-translate-x-1 transition-all"
                >←</span
            >
            Назад к списку
        </Link>

        <div
            class="bg-white border border-gray-100 p-5 md:p-12 rounded-2xl md:rounded-[2.5rem] shadow-sm"
        >
            <div class="mb-10">
                <div class="mb-6">
                    <span
                        :class="statusClasses[section.status]"
                        class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase"
                    >
                        {{ section.status_label }}
                    </span>
                </div>

                <h1
                    class="text-2xl md:text-4xl font-extrabold tracking-tight text-[#1a1a1a]"
                >
                    {{ section.name }}
                </h1>
            </div>

            <div class="mb-8">
                <p class="text-base md:text-lg text-gray-500 font-medium">
                    {{ section.full_description }}
                </p>
            </div>

            <div class="mb-10 flex flex-col sm:flex-row gap-6">
                <div
                    v-if="section.start_date || section.end_date"
                    class="flex items-start gap-3"
                >
                    <div class="mt-0.5 text-gray-400">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p
                            class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5"
                        >
                            Даты проведения
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ section.start_date
                            }}{{
                                section.end_date &&
                                section.end_date !== section.start_date
                                    ? " — " + section.end_date
                                    : ""
                            }}
                        </p>
                    </div>
                </div>
                <div v-if="section.chairs" class="flex items-start gap-3">
                    <div class="mt-0.5 text-gray-400">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p
                            class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5"
                        >
                            Председатели
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ section.chairs }}
                        </p>
                    </div>
                </div>
                <div
                    v-if="section.location_names"
                    class="flex items-start gap-3"
                >
                    <div class="mt-0.5 text-gray-400">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p
                            class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5"
                        >
                            Аудитория
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ section.location_names }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="pt-10 border-t border-gray-50 flex flex-col sm:flex-row items-center gap-4"
            >
                <template v-if="section.can_registration">
                    <button
                        @click="handleRegisterClick"
                        :class="
                            section.is_joined
                                ? 'bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-600'
                                : 'bg-black text-white hover:bg-gray-800 shadow-lg shadow-black/10'
                        "
                        class="w-full sm:w-auto px-10 py-4 rounded-full transition-all duration-300 font-bold text-base"
                    >
                        {{
                            section.is_joined
                                ? "Отменить участие"
                                : "Участвовать в секции"
                        }}
                    </button>
                </template>

                <Link
                    v-if="section.is_joined && section.can_create_thesis"
                    :href="`/theses/create/${section.id}`"
                    class="w-full sm:w-auto px-10 py-4 bg-blue-600 text-white rounded-full hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all duration-300 font-bold text-base text-center"
                >
                    Создать тезис
                </Link>

                <div
                    v-if="section.is_joined"
                    class="sm:ml-auto flex items-center text-emerald-600 font-bold text-sm"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-1.5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    Вы участвуете
                </div>
            </div>
        </div>

        <div
            v-if="dateKeys.length > 0"
            class="flex space-x-2 mb-6 border-b overflow-x-auto scrollbar-hide"
        >
            <button
                v-for="date in dateKeys"
                :key="date"
                @click="selectedDate = date"
                :class="[
                    'px-4 sm:px-6 py-3 font-bold text-sm transition-all whitespace-nowrap',
                    selectedDate === date
                        ? 'border-b-2 border-blue-600 text-blue-600'
                        : 'text-gray-400 hover:text-gray-600',
                ]"
            >
                {{ formatDate(date) }}
            </button>
        </div>

        <div v-if="currentEvents.length > 0">
            <ScheduleTable :sections="[section]" :events="currentEvents" />
        </div>

        <div v-else class="text-center py-20 bg-gray-50 rounded-xl">
            <p class="text-gray-500">
                В этот день выступлений не запланировано.
            </p>
        </div>

        <!-- Модальное окно анкеты -->
        <div
            v-if="showModal !== false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4 py-4"
            @click.self="closeModal"
        >
            <div
                class="bg-white rounded-2xl shadow-xl w-full max-w-lg flex flex-col max-h-[85vh] sm:max-h-[90vh]"
            >
                <h2
                    class="text-xl font-extrabold text-gray-900 px-8 pt-8 pb-4 shrink-0"
                >
                    Регистрация на секцию
                </h2>

                <form
                    @submit.prevent="submitRegistration"
                    class="flex flex-col flex-1 overflow-hidden min-h-0"
                >
                    <div class="space-y-4 overflow-y-auto px-8 flex-1">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 mb-1"
                                    >Уровень обучения<span
                                        class="text-red-500 ml-0.5"
                                        >*</span
                                    ></label
                                >
                                <select
                                    v-model="form.degree_type"
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                                >
                                    <option value="bachelor">
                                        Бакалавриат
                                    </option>
                                    <option value="magistrant">
                                        Магистратура
                                    </option>
                                    <option value="schoolboy">Школьник</option>
                                    <option value="postgraduate">
                                        Аспирант
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 mb-1"
                                    >Курс<span
                                        v-if="!isOptionalLevel"
                                        class="text-red-500 ml-0.5"
                                        >*</span
                                    ></label
                                >
                                <input
                                    v-model="form.course"
                                    type="number"
                                    min="1"
                                    max="5"
                                    placeholder="1–5"
                                    :required="!isOptionalLevel"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 mb-1"
                                >Номер телефона<span class="text-red-500 ml-0.5"
                                    >*</span
                                ></label
                            >
                            <input
                                v-model="form.phone_number"
                                type="text"
                                placeholder="+79999999999"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 mb-1"
                                >Номер группы<span
                                    v-if="!isOptionalLevel"
                                    class="text-red-500 ml-0.5"
                                    >*</span
                                ></label
                            >
                            <input
                                v-model="form.group_number"
                                type="text"
                                placeholder="Например: 02121-ДБ"
                                :required="!isOptionalLevel"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 mb-2"
                                >Доклады<span class="text-red-500 ml-0.5"
                                    >*</span
                                ></label
                            >
                            <div
                                v-for="(item, index) in form.topics"
                                :key="index"
                                class="mb-3 p-4 border border-gray-200 rounded-xl bg-gray-50"
                            >
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <span
                                        class="text-sm font-semibold text-gray-500"
                                        >Доклад {{ index + 1 }}</span
                                    >
                                    <button
                                        v-if="form.topics.length > 1"
                                        type="button"
                                        @click="removeTopic(index)"
                                        class="text-gray-400 hover:text-red-500 transition-colors text-xl leading-none"
                                    >
                                        ×
                                    </button>
                                </div>
                                <input
                                    v-model="item.topic"
                                    type="text"
                                    :required="index === 0"
                                    placeholder="Тема доклада (предварительная тема)"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white mb-2"
                                />
                                <textarea
                                    v-model="item.description"
                                    rows="3"
                                    maxlength="1000"
                                    placeholder="Описание вашего доклада"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none bg-white"
                                ></textarea>
                                <div
                                    class="text-xs text-gray-400 text-right mt-1"
                                >
                                    {{ item.description.length }} / 1000
                                </div>
                            </div>
                            <button
                                v-if="form.topics.length < 5"
                                type="button"
                                @click="addTopic"
                                class="text-sm text-blue-500 hover:text-blue-700 font-semibold transition-colors"
                            >
                                + Добавить ещё доклад
                            </button>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 mb-1"
                                >Руководитель (нужно указать учёную степень,
                                учёное звание и ФИО руководителя)<span
                                    class="text-red-500 ml-0.5"
                                    >*</span
                                ></label
                            >
                            <input
                                v-model="form.supervisor"
                                type="text"
                                placeholder="Например: к.ф.-м.н. доцент Иванов И.И."
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div class="pb-2">
                            <label
                                class="block text-sm font-bold text-gray-700 mb-1"
                                >Соавторы</label
                            >
                            <p class="text-sm text-gray-500 mb-1">
                                Если ваш доклад делается в соавторстве, то
                                обязательно укажите здесь ФИО соавторов, номер
                                группы, контактный телефон и электронную почту.
                            </p>
                            <textarea
                                v-model="form.co_author"
                                rows="3"
                                maxlength="1000"
                                placeholder="Указать сооавторов необходимо по одному на строку"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            ></textarea>
                            <div class="text-xs text-gray-400 text-right mt-1">
                                {{ form.co_author.length }} / 1000
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex sm:flex-row flex-col-reverse gap-3 px-8 pb-8 pt-4 shrink-0"
                    >
                        <button
                            type="button"
                            @click="closeModal"
                            class="flex-1 py-3 rounded-full border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all"
                        >
                            Отмена
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex-1 py-3 rounded-full bg-black text-white text-sm font-semibold hover:bg-gray-800 transition-all disabled:opacity-50"
                        >
                            Зарегистрироваться
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import ScheduleTable from "@/Components/ScheduleTable.vue";
import dayjs from "dayjs";
import "dayjs/locale/ru";
import { ref, watch, computed } from "vue";

export default {
    components: { Link, ScheduleTable },
    props: {
        section: Object,
        user: Object,
        schedules: Object,
    },
    data() {
        return {
            selectedDate: Object.keys(this.schedules)[0] || null,
        };
    },
    computed: {
        dateKeys() {
            return Object.keys(this.schedules).sort();
        },
        currentEvents() {
            return this.selectedDate ? this.schedules[this.selectedDate] : [];
        },
    },
    methods: {
        formatDate(date) {
            return dayjs(date).locale("ru").format("DD MMMM (dd)");
        },
    },
    setup(props) {
        const form = useForm({
            degree_type: "",
            course: "",
            group_number: "",
            topics: [{ topic: "", description: "" }],
            supervisor: "",
            co_author: "",
            phone_number: "",
        });

        const showModal = ref(false);

        watch(showModal, (val) => {
            document.body.style.overflow = val ? "hidden" : "";
        });

        const statusClasses = {
            planned: "bg-orange-50 text-orange-600",
            registration: "bg-blue-50 text-blue-600",
            thesis_submission: "bg-purple-50 text-purple-600",
            thesis_review: "bg-amber-50 text-amber-600",
            ongoing: "bg-emerald-50 text-emerald-700",
            finished: "bg-gray-100 text-gray-400",
        };

        const handleRegisterClick = () => {
            if (!props.user) {
                window.location.href = "/login";
                return;
            }
            if (props.section.is_joined) {
                form.post(`/sections/${props.section.id}/register`, {
                    preserveScroll: true,
                });
                return;
            }
            clearForm();
            console.log(form);
            showModal.value = true;
        };

        const clearForm = () => {
            form.reset();
            form.clearErrors();

            form.degree_type = "";
            form.course = "";
            form.group_number = "";
            form.topics = [{ topic: "", description: "" }];
            form.supervisor = "";
            form.co_author = "";
            form.phone_number = "";
        };

        const addTopic = () => {
            form.topics.push({ topic: "", description: "" });
        };

        const removeTopic = (index) => {
            form.topics.splice(index, 1);
        };

        const closeModal = () => {
            showModal.value = false;
        };

        const submitRegistration = () => {
            form.post(`/sections/${props.section.id}/register`, {
                preserveScroll: true,
                onSuccess: () => {
                    showModal.value = false;
                },
            });
        };

        const isOptionalLevel = computed(
            () =>
                form.degree_type === "schoolboy" ||
                form.degree_type === "postgraduate",
        );

        return {
            statusClasses,
            form,
            showModal,
            handleRegisterClick,
            closeModal,
            submitRegistration,
            isOptionalLevel,
            addTopic,
            removeTopic,
        };
    },
};
</script>
