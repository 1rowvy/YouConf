<template>
    <div class="flex flex-col gap-8">
        <header class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-400 mb-1">Тезисы</p>
                <h1 class="text-2xl font-bold text-gray-900">Рецензирование тезисов</h1>
            </div>
            <div class="flex items-center gap-2">
                <select
                    v-model="statusFilter"
                    class="px-3 py-2 rounded-xl border border-gray-200 bg-white text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                >
                    <option value="">Все статусы</option>
                    <option value="1">На рассмотрении</option>
                    <option value="2">Принято</option>
                    <option value="3">На доработку</option>
                    <option value="4">Отклонено</option>
                </select>
            </div>
        </header>

        <div
            v-if="filteredTheses.length === 0"
            class="bg-white border border-gray-100 rounded-2xl p-16 text-center"
        >
            <FileSearch :size="36" class="mx-auto text-gray-300 mb-3" />
            <p class="text-gray-500">Тезисов не найдено.</p>
        </div>

        <div v-else class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Название
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Автор
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Секция
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Правки
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Статус
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Действия
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="thesis in filteredTheses"
                        :key="thesis.id"
                        class="hover:bg-gray-50 transition-colors duration-150"
                    >
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900 max-w-xs truncate">{{ thesis.title }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ thesis.user.last_name }} {{ thesis.user.first_name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ thesis.section.name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ thesis.revision_count }} / {{ thesis.section.revision_limit }}
                        </td>
                        <td class="px-6 py-4">
                            <span
                                :class="statusClasses[thesis.status_id]"
                                class="px-3 py-1 rounded-full text-[10px] font-bold uppercase"
                            >
                                {{ thesis.status.name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <Link
                                :href="`/expert/theses/${thesis.id}/review`"
                                class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-black text-white rounded-full text-xs font-bold hover:bg-gray-800 transition-all"
                            >
                                <Pencil :size="12" />
                                Рецензировать
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import { FileSearch, Pencil } from '@lucide/vue'
import ExpertLayout from '../../Common/ExpertLayout.vue'

defineOptions({
    meta: {
        layout: ExpertLayout,
    },
})

const props = defineProps({
    theses: {
        type: Array,
        default: () => [],
    },
})

const statusFilter = ref('')

const statusClasses = {
    1: 'bg-orange-50 text-orange-600',
    2: 'bg-emerald-50 text-emerald-700',
    3: 'bg-blue-50 text-blue-600',
    4: 'bg-red-50 text-red-500',
}

const filteredTheses = computed(() => {
    if (!statusFilter.value) return props.theses
    return props.theses.filter(t => String(t.status_id) === statusFilter.value)
})
</script>
