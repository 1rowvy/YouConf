<template>
    <div class="w-full">
        <div class="">
            <h1 class="text-1xl text-gray-600 mb-6">Участники</h1>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <label
                    for="sectionFilter"
                    class="text-sm font-bold text-gray-700"
                >
                    Фильтр по секции:
                </label>
                <select
                    v-model="selectedSection"
                    id="sectionFilter"
                    class="px-4 py-2 border border-gray-200 rounded-xl bg-white hover:border-gray-300 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold"
                >
                    <option value="">Все секции</option>
                    <option
                        v-for="section in sections"
                        :key="section.id"
                        :value="section.id"
                    >
                        {{ section.name }}
                    </option>
                </select>
            </div>
            <div v-for="section in filteredSections" :key="section.id">
                <h2 class="text-lg font-bold text-gray-800 mb-2">
                    {{ section.name }}
                </h2>

                <div v-if="section.users.length > 0" class="mb-2">
                    <p class="text-sm text-gray-700">
                        Количество участников: {{ section.users.length }}
                    </p>
                </div>

                <div
                    v-if="section.users.length === 0"
                    class="text-sm text-gray-400 py-4 px-6 bg-gray-50 rounded-xl"
                >
                    Нет зарегистрированных участников.
                </div>
                <div class="space-y-3">
                    <div
                        v-for="user in sortedUsers(section.users)"
                        :key="'m-' + user.id"
                        class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm"
                    >
                        <p class="text-base font-bold text-gray-900">
                            {{ user.last_name }} {{ user.first_name }}
                            {{ user.patronymic }}
                        </p>
                        <p v-if="user.email" class="text-sm text-gray-500 mt-1">
                            {{ user.email }}
                        </p>
                        <p v-if="user.email" class="text-sm text-gray-500 mt-1">
                            {{ user.phone_number }}
                        </p>
                        <div
                            class="mt-3 pt-3 border-t border-gray-100 space-y-1"
                        >
                            <p
                                v-if="user.degree_type"
                                class="text-sm text-gray-600"
                            >
                                <span class="font-semibold">Уровень:</span>
                                {{ degreeLabel(user.degree_type) }}
                            </p>
                            <p v-if="user.course" class="text-sm text-gray-600">
                                <span class="font-semibold">Курс:</span>
                                {{ user.course }}
                            </p>
                            <p
                                v-if="user.group_number"
                                class="text-sm text-gray-600"
                            >
                                <span class="font-semibold">Группа:</span>
                                {{ user.group_number }}
                            </p>
                            <div
                                v-if="user.topics && user.topics.length > 0"
                                class="space-y-1"
                            >
                                <div
                                    v-for="(t, i) in user.topics"
                                    :key="i"
                                    class="text-sm text-gray-600"
                                >
                                    <p>
                                        <span class="font-semibold"
                                            >Тема
                                            {{
                                                user.topics.length > 1
                                                    ? i + 1
                                                    : ""
                                            }}:</span
                                        >
                                        {{ t.topic }}
                                    </p>
                                    <p v-if="t.description">
                                        <span class="font-semibold"
                                            >Описание:</span
                                        >
                                        {{ t.description }}
                                    </p>
                                </div>
                            </div>
                            <p
                                v-if="user.supervisor"
                                class="text-sm text-gray-600"
                            >
                                <span class="font-semibold">Руководитель:</span>
                                {{ user.supervisor }}
                            </p>
                            <p
                                v-if="user.co_author"
                                class="text-sm text-gray-600"
                            >
                                <span class="font-semibold">Соавторы:</span>
                                {{ user.co_author }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ExpertLayout from "../../Common/ExpertLayout.vue";

export default {
    props: {
        sections: Array,
    },
    meta: {
        layout: ExpertLayout,
    },
    data() {
        return {
            selectedSection: "",
            sortBy: "last_name",
            sortOrder: "asc",
        };
    },
    computed: {
        filteredSections() {
            if (this.selectedSection === "") {
                return this.sections;
            }
            return this.sections.filter((s) => s.id === this.selectedSection);
        },
    },
    methods: {
        degreeLabel(val) {
            if (val === "bachelor") return "Бакалавриат";
            if (val === "magistrant") return "Магистратура";
            if (val === "schoolboy") return "Школьник";
            if (val === "postgraduate") return "Аспирант";
            return "—";
        },
        toggleSort(field) {
            if (this.sortBy === field) {
                this.sortOrder = this.sortOrder === "asc" ? "desc" : "asc";
            } else {
                this.sortBy = field;
                this.sortOrder = "asc";
            }
        },
        sortedUsers(users) {
            return [...users].sort((a, b) => {
                const va = (a[this.sortBy] || "").toLowerCase();
                const vb = (b[this.sortBy] || "").toLowerCase();
                return this.sortOrder === "asc"
                    ? va.localeCompare(vb)
                    : vb.localeCompare(va);
            });
        },
    },
};
</script>
