<template>
    <div class="flex flex-col min-h-screem min-w-full">
        <Header />
        <div class="flex flex-row gap-1">
            <Sidebar />
            <main class="flex">
                <div class="">
                    <p>expert</p>
                </div>
            </main>
        </div>
    </div>
</template>
<script setup lang="ts">
import Header from "../Components/Expert/Header.vue";
import Sidebar from "../Components/Expert/Sidebar.vue";
</script>
