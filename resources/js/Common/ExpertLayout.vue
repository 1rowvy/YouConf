<template>
    <div class="flex flex-row min-h-screem min-w-full">
        <Sidebar />
        <main class="flex ml-64 p-6 px-8">
            <div class="">
                <slot></slot>
            </div>
        </main>
    </div>
</template>
<script setup lang="ts">
import Sidebar from "../Components/Expert/Sidebar.vue";
</script>
