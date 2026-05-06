<template>
    <div class="flex flex-col gap-6">
        <h1 class="text-1xl text-gray-600 mb-6">Главная</h1>
        <p class="text-4xl font-semibold">
            Здравствуйте, {{ $page.props.user_data.first_name }}!
        </p>
        <h1>Назначенные секции</h1>
        <div class="grid grid-cols-2 gap-4">
            <div
                v-for="section in sections"
                :key="section.id"
                class="group bg-white border border-gray-100 p-6 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full"
            >
                <div class="mb-4">
                    <span
                        :class="statusClasses[section.status]"
                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase"
                    >
                        {{ section.status_label }}
                    </span>
                </div>

                <div class="flex-1">
                    <h3
                        class="text-xl font-bold mb-3 group-hover:text-blue-600 transition-all"
                    >
                        {{ section.name }}
                    </h3>
                    <p class="text-gray-500 text-sm">
                        {{ section.description }}
                    </p>
                    <div class="mt-3 space-y-1">
                        <p
                            v-if="section.start_date || section.end_date"
                            class="text-xs text-gray-400"
                        >
                            <span class="font-semibold text-gray-500"
                                >Даты:</span
                            >
                            {{ formatDate(section.start_date)
                            }}{{
                                section.end_date &&
                                section.end_date !== section.start_date
                                    ? " — " + formatDate(section.end_date)
                                    : ""
                            }}
                        </p>
                        <p v-if="section.chairs" class="text-xs text-gray-400">
                            <span class="font-semibold text-gray-500"
                                >Председатели:</span
                            >
                            {{ section.chairs }}
                        </p>
                        <p
                            v-if="section.location_names"
                            class="text-xs text-gray-400"
                        >
                            <span class="font-semibold text-gray-500"
                                >Аудитория:</span
                            >
                            {{ section.location_names }}
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between pt-2">
                        <Link
                            :href="`/sections/${section.id}`"
                            class="text-sm font-bold text-gray-400 hover:text-black transition-all"
                        >
                            Подробнее →
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import ExpertLayout from "../../Common/ExpertLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";

defineOptions({
    meta: {
        layout: ExpertLayout,
    },
});

defineProps({
    sections: Array,
});

const statusClasses = {
    planned: "bg-orange-50 text-orange-600",
    registration: "bg-blue-50 text-blue-600",
    thesis_submission: "bg-purple-50 text-purple-600",
    thesis_review: "bg-amber-50 text-amber-600",
    ongoing: "bg-emerald-50 text-emerald-700",
    finished: "bg-gray-100 text-gray-400",
};

const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}.${month}.${year}`;
};
</script>
