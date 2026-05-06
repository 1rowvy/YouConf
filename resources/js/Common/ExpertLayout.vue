<template>
    <div class="flex flex-row min-h-screen min-w-full bg-[#F9F9FB]">
        <Sidebar />
        <main class="flex-1 ml-60 p-6 md:p-8">
            <div class="max-w-[1400px] mx-auto w-full">
                <slot></slot>
            </div>
        </main>
    </div>
</template>
<script setup lang="ts">
import Sidebar from "../Components/Expert/Sidebar.vue";
</script>
