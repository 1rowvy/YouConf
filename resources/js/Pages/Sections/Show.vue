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
            class="bg-white border border-gray-100 p-8 md:p-12 rounded-[2.5rem] shadow-sm"
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
                    class="text-4xl md:text-5xl font-extrabold tracking-tight text-[#1a1a1a]"
                >
                    {{ section.name }}
                </h1>
            </div>

            <div class="mb-12">
                <p class="text-lg md:text-xl text-gray-500 font-medium">
                    {{ section.full_description }}
                </p>
            </div>

            <div
                class="pt-10 border-t border-gray-50 flex flex-col sm:flex-row items-center gap-4"
            >
                <template v-if="section.can_registration">
                    <button
                        @click="toggleRegistration"
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
        <div v-if="dateKeys.length > 0" class="flex space-x-2 mb-6 border-b overflow-x-auto scrollbar-hide">
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
    </div>
</template>

<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import ScheduleTable from "@/Components/ScheduleTable.vue";
import dayjs from "dayjs";
import "dayjs/locale/ru";

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
        const form = useForm({});

        const statusClasses = {
            planned: "bg-orange-50 text-orange-600",
            registration: "bg-blue-50 text-blue-600",
            ongoing: "bg-emerald-50 text-emerald-700",
            finished: "bg-gray-100 text-gray-400",
        };

        const toggleRegistration = () => {
            if (!props.user) {
                window.location.href = "/login";
                return;
            }
            form.post(`/sections/${props.section.id}/register`, {
                preserveScroll: true,
            });
        };

        return { statusClasses, toggleRegistration };
    },
};
</script>
