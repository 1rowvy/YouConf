<template>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <!-- Информация о пользователе -->
        <div
            class="bg-white border border-gray-100 p-5 md:p-12 rounded-2xl md:rounded-[2.5rem] shadow-sm mb-8"
        >
            <div class="mb-10">
                <h1
                    class="text-2xl md:text-4xl font-extrabold tracking-tight text-[#1a1a1a]"
                >
                    Информация о пользователе
                </h1>
            </div>

            <div class="space-y-4">
                <p class="text-base md:text-lg text-gray-500">
                    <strong class="font-bold text-gray-700">Имя:</strong>
                    {{ user_data.first_name }}
                </p>
                <p class="text-base md:text-lg text-gray-500">
                    <strong class="font-bold text-gray-700">Фамилия:</strong>
                    {{ user_data.last_name }}
                </p>
                <p
                    v-if="user_data.email"
                    class="text-base md:text-lg text-gray-500"
                >
                    <strong class="font-bold text-gray-700">Email:</strong>
                    {{ user_data.email }}
                </p>
            </div>

            <div class="pt-10 border-t border-gray-50">
                <Link
                    :disabled="isDisabled"
                    :href="`/user/${user_data.id}/edit`"
                    class="inline-block px-10 py-4 bg-black text-white rounded-full hover:bg-gray-800 shadow-lg shadow-black/10 transition-all duration-300 font-bold text-base"
                >
                    Редактировать профиль
                </Link>
            </div>
        </div>

        <!-- Мои секции -->
        <div class="mb-8">
            <h2
                class="text-xl md:text-2xl font-extrabold tracking-tight text-[#1a1a1a] mb-5"
            >
                Мои секции
            </h2>

            <div
                v-if="sections.length === 0"
                class="text-center py-12 bg-white border border-gray-100 rounded-2xl shadow-sm"
            >
                <p class="text-gray-400 text-base">
                    Вы не участвуете ни в одной секции
                </p>
            </div>

            <div
                v-else
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6"
            >
                <Link
                    v-for="section in sections"
                    :key="section.id"
                    :href="`/sections/${section.id}`"
                    class="group bg-white border border-gray-100 p-5 md:p-6 rounded-2xl shadow-sm transition-all duration-300 flex flex-col h-full"
                >
                    <div class="mb-3">
                        <span
                            :class="sectionStatusClasses[section.status]"
                            class="px-3 py-1 rounded-full text-[10px] font-bold uppercase"
                        >
                            {{ section.status_label }}
                        </span>
                    </div>

                    <h3
                        class="text-lg font-bold mb-2 group-hover:text-blue-600 transition-colors"
                    >
                        {{ section.name }}
                    </h3>

                    <p class="text-gray-500 text-sm mb-3 line-clamp-3 flex-1">
                        {{ section.description }}
                    </p>

                    <div
                        v-if="section.start_date || section.end_date"
                        class="flex items-center gap-1.5 text-xs text-gray-400 mt-auto pt-3 border-t border-gray-50"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-3.5 w-3.5"
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
                        <span v-if="section.start_date && section.end_date">
                            {{ formatDate(section.start_date) }} —
                            {{ formatDate(section.end_date) }}
                        </span>
                        <span v-else-if="section.start_date">
                            с {{ formatDate(section.start_date) }}
                        </span>
                        <span v-else>
                            до {{ formatDate(section.end_date) }}
                        </span>
                    </div>
                </Link>
            </div>
        </div>

        <!-- Мои тезисы -->
        <div v-if="$page.props?.role !== 'expert'">
            <h2
                class="text-xl md:text-2xl font-extrabold tracking-tight text-[#1a1a1a] mb-5"
            >
                Мои тезисы
            </h2>

            <ThesesTable
                :theses="theses"
                :statuses="statuses"
                :role="$page.props?.role"
                @status-updated="handleStatusUpdated"
            />
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import ThesesTable from "@/Components/ThesesTable.vue";

export default {
    name: "UserProfile",
    props: {
        user_data: Object,
        theses: Array,
        sections: {
            type: Array,
            default: () => [],
        },
    },
    components: {
        Link,
        ThesesTable,
    },
    data() {
        return {
            sectionStatusClasses: {
                planned: "bg-orange-50 text-orange-600",
                registration: "bg-blue-50 text-blue-600",
                ongoing: "bg-emerald-50 text-emerald-700",
                finished: "bg-gray-100 text-gray-400",
            },
        };
    },
    methods: {
        formatDate(dateString) {
            if (!dateString) return "";
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, "0");
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const year = date.getFullYear();
            return `${day}.${month}.${year}`;
        },
    },
};
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
}
</style>
