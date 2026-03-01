<template>
  <div class="flex overflow-x-auto gap-2 mb-4 pb-2 scrollbar-hide">
    <button
      v-for="(date, index) in dateKeys"
      :key="index"
      @click="selectDate(date)"
      class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-all"
      :class="selectedDate === date
        ? 'bg-black text-white'
        : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
    >
      {{ date }}
    </button>
  </div>
</template>

<script>
export default {
  props: {
    dateKeys: Array,
    selectedDate: String,
  },
  methods: {
    selectDate(date) {
      this.$emit('date-selected', date)
    },
  },
}
</script>
