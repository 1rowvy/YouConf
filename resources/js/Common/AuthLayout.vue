<template>
    <div class="min-h-screen flex flex-col bg-white">
        <header
            class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50 py-3"
        >
            <div
                class="container mx-auto px-6 flex justify-between items-center"
            >
                <Link
                    :href="'/'"
                    class="flex items-center space-x-3 hover:opacity-80 transition-opacity"
                >
                    <div>
                        <img src="@img/logo.jpg" alt="Logo" class="h-14 w-14" />
                    </div>
                    <span class="font-semibold text-lg md:flex hidden"
                        >Конференция ИМИТ ИГУ</span
                    >
                </Link>
            </div>
        </header>
        <main class="flex-grow flex items-center justify-center py-12 px-4">
            <div
                class="w-full max-w-[350px] animate-in fade-in slide-in-from-bottom-4 duration-700"
            >
                <slot />
            </div>
        </main>
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
    name: "AuthLayout",
    components: {
        Link,
    },
};
</script>
