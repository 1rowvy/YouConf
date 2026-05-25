<template>
    <div>
        <div
            v-if="theses.length === 0"
            class="text-center py-20 bg-gray-50 rounded-xl"
        >
            <p class="text-gray-500 text-lg">У вас нет заявок.</p>
        </div>

        <div v-else>
            <div class="space-y-4 md:hidden">
                <div
                    v-for="thesis in theses"
                    :key="'m-' + thesis.id"
                    class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm"
                >
                    <div class="flex items-center justify-between mb-3">
                        <span
                            :class="getStatusClass(thesis.status.name)"
                            class="px-3 py-1 rounded-full text-[10px] font-bold uppercase"
                        >
                            {{ thesis.status.name }}
                        </span>
                        <span class="text-xs text-gray-400">{{
                            formatDate(thesis.created_at)
                        }}</span>
                    </div>
                    <Link
                        :href="`/theses/${thesis.id}`"
                        class="block text-base font-bold text-gray-900 hover:text-blue-600 transition-colors mb-2"
                    >
                        {{ thesis.title }}
                    </Link>
                    <p class="text-sm text-gray-500 mb-1">
                        {{ thesis.section.name }}
                    </p>
                    <p v-if="isExpert" class="text-sm text-gray-500">
                        {{ thesis.user.first_name }} {{ thesis.user.last_name }}
                    </p>
                    <div
                        v-if="isExpert"
                        class="mt-3 pt-3 border-t border-gray-100"
                    >
                        <select
                            v-model="thesis.status_id"
                            @change="updateStatus(thesis.id, thesis.status_id)"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm font-semibold hover:border-gray-300 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                </div>
            </div>

            <div
                class="hidden md:block bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden"
            >
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th
                                @click="$emit('sort', 'title')"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                            >
                                <div class="flex items-center gap-2">
                                    Название
                                    <span class="text-gray-400">↕</span>
                                </div>
                            </th>
                            <th
                                @click="$emit('sort', 'section.name')"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                            >
                                <div class="flex items-center gap-2">
                                    Секция
                                    <span class="text-gray-400">↕</span>
                                </div>
                            </th>
                            <th
                                @click="$emit('sort', 'created_at')"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                            >
                                <div class="flex items-center gap-2">
                                    Дата
                                    <span class="text-gray-400">↕</span>
                                </div>
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider"
                            >
                                Статус
                            </th>
                            <th
                                v-if="isExpert"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider"
                            >
                                Автор
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr
                            v-for="thesis in theses"
                            :key="thesis.id"
                            class="hover:bg-gray-50 transition-colors duration-150"
                        >
                            <td class="px-6 py-4">
                                <Link
                                    :href="`/theses/${thesis.id}`"
                                    class="font-semibold text-gray-900 hover:text-blue-600 transition-colors"
                                >
                                    {{ thesis.title }}
                                </Link>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ thesis.section.name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ formatDate(thesis.created_at) }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="getStatusClass(thesis.status.name)"
                                    class="px-3 py-1 rounded-full text-[10px] font-bold uppercase"
                                >
                                    {{ thesis.status.name }}
                                </span>
                            </td>
                            <td
                                v-if="isExpert"
                                class="px-6 py-4 text-sm text-gray-600"
                            >
                                {{ thesis.user.first_name }}
                                {{ thesis.user.last_name }}
                            </td>
                            <td v-if="isExpert" class="px-6 py-4">
                                <select
                                    v-model="thesis.status_id"
                                    @change="
                                        updateStatus(
                                            thesis.id,
                                            thesis.status_id,
                                        )
                                    "
                                    class="px-3 py-1.5 border border-gray-200 rounded-lg bg-white text-sm font-semibold hover:border-gray-300 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option
                                        v-for="status in statuses"
                                        :key="status.id"
                                        :value="status.id"
                                    >
                                        {{ status.name }}
                                    </option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
    name: "ThesesTable",
    props: {
        theses: Array,
        statuses: Array,
        role: String,
    },
    components: {
        Link,
    },
    emits: ["status-updated", "sort"],
    data() {
        return {
            statusClasses: {
                "На рассмотрении": "bg-orange-50 text-orange-600",
                Принято: "bg-emerald-50 text-emerald-700",
                "На доработку": "bg-blue-50 text-blue-600",
                Отклонено: "bg-red-50 text-red-600",
            },
        };
    },
    computed: {
        isExpert() {
            return this.role === "expert";
        },
    },
    methods: {
        formatDate(date) {
            return new Date(date).toLocaleDateString(); // Форматирование даты
        },
        async updateStatus(thesisId, statusId) {
            try {
                await this.$inertia.post(`/theses/${thesisId}/status`, {
                    status_id: statusId,
                });
                this.$emit("status-updated");
            } catch (error) {
                console.error("Ошибка при обновлении статуса:", error);
            }
        },
        getStatusClass(statusName) {
            return (
                this.statusClasses[statusName] || "bg-gray-100 text-gray-400"
            );
        },
    },
};
</script>
