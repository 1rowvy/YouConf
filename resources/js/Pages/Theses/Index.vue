<template>
  <div class="max-w-7xl mx-auto py-10 px-4">
    <div class="mb-8">
      <h1 class="text-2xl md:text-4xl font-extrabold text-[#1a1a1a] mb-6">Тезисы</h1>

      <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <label for="statusFilter" class="text-sm font-bold text-gray-700">
          Фильтр по статусу:
        </label>
        <select
          v-model="selectedStatus"
          id="statusFilter"
          class="px-4 py-2 border border-gray-200 rounded-xl bg-white hover:border-gray-300 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold"
        >
          <option value="">Все статусы</option>
          <option
            v-for="status in statuses"
            :key="status.id"
            :value="status.id"
          >
            {{ status.name }}
          </option>
        </select>
      </div>
    </div>

    <ThesesTable
      :theses="filteredTheses"
      :statuses="statuses"
      :role="$page.props.role"
      @status-updated="handleStatusUpdated"
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
    statuses: Array,
  },
  components: {
    Link,
    ThesesTable,
  },
  data() {
    return {
      selectedStatus: '',
      sortBy: 'created_at',
      sortOrder: 'desc',
    }
  },
  computed: {
    filteredTheses() {
      let filtered = this.theses.filter((thesis) => {
        return (
          this.selectedStatus === '' || thesis.status_id === this.selectedStatus
        )
      })

      // Сортировка
      return filtered.sort((a, b) => {
        let valueA = a[this.sortBy]
        let valueB = b[this.sortBy]

        if (this.sortBy === 'title' || this.sortBy === 'section.name') {
          valueA = this.sortBy === 'section.name' ? a.section.name : valueA
          valueB = this.sortBy === 'section.name' ? b.section.name : valueB
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
    formatDate(date) {
      return new Date(date).toLocaleDateString() // Форматирование даты
    },
    async updateStatus(thesisId, statusId) {
      try {
        await this.$inertia.post(`/theses/${thesisId}/status`, {
          status_id: statusId,
        })
        this.$emit('status-updated')
      } catch (error) {
        console.error('Ошибка при обновлении статуса:', error)
      }
    },
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
