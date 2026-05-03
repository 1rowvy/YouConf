<template>
    <div class="flex flex-col space-y-2 text-center mb-4">
        <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
            Вход в аккаунт
        </h1>
        <p class="text-sm text-gray-500">Введите вашу почту и пароль ниже</p>
    </div>

    <div class="grid gap-6">
        <div
            v-if="message"
            :class="status === 'success' ? 'text-emerald-600' : 'text-red-600'"
            class="text-sm text-center font-medium"
        >
            {{ message }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="grid gap-2">
                <div class="grid gap-1">
                    <label class="sr-only" for="email">Email</label>
                    <input
                        id="email"
                        v-model="form.email"
                        placeholder="name@example.com"
                        type="email"
                        required
                        class="flex h-10 w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        :class="{ 'border-red-500': errors.email }"
                    />
                    <p
                        v-if="errors.email"
                        class="text-[0.8rem] font-medium text-red-500"
                    >
                        {{ errors.email[0] }}
                    </p>
                </div>

                <div class="grid gap-1">
                    <label class="sr-only" for="password">Пароль</label>
                    <input
                        id="password"
                        v-model="form.password"
                        placeholder="Пароль"
                        type="password"
                        required
                        class="flex h-10 w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <p
                        v-if="errors.password"
                        class="text-[0.8rem] font-medium text-red-500"
                    >
                        {{ errors.password }}
                    </p>
                </div>
            </div>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-black text-white hover:bg-black/90 h-10 py-2 px-4 w-full"
                :disabled="form.processing"
            >
                <span v-if="form.processing">Вход...</span>
                <span v-else>Войти</span>
            </button>
        </form>

        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <span class="w-full border-t border-gray-200"></span>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-white px-2 text-gray-500">Или</span>
            </div>
        </div>

        <!-- <div class="grid gap-2">
            <VkAuthButton class="w-full" />
        </div> -->

        <div class="text-center">
            <Link
                href="/register"
                class="text-sm font-medium hover:underline underline-offset-4"
            >
                Нет аккаунта? Зарегистрируйтесь
            </Link>
        </div>
    </div>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import VkAuthButton from "@/Components/VkAuthButton.vue";
import { Link } from "@inertiajs/inertia-vue3";
import AuthLayout from "@/Common/AuthLayout.vue";

export default {
    props: {
        errors: Object,
        message: String,
        status: String,
        user_data: Object,
    },
    components: {
        VkAuthButton,
        Link,
    },
    meta: {
        layout: AuthLayout,
    },
    setup(props) {
        const form = useForm({
            email: "",
            password: "",
            remember: props.user_data?.remember || false,
        });

        const submit = () => {
            Inertia.post("/login", form, {
                onSuccess: (page) => {
                    if (!page.props.errors) form.reset();
                },
                onError: (errors) => form.setError(errors),
            });
        };

        return { form, submit };
    },
};
</script>
