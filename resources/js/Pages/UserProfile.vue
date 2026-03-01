<template>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <div
            class="bg-white border border-gray-100 p-5 md:p-12 rounded-2xl md:rounded-[2.5rem] shadow-sm mb-8"
        >
            <div class="mb-10">
                <h1
                    class="text-2xl md:text-4xl font-extrabold tracking-tight text-[#1a1a1a]"
                >
                    Информация о пользователе
                </h1>
            </div>

            <div class="space-y-4">
                <p class="text-base md:text-lg text-gray-500">
                    <strong class="font-bold text-gray-700">Имя:</strong>
                    {{ user_data.first_name }}
                </p>
                <p class="text-base md:text-lg text-gray-500">
                    <strong class="font-bold text-gray-700">Фамилия:</strong>
                    {{ user_data.last_name }}
                </p>
                <p
                    v-if="user_data.email"
                    class="text-base md:text-lg text-gray-500"
                >
                    <strong class="font-bold text-gray-700">Email:</strong>
                    {{ user_data.email }}
                </p>
            </div>

            <div class="pt-10 border-t border-gray-50">
                <Link
                    :disabled="isDisabled"
                    :href="`/user/${user_data.id}/edit`"
                    class="inline-block px-10 py-4 bg-black text-white rounded-full hover:bg-gray-800 shadow-lg shadow-black/10 transition-all duration-300 font-bold text-base"
                >
                    Редактировать профиль
                </Link>
            </div>
        </div>

        <ThesesTable
            v-if="$page.props?.role !== 'expert'"
            :theses="theses"
            :statuses="statuses"
            :role="$page.props?.role"
            @status-updated="handleStatusUpdated"
        />
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import ThesesTable from "@/Components/ThesesTable.vue";

const formatDate = (dateString, formatType) => {
    const date = new Date(dateString);
    if (formatType === "long") {
        return date.toLocaleDateString("ru-RU", {
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    } else if (formatType === "short") {
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const year = date.getFullYear();
        return `${day}.${month}.${year}`;
    }
    return dateString;
};

const UserInfo = {
    props: {
        label: String,
        value: String,
    },
    template: `
    <p class="text-gray-700">
      <strong class="font-semibold">{{ label }}:</strong>
      {{ value }}
    </p>
  `,
};

export default {
    name: "UserProfile",
    props: {
        user_data: Object,
        theses: Array,
    },
    components: {
        Link,
        UserInfo,
        ThesesTable,
    },
    methods: {
        formatDate,
    },
};
</script>
