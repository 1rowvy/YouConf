<template>
    <aside
        class="fixed top-0 left-0 h-screen w-60 bg-white border-r border-gray-100 flex flex-col z-30"
    >
        <div class="flex items-center gap-2 px-6 pt-6 pb-8">
            <h1 class="font-bold text-lg tracking-tight">YouConf</h1>
        </div>

        <nav class="flex-1 px-3 space-y-1">
            <a
                v-for="item in menuItems"
                :key="item.name"
                :href="item.route"
                :class="[
                    'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all',
                    isActive(item.route)
                        ? 'bg-gray-900 text-white font-semibold shadow-sm'
                        : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900',
                ]"
            >
                <component :is="item.icon" :size="18" />
                <span>{{ item.name }}</span>
            </a>
        </nav>

        <div class="px-3 pb-4 pt-3 border-t border-gray-100">
            <div v-if="userData" class="flex items-center gap-3 px-2 py-3 mb-2">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">
                        {{ userData.first_name }} {{ userData.last_name }}
                    </p>
                    <p class="text-xs text-gray-400 truncate">
                        {{ userData.email || "Эксперт" }}
                    </p>
                </div>
            </div>
            <button
                @click="logout"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-all"
            >
                <LogOut :size="18" />
                <span>Выйти</span>
            </button>
        </div>
    </aside>
</template>

<script setup>
import {
    ref,
    computed,
    onMounted,
    onBeforeUnmount,
    getCurrentInstance,
} from "vue";
import { House, Layers, FileText, Users, LogOut, Sparkles, Mail } from "@lucide/vue";
import { Inertia } from "@inertiajs/inertia";

const menuItems = [
    { name: "Главная", icon: House, route: "/expert" },
    { name: "Секции", icon: Layers, route: "/expert/sections" },
    { name: "Тезисы", icon: FileText, route: "/expert/theses" },
    { name: "Участники", icon: Users, route: "/expert/participants" },
    { name: "Рассылки", icon: Mail, route: "/expert/mailings" },
];

const currentUrl = ref(
    typeof window !== "undefined" ? window.location.pathname : "/expert",
);
let removeNavigateListener;

onMounted(() => {
    removeNavigateListener = Inertia.on("navigate", () => {
        currentUrl.value = window.location.pathname;
    });
});

onBeforeUnmount(() => {
    if (removeNavigateListener) removeNavigateListener();
});

const isActive = (route) => {
    if (route === "/expert") return currentUrl.value === "/expert";
    return currentUrl.value.startsWith(route);
};

const instance = getCurrentInstance();
const userData = computed(
    () => instance?.proxy?.$page?.props?.user_data || null,
);

const userInitials = computed(() => {
    const u = userData.value;
    if (!u) return "?";
    const first = (u.first_name || "").charAt(0).toUpperCase();
    const last = (u.last_name || "").charAt(0).toUpperCase();
    return first + last || "?";
});

const logout = () => {
    Inertia.post("/logout");
};
</script>
