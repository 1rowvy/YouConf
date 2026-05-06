<template>
    <div class="flex min-h-screen fixed">
        <aside
            :class="[
                'bg-white text-black transition-all duration-300 flex flex-col w-60 items-start',
            ]"
        >
            <div class="flex items-center min-h-[70px] gap-2 p-4">
                <h1 class="font-semibold text-lg">YouConf Expert</h1>
            </div>

            <nav class="flex-1 p-2 space-y-1 w-full">
                <a
                    v-for="item in menuItems"
                    :key="item.name"
                    :href="item.route"
                    class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition"
                >
                    <component :is="item.icon" :size="20" />
                    <span class="ml-3">{{ item.name }}</span>
                </a>
            </nav>
        </aside>
    </div>
</template>

<script setup>
import { House, Layers, FileText, Users } from "@lucide/vue";

const menuItems = [
    { name: "Главная", icon: House, route: "/expert" },
    { name: "Секции", icon: Layers, route: "/expert/sections" },
    { name: "Тезисы", icon: FileText, route: "/expert/theses" },
    { name: "Участники", icon: Users, route: "/expert/participants" },
];
</script>
