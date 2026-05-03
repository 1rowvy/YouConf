<template>
    <div class="flex flex-col space-y-2 text-center mb-4">
        <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
            Подтвердите вашу почту
        </h1>
        <p class="text-sm text-gray-500">
            Мы отправили письмо на
            <span class="font-medium text-gray-700">{{ email }}</span
            >. Перейдите по ссылке в письме, чтобы продолжить.
        </p>
    </div>

    <div class="grid gap-4">
        <div
            v-if="status === 'verification-link-sent'"
            class="text-sm text-center font-medium text-emerald-600"
        >
            Письмо отправлено повторно. Проверьте входящие.
        </div>

        <form @submit.prevent="resend">
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-black text-white hover:bg-black/90 h-10 py-2 px-4 w-full"
                :disabled="processing"
            >
                <span v-if="processing">Отправка...</span>
                <span v-else>Отправить письмо повторно</span>
            </button>
        </form>

        <div class="text-center">
            <Link
                href="/logout"
                method="post"
                as="button"
                class="text-sm font-medium text-gray-500 hover:underline underline-offset-4"
            >
                Выйти из аккаунта
            </Link>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";
import AuthLayout from "@/Common/AuthLayout.vue";

export default {
    props: {
        email: String,
        status: String,
    },
    components: {
        Link,
    },
    meta: {
        layout: AuthLayout,
    },
    setup() {
        const processing = ref(false);

        const resend = () => {
            processing.value = true;
            Inertia.post(
                "/email/verification-notification",
                {},
                {
                    onFinish: () => {
                        processing.value = false;
                    },
                },
            );
        };

        return { processing, resend };
    },
};
</script>
