<template>
    <div class="max-w-4xl mx-auto px-4">
        <div
            class="bg-white border px-8 border-gray-100 rounded-2xl md:rounded-[2.5rem] shadow-sm"
        >
            <article
                class="max-w-none prose prose-slate md:prose-lg prose-sm prose-img:rounded-xl"
            >
                <div v-html="content" />
            </article>
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: String,
    content: String,
});
</script>
