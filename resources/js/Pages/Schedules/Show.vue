<template>
    <div class="py-10 max-w-7xl mx-auto px-4 font-sans">
        <div
            class="flex flex-col md:flex-row items-center justify-between gap-6 mb-12"
        >
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#1a1a1a]">
                Расписание конференции
            </h1>

            <div
                class="flex items-center bg-white border border-gray-100 p-2 rounded-full shadow-sm"
            >
                <button
                    @click="changeWeek(-1)"
                    class="p-3 hover:bg-gray-50 rounded-full transition-all"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </button>

                <div class="px-6 text-sm font-semibold text-gray-800">
                    {{ weekRangeText }}
                </div>

                <button
                    @click="changeWeek(1)"
                    class="p-3 hover:bg-gray-50 rounded-full transition-all"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <GeneralSheduleTable :days="weekDays" />
    </div>
</template>

<script>
import GeneralSheduleTable from "../../Components/GeneralSheduleTable.vue";

export default {
    components: { GeneralSheduleTable },
    props: {
        sections: Array,
    },
    data() {
        return {
            weekOffset: 0,
        };
    },
    computed: {
        weekDays() {
            const days = [];
            const now = new Date();
            const startOfWeek = new Date(now);
            const dayNum = startOfWeek.getDay() || 7;

            startOfWeek.setDate(
                now.getDate() - (dayNum - 1) + this.weekOffset * 7,
            );

            const weekdayNames = [
                "Понедельник",
                "Вторник",
                "Среда",
                "Четверг",
                "Пятница",
                "Суббота",
            ];

            for (let i = 0; i < 6; i++) {
                const currentDay = new Date(startOfWeek);
                currentDay.setDate(startOfWeek.getDate() + i);

                const currentTime = currentDay.getTime();
                const dateStr = currentDay.toISOString().split("T")[0];

                days.push({
                    date: dateStr,
                    displayDate: currentDay.toLocaleDateString("ru-RU", {
                        day: "numeric",
                        month: "short",
                    }),
                    weekdayName: weekdayNames[i],
                    sections: this.sections.filter((s) => {
                        const start = new Date(s.start_date);
                        const end = new Date(s.end_date);
                        return currentTime >= start && currentTime <= end;
                    }),
                });
            }
            return days;
        },
        weekRangeText() {
            if (this.weekDays.length === 0) return "";
            return `${this.weekDays[0].displayDate} — ${this.weekDays[5].displayDate}`;
        },
    },
    methods: {
        changeWeek(step) {
            this.weekOffset += step;
        },
    },
};
</script>
