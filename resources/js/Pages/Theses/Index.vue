<template>
  <div class="max-w-7xl mx-auto py-10 px-4">
    <div class="mb-8">
      <h1 class="text-2xl md:text-4xl font-extrabold text-[#1a1a1a] mb-2">Принятые тезисы</h1>
      <p class="text-sm text-gray-400">Список тезисов, принятых к участию в конференции</p>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
      <label for="sectionFilter" class="text-sm font-bold text-gray-700 whitespace-nowrap">
        Фильтр по секции:
      </label>
      <select
        v-model="selectedSection"
        id="sectionFilter"
        class="px-4 py-2 border border-gray-200 rounded-xl bg-white hover:border-gray-300 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold"
      >
        <option value="">Все секции</option>
        <option
          v-for="section in sections"
          :key="section.id"
          :value="section.id"
        >
          {{ section.name }}
        </option>
      </select>
    </div>

    <ThesesTable
      :theses="filteredTheses"
      :statuses="[]"
      :role="$page.props.role"
      @sort="toggleSort"
    />
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import ThesesTable from '@/Components/ThesesTable.vue'

export default {
  props: {
    theses: Array,
    sections: Array,
  },
  components: {
    Link,
    ThesesTable,
  },
  data() {
    return {
      selectedSection: '',
      sortBy: 'created_at',
      sortOrder: 'desc',
    }
  },
  computed: {
    filteredTheses() {
      let result = this.selectedSection
        ? this.theses.filter((t) => t.section_id === this.selectedSection)
        : this.theses

      return [...result].sort((a, b) => {
        let valueA = a[this.sortBy]
        let valueB = b[this.sortBy]

        if (this.sortBy === 'title') {
          return this.sortOrder === 'asc'
            ? valueA.localeCompare(valueB)
            : valueB.localeCompare(valueA)
        }

        if (this.sortBy === 'section.name') {
          valueA = a.section.name
          valueB = b.section.name
          return this.sortOrder === 'asc'
            ? valueA.localeCompare(valueB)
            : valueB.localeCompare(valueA)
        }

        if (this.sortBy === 'created_at') {
          valueA = new Date(valueA)
          valueB = new Date(valueB)
        }

        return this.sortOrder === 'asc' ? valueA - valueB : valueB - valueA
      })
    },
  },
  methods: {
    toggleSort(field) {
      if (this.sortBy === field) {
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc'
      } else {
        this.sortBy = field
        this.sortOrder = 'asc'
      }
    },
  },
}
</script>
