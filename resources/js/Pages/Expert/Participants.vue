<template>
    <div class="flex flex-col gap-8">
        <header
            class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4"
        >
            <div>
                <p class="text-sm text-gray-400 mb-1">Участники</p>
                <p
                    class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight"
                >
                    Список зарегистрированных участников
                </p>
            </div>
            <div
                class="flex items-center gap-2 text-sm font-semibold text-gray-700 bg-white border border-gray-100 rounded-full px-4 py-2 shadow-sm self-start"
            >
                <Users :size="16" class="text-gray-400" />
                <span>{{ totalParticipants }} участников</span>
            </div>
        </header>

        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <label
                    for="sectionFilter"
                    class="text-sm font-semibold text-gray-700 shrink-0"
                >
                    Фильтр по секции:
                </label>
                <select
                    v-model="selectedSection"
                    id="sectionFilter"
                    class="px-4 py-2 border border-gray-200 rounded-xl bg-white hover:border-gray-300 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-medium w-full sm:w-auto"
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
        </div>

        <div
            v-for="section in filteredSections"
            :key="section.id"
            class="flex flex-col gap-4"
        >
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">
                    {{ section.name }}
                </h2>
                <span
                    class="text-xs font-semibold text-gray-500 bg-gray-100 px-3 py-1 rounded-full"
                >
                    {{ section.users.length }}
                    {{ pluralize(section.users.length) }}
                </span>
            </div>

            <div
                v-if="section.users.length === 0"
                class="bg-white border border-gray-100 rounded-2xl p-10 text-center"
            >
                <Users :size="32" class="mx-auto text-gray-300 mb-3" />
                <p class="text-sm text-gray-500">
                    Нет зарегистрированных участников.
                </p>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div
                    v-for="user in sortedUsers(section.users)"
                    :key="'m-' + user.id"
                    class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all"
                >
                    <div class="flex items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <p
                                class="text-base font-bold text-gray-900 truncate"
                            >
                                {{ user.last_name }} {{ user.first_name }}
                                {{ user.patronymic }}
                            </p>

                            <div class="mt-2 space-y-1">
                                <div
                                    v-if="user.email"
                                    class="flex items-center gap-2 text-sm text-gray-500"
                                >
                                    <Mail
                                        :size="14"
                                        class="text-gray-400 shrink-0"
                                    />
                                    <span class="truncate">{{
                                        user.email
                                    }}</span>
                                </div>
                                <div
                                    v-if="user.phone_number"
                                    class="flex items-center gap-2 text-sm text-gray-500"
                                >
                                    <Phone
                                        :size="14"
                                        class="text-gray-400 shrink-0"
                                    />
                                    <span>{{ user.phone_number }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs"
                    >
                        <div v-if="user.degree_type">
                            <p class="text-gray-400 font-medium mb-0.5">
                                Уровень
                            </p>
                            <p class="text-gray-900 font-semibold">
                                {{ degreeLabel(user.degree_type) }}
                            </p>
                        </div>
                        <div v-if="user.course">
                            <p class="text-gray-400 font-medium mb-0.5">Курс</p>
                            <p class="text-gray-900 font-semibold">
                                {{ user.course }}
                            </p>
                        </div>
                        <div v-if="user.group_number">
                            <p class="text-gray-400 font-medium mb-0.5">
                                Группа
                            </p>
                            <p class="text-gray-900 font-semibold">
                                {{ user.group_number }}
                            </p>
                        </div>
                    </div>

                    <div
                        v-if="user.topics && user.topics.length > 0"
                        class="mt-4 pt-4 border-t border-gray-100 space-y-3"
                    >
                        <div
                            v-for="(t, i) in user.topics"
                            :key="i"
                            class="bg-gray-50 rounded-xl p-3"
                        >
                            <p class="text-xs text-gray-400 font-medium mb-1">
                                Тема{{
                                    user.topics.length > 1 ? " " + (i + 1) : ""
                                }}
                            </p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ t.topic }}
                            </p>
                            <p
                                v-if="t.description"
                                class="text-xs text-gray-500 mt-1"
                            >
                                {{ t.description }}
                            </p>
                        </div>
                    </div>

                    <div
                        v-if="user.supervisor || user.co_author"
                        class="mt-4 pt-4 border-t border-gray-100 space-y-1.5 text-xs"
                    >
                        <p v-if="user.supervisor" class="text-gray-500">
                            <span class="font-semibold text-gray-700"
                                >Руководитель:</span
                            >
                            {{ user.supervisor }}
                        </p>
                        <p v-if="user.co_author" class="text-gray-500">
                            <span class="font-semibold text-gray-700"
                                >Соавторы:</span
                            >
                            {{ user.co_author }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ExpertLayout from "../../Common/ExpertLayout.vue";
import { Mail, Phone, Users } from "@lucide/vue";

export default {
    components: { Mail, Phone, Users },
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
            avatarPalette: [
                "bg-gradient-to-br from-blue-400 to-purple-500",
                "bg-gradient-to-br from-pink-400 to-rose-500",
                "bg-gradient-to-br from-emerald-400 to-teal-500",
                "bg-gradient-to-br from-amber-400 to-orange-500",
                "bg-gradient-to-br from-indigo-400 to-blue-500",
                "bg-gradient-to-br from-fuchsia-400 to-pink-500",
            ],
        };
    },
    computed: {
        filteredSections() {
            if (this.selectedSection === "") {
                return this.sections;
            }
            return this.sections.filter((s) => s.id === this.selectedSection);
        },
        totalParticipants() {
            return this.filteredSections.reduce(
                (acc, s) => acc + (s.users?.length || 0),
                0,
            );
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
        pluralize(n) {
            const mod10 = n % 10;
            const mod100 = n % 100;
            if (mod10 === 1 && mod100 !== 11) return "участник";
            if ([2, 3, 4].includes(mod10) && ![12, 13, 14].includes(mod100))
                return "участника";
            return "участников";
        },
    },
};
</script>
