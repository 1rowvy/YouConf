<template>
    <div class="flex flex-col space-y-2 text-center mb-4">
        <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
            Создать аккаунт
        </h1>
        <p class="text-sm text-gray-500">Введите ваши данные для регистрации</p>
    </div>

    <div class="grid gap-6">
        <form @submit.prevent="submit" class="space-y-4">
            <div class="grid gap-2">
                <div class="grid gap-1">
                    <label class="sr-only" for="last_name">Фамилия</label>
                    <input
                        id="last_name"
                        v-model="form.last_name"
                        placeholder="Фамилия"
                        type="text"
                        required
                        class="flex h-10 w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        :class="{ 'border-red-500': errors.last_name }"
                    />
                    <p
                        v-if="errors.last_name"
                        class="text-[0.8rem] font-medium text-red-500"
                    >
                        {{ errors.last_name[0] }}
                    </p>
                </div>

                <div class="grid gap-1">
                    <label class="sr-only" for="first_name">Имя</label>
                    <input
                        id="first_name"
                        v-model="form.first_name"
                        placeholder="Имя"
                        type="text"
                        required
                        class="flex h-10 w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        :class="{ 'border-red-500': errors.first_name }"
                    />
                    <p
                        v-if="errors.first_name"
                        class="text-[0.8rem] font-medium text-red-500"
                    >
                        {{ errors.first_name[0] }}
                    </p>
                </div>

                <div class="grid gap-1">
                    <label class="sr-only" for="patronymic">Отчество</label>
                    <input
                        id="patronymic"
                        v-model="form.patronymic"
                        placeholder="Отчество (при наличии)"
                        type="text"
                        class="flex h-10 w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        :class="{ 'border-red-500': errors.patronymic }"
                    />
                    <p
                        v-if="errors.patronymic"
                        class="text-[0.8rem] font-medium text-red-500"
                    >
                        {{ errors.patronymic[0] }}
                    </p>
                </div>

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
                        :class="{ 'border-red-500': errors.password }"
                    />
                    <p
                        v-if="errors.password"
                        class="text-[0.8rem] font-medium text-red-500"
                    >
                        {{ errors.password[0] }}
                    </p>
                </div>

                <div class="grid gap-1">
                    <label class="sr-only" for="password_confirmation"
                        >Подтвердите пароль</label
                    >
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        placeholder="Подтвердите пароль"
                        type="password"
                        required
                        class="flex h-10 w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                </div>
            </div>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-black text-white hover:bg-black/90 h-10 py-2 px-4 w-full"
                :disabled="form.processing"
            >
                <span v-if="form.processing">Регистрация...</span>
                <span v-else>Зарегистрироваться</span>
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
                href="/login"
                class="text-sm font-medium hover:underline underline-offset-4"
            >
                Уже есть аккаунт? Войдите
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
            first_name: "",
            last_name: "",
            patronymic: "",
            email: "",
            password: "",
            password_confirmation: "",
        });

        const submit = () => {
            Inertia.post("/register", form, {
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
