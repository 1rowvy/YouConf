<template>
    <div class="py-8">
        <div class="mb-5">
            <h1 class="text-2xl md:text-4xl font-extrabold text-[#1a1a1a]">
                Секции
            </h1>
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
                    <div class="mt-3 space-y-1">
                        <p
                            v-if="section.start_date || section.end_date"
                            class="text-xs text-gray-400"
                        >
                            <span class="font-semibold text-gray-500"
                                >Даты:</span
                            >
                            {{ section.start_date
                            }}{{
                                section.end_date &&
                                section.end_date !== section.start_date
                                    ? " — " + section.end_date
                                    : ""
                            }}
                        </p>
                        <p v-if="section.chairs" class="text-xs text-gray-400">
                            <span class="font-semibold text-gray-500"
                                >Председатели:</span
                            >
                            {{ section.chairs }}
                        </p>
                        <p
                            v-if="section.location_name"
                            class="text-xs text-gray-400"
                        >
                            <span class="font-semibold text-gray-500"
                                >Аудитория:</span
                            >
                            {{ section.location_name }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 space-y-3">
                    <button
                        v-if="section.can_registration"
                        @click="handleRegisterClick(section)"
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

        <div
            v-if="modalSectionId !== null"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4 py-4"
            @click.self="closeModal"
        >
            <div
                class="bg-white rounded-2xl shadow-xl w-full max-w-lg flex flex-col max-h-[85vh] sm:max-h-[90vh]"
            >
                <h2
                    class="text-xl font-extrabold text-gray-900 px-8 pt-8 pb-4 shrink-0"
                >
                    Регистрация на секцию
                </h2>

                <form
                    @submit.prevent="submitRegistration"
                    class="flex flex-col flex-1 overflow-hidden min-h-0"
                >
                    <div class="space-y-4 overflow-y-auto px-8 flex-1">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 mb-1"
                                    >Уровень обучения<span
                                        class="text-red-500 ml-0.5"
                                        >*</span
                                    ></label
                                >
                                <select
                                    v-model="form.degree_type"
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                                >
                                    <option value="bachelor">
                                        Бакалавриат
                                    </option>
                                    <option value="magistrant">
                                        Магистратура
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 mb-1"
                                    >Курс<span class="text-red-500 ml-0.5"
                                        >*</span
                                    ></label
                                >
                                <input
                                    v-model="form.course"
                                    type="number"
                                    min="1"
                                    max="5"
                                    placeholder="1–5"
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 mb-1"
                                >Номер телефона<span class="text-red-500 ml-0.5"
                                    >*</span
                                ></label
                            >
                            <input
                                v-model="form.phone_number"
                                type="text"
                                placeholder="+79999999999"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 mb-1"
                                >Номер группы<span class="text-red-500 ml-0.5"
                                    >*</span
                                ></label
                            >
                            <input
                                v-model="form.group_number"
                                type="text"
                                placeholder="Например: 02121-ДБ"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 mb-1"
                                >Тема доклада (предварительная тема)<span
                                    class="text-red-500 ml-0.5"
                                    >*</span
                                ></label
                            >
                            <input
                                v-model="form.topic"
                                type="text"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 mb-1"
                                >Описание</label
                            >
                            <textarea
                                v-model="form.description"
                                rows="3"
                                placeholder="Описание вашего доклада"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            ></textarea>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 mb-1"
                                >Руководитель (нужно указать учёную степень,
                                учёное звание и ФИО руководителя)<span
                                    class="text-red-500 ml-0.5"
                                    >*</span
                                ></label
                            >
                            <input
                                v-model="form.supervisor"
                                type="text"
                                placeholder="Например: к.ф.-м.н. доцент Иванов И.И."
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div class="pb-2">
                            <label
                                class="block text-sm font-bold text-gray-700 mb-1"
                                >Соавторы</label
                            >
                            <p class="text-sm text-gray-500 mb-1">
                                Если ваш доклад делается в соавторстве, то
                                обязательно укажите здесь ФИО соавторов, номер
                                группы, контактный телефон и электронную почту.
                            </p>
                            <textarea
                                v-model="form.co_author"
                                rows="3"
                                placeholder="Указать сооавторов необходимо по одному на строку"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            ></textarea>
                        </div>
                    </div>
                    <div class="flex gap-3 px-8 pb-8 pt-4 shrink-0">
                        <button
                            type="button"
                            @click="closeModal"
                            class="flex-1 py-3 rounded-full border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all"
                        >
                            Отмена
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex-1 py-3 rounded-full bg-black text-white text-sm font-semibold hover:bg-gray-800 transition-all disabled:opacity-50"
                        >
                            Зарегистрироваться
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { useForm, Link } from "@inertiajs/inertia-vue3";
import { ref, watch } from "vue";

export default {
    components: {
        Link,
    },
    props: {
        sections: Array,
        user: Object,
    },
    setup(props) {
        const form = useForm({
            degree_type: "",
            course: "",
            group_number: "",
            topic: "",
            supervisor: "",
            co_author: "",
            description: "",
            phone_number: "",
        });

        const modalSectionId = ref(null);

        watch(modalSectionId, (val) => {
            document.body.style.overflow = val !== null ? 'hidden' : '';
        });

        const statusClasses = {
            planned: "bg-orange-50 text-orange-600",
            registration: "bg-blue-50 text-blue-600",
            thesis_submission: "bg-purple-50 text-purple-600",
            thesis_review: "bg-amber-50 text-amber-600",
            ongoing: "bg-emerald-50 text-emerald-700",
            finished: "bg-gray-100 text-gray-400",
        };

        const handleRegisterClick = (section) => {
            if (!props.user) {
                window.location.href = "/login";
                return;
            }
            if (section.is_joined) {
                form.post(`/sections/${section.id}/register`, {
                    preserveScroll: true,
                });
                return;
            }
            form.reset();
            modalSectionId.value = section.id;
        };

        const closeModal = () => {
            modalSectionId.value = null;
        };

        const submitRegistration = () => {
            form.post(`/sections/${modalSectionId.value}/register`, {
                preserveScroll: true,
                onSuccess: () => {
                    modalSectionId.value = null;
                },
            });
        };

        return {
            form,
            statusClasses,
            modalSectionId,
            handleRegisterClick,
            closeModal,
            submitRegistration,
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
