<template>
    <div class="flex flex-col gap-8">
        <header class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-400 mb-1">Главная</p>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight">
                    Здравствуйте, {{ $page.props.user_data.first_name }}!
                </h1>
                <p class="text-gray-500 mt-2">
                    Следите за вашими назначенными секциями.
                </p>
            </div>
            <div
                class="flex items-center gap-2 text-sm font-semibold text-gray-700 bg-white border border-gray-100 rounded-full px-4 py-2 shadow-sm self-start"
            >
                <Calendar :size="16" class="text-gray-400" />
                <span>{{ todayLabel }}</span>
            </div>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div
                v-for="stat in stats"
                :key="stat.label"
                class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center gap-4 shadow-sm"
            >
                <div
                    :class="[
                        'w-12 h-12 rounded-xl flex items-center justify-center',
                        stat.bg,
                    ]"
                >
                    <component :is="stat.icon" :size="22" :class="stat.fg" />
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium">{{ stat.label }}</p>
                    <p class="text-2xl font-bold text-gray-900 leading-tight">
                        {{ stat.value }}
                    </p>
                </div>
            </div>
        </div>

        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">
                    Назначенные секции
                </h2>
                <span class="text-sm text-gray-400">
                    Всего: {{ sections.length }}
                </span>
            </div>

            <div
                v-if="sections.length === 0"
                class="bg-white border border-gray-100 rounded-2xl p-10 text-center"
            >
                <Layers :size="32" class="mx-auto text-gray-300 mb-3" />
                <p class="text-gray-500">У вас пока нет назначенных секций.</p>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div
                    v-for="section in sections"
                    :key="section.id"
                    :class="[
                        'group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full overflow-hidden border border-gray-100 border-t-4',
                        statusBorder[section.status] || 'border-t-gray-200',
                    ]"
                >
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <span
                                :class="statusClasses[section.status]"
                                class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"
                            >
                                {{ section.status_label }}
                            </span>
                        </div>

                        <h3
                            class="text-lg font-bold mb-2 text-gray-900 group-hover:text-blue-600 transition-all"
                        >
                            {{ section.name }}
                        </h3>
                        <p
                            v-if="section.description"
                            class="text-gray-500 text-sm mb-4 line-clamp-2"
                        >
                            {{ section.description }}
                        </p>

                        <div class="space-y-2 mt-auto pt-4 border-t border-gray-100">
                            <div
                                v-if="section.start_date || section.end_date"
                                class="flex items-center gap-2 text-xs text-gray-500"
                            >
                                <Calendar :size="14" class="text-gray-400 shrink-0" />
                                <span>
                                    {{ formatDate(section.start_date)
                                    }}{{
                                        section.end_date &&
                                        section.end_date !== section.start_date
                                            ? " — " + formatDate(section.end_date)
                                            : ""
                                    }}
                                </span>
                            </div>
                            <div
                                v-if="section.chairs"
                                class="flex items-center gap-2 text-xs text-gray-500"
                            >
                                <UserCog :size="14" class="text-gray-400 shrink-0" />
                                <span class="truncate">{{ section.chairs }}</span>
                            </div>
                            <div
                                v-if="section.location_names"
                                class="flex items-center gap-2 text-xs text-gray-500"
                            >
                                <MapPin :size="14" class="text-gray-400 shrink-0" />
                                <span class="truncate">{{ section.location_names }}</span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="px-6 py-3 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end"
                    >
                        <Link
                            :href="`/sections/${section.id}`"
                            class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition-all flex items-center gap-1"
                        >
                            Подробнее
                            <ArrowRight :size="14" />
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { computed } from "vue";
import ExpertLayout from "../../Common/ExpertLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import {
    Calendar,
    Layers,
    FileSearch,
    PlayCircle,
    UserCog,
    MapPin,
    ArrowRight,
} from "@lucide/vue";

defineOptions({
    meta: {
        layout: ExpertLayout,
    },
});

const props = defineProps({
    sections: {
        type: Array,
        default: () => [],
    },
});

const statusClasses = {
    planned: "bg-orange-50 text-orange-600",
    registration: "bg-blue-50 text-blue-600",
    thesis_submission: "bg-purple-50 text-purple-600",
    thesis_review: "bg-amber-50 text-amber-600",
    ongoing: "bg-emerald-50 text-emerald-700",
    finished: "bg-gray-100 text-gray-400",
};

const statusBorder = {
    planned: "border-t-orange-300",
    registration: "border-t-blue-300",
    thesis_submission: "border-t-purple-300",
    thesis_review: "border-t-amber-300",
    ongoing: "border-t-emerald-400",
    finished: "border-t-gray-200",
};

const stats = computed(() => [
    {
        label: "Всего секций",
        value: props.sections.length,
        icon: Layers,
        bg: "bg-blue-50",
        fg: "text-blue-600",
    },
    {
        label: "На рецензии",
        value: props.sections.filter((s) =>
            ["thesis_review", "thesis_submission"].includes(s.status)
        ).length,
        icon: FileSearch,
        bg: "bg-amber-50",
        fg: "text-amber-600",
    },
    {
        label: "Активных",
        value: props.sections.filter((s) => s.status === "ongoing").length,
        icon: PlayCircle,
        bg: "bg-emerald-50",
        fg: "text-emerald-600",
    },
]);

const months = [
    "января", "февраля", "марта", "апреля", "мая", "июня",
    "июля", "августа", "сентября", "октября", "ноября", "декабря",
];

const todayLabel = (() => {
    const d = new Date();
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
})();

const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}.${month}.${year}`;
};
</script>
