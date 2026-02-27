<template>
    <div
        class="flex flex-col min-h-screen bg-[#F9F9FB] text-[#1a1a1a] font-sans"
    >
        <Toaster position="bottom-right" richColors closeButton />

        <header class="bg-white border-b border-gray-100 py-3">
            <div
                class="container mx-auto px-6 flex justify-between items-center"
            >
                <div class="flex items-center space-x-3">
                    <div class="bg-black p-1.5 rounded-lg">
                        <img src="@img/logo.png" alt="Logo" class="h-6 w-6" />
                    </div>
                    <span class="text-xl font-bold">Удивительный Мир</span>
                </div>

                <!-- Desktop: profile/login + notifications -->
                <div class="hidden md:flex items-center space-x-4">
                    <NotificationsDropdown v-if="$page.props?.user_data" />

                    <template v-if="$page.props?.user_data">
                        <Link
                            :href="'/user/' + $page.props.user_data.id"
                            class="flex items-center bg-white border border-gray-200 py-1.5 pl-1.5 pr-4 rounded-full hover:shadow-md transition-all"
                        >
                            <!-- <img
                                :src="$page.props?.user_data?.avatar"
                                alt="Avatar"
                                class="h-7 w-7 rounded-full object-cover mr-2"
                            /> -->
                            <span class="text-sm font-semibold">{{
                                $page.props?.user_data?.first_name
                            }}</span>
                        </Link>
                    </template>
                    <template v-else>
                        <a
                            href="/login"
                            class="bg-black text-white text-sm font-medium px-6 py-2.5 rounded-full hover:bg-gray-800 transition-all shadow-sm"
                        >
                            Войти
                        </a>
                    </template>
                </div>

                <!-- Mobile: burger button -->
                <button
                    class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    aria-label="Toggle menu"
                >
                    <!-- Hamburger icon -->
                    <svg
                        v-if="!mobileMenuOpen"
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Close icon -->
                    <svg
                        v-else
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile dropdown menu -->
            <transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="max-h-0 opacity-0"
                enter-to-class="max-h-[500px] opacity-100"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="max-h-[500px] opacity-100"
                leave-to-class="max-h-0 opacity-0"
            >
                <div
                    v-show="mobileMenuOpen"
                    class="md:hidden overflow-hidden border-t border-gray-100"
                >
                    <div class="container mx-auto px-6 py-4 space-y-1">
                        <!-- Navigation links -->
                        <template v-if="$page.props?.allPages">
                            <a
                                v-for="page in $page.props.allPages"
                                :key="'mobile-' + page.slug"
                                :href="`/${page.slug}`"
                                class="block px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-black hover:bg-gray-100 rounded-lg transition-all"
                            >
                                {{ page.title }}
                            </a>
                        </template>
                        <a
                            href="/sections"
                            class="block px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-black hover:bg-gray-100 rounded-lg transition-all"
                        >
                            Секции
                        </a>
                        <a
                            href="/schedules"
                            class="block px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-black hover:bg-gray-100 rounded-lg transition-all"
                        >
                            Раписание
                        </a>
                        <a
                            v-if="$page.props?.role === 'expert'"
                            href="/theses"
                            class="block px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-black hover:bg-gray-100 rounded-lg transition-all"
                        >
                            Тезисы
                        </a>

                        <!-- Divider -->
                        <hr class="border-gray-100 !my-3" />

                        <!-- User section -->
                        <div class="flex items-center justify-between gap-3">
                            <template v-if="$page.props?.user_data">
                                <Link
                                    :href="'/user/' + $page.props.user_data.id"
                                    class="flex items-center bg-white border border-gray-200 py-1.5 pl-1.5 pr-4 rounded-full hover:shadow-md transition-all"
                                >
                                    <span class="text-sm font-semibold">{{
                                        $page.props?.user_data?.first_name
                                    }}</span>
                                </Link>
                                <Link
                                    href="/notifications"
                                    class="relative p-2 rounded-full hover:bg-gray-100 transition-all"
                                >
                                    <svg class="w-6 h-6 text-black" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5.365V3m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175 0 .593 0 1.292-.538 1.292H5.538C5 18 5 17.301 5 16.708c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 12 5.365ZM8.733 18c.094.852.306 1.54.944 2.112a3.48 3.48 0 0 0 4.646 0c.638-.572 1.236-1.26 1.33-2.112h-6.92Z" />
                                    </svg>
                                    <span
                                        v-if="$page.props.unreadNotificationsCount > 0"
                                        class="absolute top-0 right-0 bg-red-500 text-white text-[10px] rounded-full h-4 w-4 flex items-center justify-center"
                                    >
                                        {{ $page.props.unreadNotificationsCount }}
                                    </span>
                                </Link>
                            </template>
                            <template v-else>
                                <a
                                    href="/login"
                                    class="bg-black text-white text-sm font-medium px-6 py-2.5 rounded-full hover:bg-gray-800 transition-all shadow-sm w-full text-center"
                                >
                                    Войти
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </transition>
        </header>

        <!-- Desktop nav -->
        <nav
            class="hidden md:block sticky top-0 bg-white/80 backdrop-blur-md border-b border-gray-100 z-[50]"
        >
            <div
                class="container mx-auto flex justify-center items-center space-x-1 p-2"
            >
                <template v-if="$page.props?.allPages">
                    <template
                        v-for="page in $page.props.allPages"
                        :key="page.slug"
                    >
                        <a
                            :href="`/${page.slug}`"
                            class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-black hover:bg-gray-100 rounded-full transition-all"
                        >
                            {{ page.title }}
                        </a>
                    </template>
                </template>
                <a
                    href="/sections"
                    class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-black hover:bg-gray-100 rounded-full transition-all"
                    >Секции</a
                >
                <a
                    href="/schedules"
                    class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-black hover:bg-gray-100 rounded-full transition-all"
                >
                    Раписание
                </a>
                <a
                    v-if="$page.props?.role === 'expert'"
                    href="/theses"
                    class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-black hover:bg-gray-100 rounded-full transition-all"
                >
                    Тезисы
                </a>
            </div>
        </nav>

        <main class="flex-grow container mx-auto px-4 py-4 md:p-6">
            <div class="max-w-7xl mx-auto">
                <slot></slot>
            </div>
        </main>

        <footer class="bg-white border-t border-gray-100 py-10">
            <div
                class="container mx-auto px-6 text-center text-gray-400 text-sm font-medium"
            ></div>
        </footer>
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import NotificationsDropdown from "@/Components/NotificationsDropdown.vue";
import { Toaster, toast } from "vue-sonner";
import "vue-sonner/style.css";

export default {
    name: "DefaultLayout",
    components: {
        Link,
        NotificationsDropdown,
        Toaster,
    },
    props: {
        sections: Array,
    },
    data() {
        return {
            mobileMenuOpen: false,
        };
    },
    computed: {
        userProfileLink() {
            return `/user/${this.$page.props?.user_data?.id}`;
        },
    },
    methods: {
        handleFlashMessages(flash) {
            if (flash?.success) {
                toast.success(flash.success);
            }
            if (flash?.error) {
                toast.error(flash.error);
            }
        },
        switchUser(userId) {
            this.$inertia.visit(`/switch-user/${userId}`);
        },
    },
    watch: {
        "$page.props.flash": {
            handler(newFlash) {
                this.handleFlashMessages(newFlash);
            },
            deep: true,
        },
    },
    mounted() {
        this.handleFlashMessages(this.$page.props.flash);
    },
};
</script>
