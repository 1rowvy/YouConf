<template>
    <div class="py-8">
        <div class="mb-5">
            <h1 class="text-2xl md:text-4xl font-extrabold text-[#1a1a1a]">Секции</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
                v-for="section in sections"
                :key="section.id"
                class="group bg-white border border-gray-100 p-6 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full"
            >
                <div class="mb-4">
                    <span
                        :class="statusClasses[section.status]"
                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase"
                    >
                        {{ section.status_label }}
                    </span>
                </div>

                <div class="flex-1">
                    <h3
                        class="text-xl font-bold mb-3 group-hover:text-blue-600 transition-all"
                    >
                        {{ section.name }}
                    </h3>
                    <p class="text-gray-500 text-sm">
                        {{ section.description }}
                    </p>
                </div>

                <div class="mt-8 space-y-3">
                    <button
                        v-if="section.can_registration"
                        @click="toggleRegistration(section.id)"
                        :class="
                            section.is_joined
                                ? 'bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-600'
                                : 'bg-black text-white hover:bg-gray-800 shadow-sm'
                        "
                        class="w-full py-3 rounded-full transition-all font-semibold text-sm"
                    >
                        {{
                            section.is_joined
                                ? "Отменить участие"
                                : "Участвовать в секции"
                        }}
                    </button>

                    <div class="flex items-center justify-between pt-2">
                        <Link
                            :href="`/sections/${section.id}`"
                            class="text-sm font-bold text-gray-400 hover:text-black transition-all"
                        >
                            Подробнее →
                        </Link>

                        <Link
                            v-if="
                                section.can_create_thesis && section.is_joined
                            "
                            :href="`/theses/create/${section.id}`"
                            class="inline-flex items-center bg-blue-50 text-blue-600 px-4 py-2 rounded-full text-xs font-bold hover:bg-blue-100 transition-all"
                        >
                            Подать тезис
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useForm, Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        Link,
    },
    props: {
        sections: Array,
        user: Object,
    },
    setup(props) {
        const form = useForm({});

        const statusClasses = {
            planned: "bg-orange-50 text-orange-600",
            registration: "bg-blue-50 text-blue-600",
            ongoing: "bg-emerald-50 text-emerald-700",
            finished: "bg-gray-100 text-gray-400",
        };

        const toggleRegistration = (id) => {
            if (!props.user) {
                window.location.href = "/login";
                return;
            }
            form.post(`/sections/${id}/register`, {
                preserveScroll: true,
            });
        };

        return {
            statusClasses,
            toggleRegistration,
        };
    },
};
</script>

<style scoped>
p {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
}
</style>
