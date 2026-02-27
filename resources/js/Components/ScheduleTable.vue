<template>
    <div class="relative space-y-4">
        <div class="absolute left-[31px] md:left-[39px] top-2 bottom-2 w-px bg-gray-100"></div>

        <div
            v-for="(event, index) in sortedEvents"
            :key="index"
            class="relative flex items-start group"
        >
            <div
                class="flex flex-col items-center justify-start min-w-[64px] md:min-w-[80px] pt-5"
            >
                <span
                    class="text-xs md:text-sm font-bold text-gray-900 bg-white z-10 px-1"
                >
                    {{ event.start_time }}
                </span>
                <span
                    class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter"
                >
                    {{ calculateEndTime(event.start_time, event.duration) }}
                </span>
            </div>
            <div class="relative mt-6 mr-3 md:mr-6 z-10">
                <div
                    class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-full bg-white border-2 border-black group-hover:bg-black transition-colors duration-300"
                ></div>
            </div>
            <div
                class="flex-1 bg-white border border-gray-100 p-4 md:p-6 rounded-xl md:rounded-[2rem] shadow-sm hover:border-gray-200 transition-all duration-300 mb-2"
            >
                <div
                    class="flex flex-col md:flex-row md:items-center justify-between gap-3 md:gap-4"
                >
                    <div class="space-y-1">
                        <h3
                            class="text-sm md:text-lg font-bold text-[#1a1a1a] leading-tight"
                        >
                            {{ event.thesis_title }}
                        </h3>
                        <div class="flex items-center text-gray-500">
                            <div
                                class="w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center mr-2"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-3 w-3 text-gray-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <span class="text-xs md:text-sm font-semibold">
                                {{ event.user.last_name }}
                                {{ event.user.first_name }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span
                            class="px-3 md:px-4 py-1 md:py-1.5 bg-gray-50 text-gray-400 text-[10px] font-bold uppercase rounded-full tracking-widest"
                        >
                            {{ event.duration }} мин
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ScheduleTable",
    props: {
        events: Array,
    },
    methods: {
        calculateEndTime(startTime, duration) {
            if (!startTime || !duration) return null;
            const [hours, minutes] = startTime.split(":").map(Number);
            const date = new Date();
            date.setHours(hours, minutes, 0, 0);
            const endDate = new Date(date.getTime() + duration * 60000);
            return `${endDate.getHours().toString().padStart(2, "0")}:${endDate.getMinutes().toString().padStart(2, "0")}`;
        },
    },
    computed: {
        sortedEvents() {
            return [...this.events].sort((a, b) =>
                a.start_time.localeCompare(b.start_time),
            );
        },
    },
};
</script>
