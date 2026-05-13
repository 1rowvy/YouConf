<template>
    <div class="flex flex-col gap-8">
        <header
            class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4"
        >
            <div>
                <p class="text-sm text-gray-400 mb-1">Секции</p>
                <p
                    class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight"
                >
                    Назначенные секции
                </p>
            </div>
        </header>
    </div>
</template>
<script setup>
import { computed } from "vue";
import ExpertLayout from "../../Common/ExpertLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";

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
            ["thesis_review", "thesis_submission"].includes(s.status),
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

const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}.${month}.${year}`;
};
</script>
