<template>
    <div class="flex flex-col gap-8">
        <header
            class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4"
        >
            <div>
                <p class="text-sm text-gray-400 mb-1">Секции</p>
                <p class="text-2xl font-bold text-gray-900">
                    Назначенные секции
                </p>
            </div>
        </header>

        <div v-if="forms.length === 0" class="text-sm text-gray-400">
            Вам не назначено ни одной секции.
        </div>

        <div
            v-for="(form, i) in forms"
            :key="form.id"
            class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden"
        >
            <div
                class="px-6 py-5 border-b border-gray-50 flex items-center justify-between gap-4"
            >
                <div>
                    <p class="font-semibold text-gray-900">{{ form.name }}</p>
                </div>
                <span
                    class="text-xs font-medium px-2.5 py-1 rounded-full"
                    :class="statusClasses[form.status]"
                >
                    {{ statusLabel(form.status) }}
                </span>
            </div>

            <div
                class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5"
            >
                <div class="sm:col-span-2 lg:col-span-3">
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5"
                        >Статус</label
                    >
                    <select
                        v-model="form.status"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                    >
                        <option
                            v-for="opt in statusOptions"
                            :key="opt.value"
                            :value="opt.value"
                        >
                            {{ opt.label }}
                        </option>
                    </select>
                </div>

                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5"
                        >Дата начала</label
                    >
                    <input
                        type="date"
                        v-model="form.start_date"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                    />
                </div>

                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5"
                        >Дата окончания</label
                    >
                    <input
                        type="date"
                        v-model="form.end_date"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                    />
                </div>

                <div class="sm:col-span-2 lg:col-span-1">
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5"
                        >Лимит попыток</label
                    >
                    <input
                        type="number"
                        v-model="form.revision_limit"
                        min="1"
                        max="99"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                    />
                </div>

                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5"
                        >Начало работы секции</label
                    >
                    <input
                        type="time"
                        v-model="form.start_time"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                    />
                </div>

                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5"
                        >Конец работы секции</label
                    >
                    <input
                        type="time"
                        v-model="form.end_time"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                    />
                </div>

                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5"
                        >Длительность защиты (мин)</label
                    >
                    <input
                        type="number"
                        v-model="form.presentation_duration"
                        min="1"
                        max="300"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                    />
                </div>
            </div>

            <div v-if="errors[i]" class="px-6 pb-3">
                <p
                    v-for="(msg, field) in errors[i]"
                    :key="field"
                    class="text-xs text-red-500"
                >
                    {{ msg }}
                </p>
            </div>

            <div class="px-6 pb-5 flex justify-end">
                <button
                    @click="save(i)"
                    :disabled="form._loading"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-full text-sm font-bold transition-all"
                    :class="
                        form._saved
                            ? 'bg-emerald-50 text-emerald-600'
                            : 'bg-black text-white hover:bg-gray-800 shadow-lg shadow-black/10 disabled:opacity-60 disabled:cursor-not-allowed'
                    "
                >
                    <span v-if="form._loading">Сохранение...</span>
                    <span v-else-if="form._saved">Сохранено</span>
                    <span v-else>Сохранить</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import ExpertLayout from "../../Common/ExpertLayout.vue";

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

const statusOptions = [
    { value: "planned", label: "Запланирована" },
    { value: "registration", label: "Регистрация участников" },
    { value: "thesis_submission", label: "Отправка тезисов" },
    { value: "thesis_review", label: "Проверка тезисов" },
    { value: "ongoing", label: "Идёт" },
    { value: "finished", label: "Прошла" },
];

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

const statusLabel = (value) =>
    statusOptions.find((o) => o.value === value)?.label ?? value;

const trimTime = (t) => (t ? t.slice(0, 5) : "");

const forms = ref(
    props.sections.map((s) => ({
        ...s,
        start_time: trimTime(s.start_time),
        end_time: trimTime(s.end_time),
        _loading: false,
        _saved: false,
    })),
);

const errors = ref(props.sections.map(() => null));

function save(i) {
    const f = forms.value[i];
    f._loading = true;
    f._saved = false;
    errors.value[i] = null;

    Inertia.patch(
        `/expert/sections/${f.id}`,
        {
            status: f.status,
            start_date: f.start_date,
            end_date: f.end_date,
            start_time: f.start_time,
            end_time: f.end_time,
            revision_limit: f.revision_limit,
            presentation_duration: f.presentation_duration,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                f._loading = false;
                f._saved = true;
                setTimeout(() => {
                    f._saved = false;
                }, 2500);
            },
            onError: (errs) => {
                f._loading = false;
                errors.value[i] = errs;
            },
        },
    );
}
</script>
