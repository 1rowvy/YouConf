<template>
    <div class="flex flex-row min-h-screen min-w-full bg-[#F9F9FB]">
        <Toaster position="bottom-right" richColors closeButton />
        <Sidebar />
        <main class="flex-1 ml-60 p-6 md:p-8">
            <div class="max-w-[1400px] mx-auto w-full">
                <slot></slot>
            </div>
        </main>
    </div>
</template>
<script>
import Sidebar from "../Components/Expert/Sidebar.vue";
import { Toaster, toast } from "vue-sonner";
import "vue-sonner/style.css";

export default {
    name: "ExpertLayout",
    components: {
        Toaster,
        Sidebar,
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
