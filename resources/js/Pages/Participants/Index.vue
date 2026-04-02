<template>
  <div class="max-w-7xl mx-auto py-10 px-4">
    <div class="mb-8">
      <h1 class="text-2xl md:text-4xl font-extrabold text-[#1a1a1a] mb-6">Участники</h1>

      <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <label for="sectionFilter" class="text-sm font-bold text-gray-700">
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
    </div>

    <div v-if="filteredSections.length === 0" class="text-center py-20 bg-gray-50 rounded-xl">
      <p class="text-gray-500 text-lg">Нет участников.</p>
    </div>

    <div v-else class="space-y-8">
      <div v-for="section in filteredSections" :key="section.id">
        <h2 class="text-lg font-bold text-gray-800 mb-3">{{ section.name }}</h2>

        <div v-if="section.users.length === 0" class="text-sm text-gray-400 py-4 px-6 bg-gray-50 rounded-xl">
          Нет зарегистрированных участников.
        </div>

        <template v-else>
          <!-- MOBILE VIEW -->
          <div class="space-y-3 md:hidden">
            <div
              v-for="user in section.users"
              :key="'m-' + user.id"
              class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm"
            >
              <p class="text-base font-bold text-gray-900">
                {{ user.last_name }} {{ user.first_name }}
              </p>
              <p v-if="user.email" class="text-sm text-gray-500 mt-1">{{ user.email }}</p>
              <div class="mt-3 pt-3 border-t border-gray-100 space-y-1">
                <p v-if="user.degree_type" class="text-sm text-gray-600">
                  <span class="font-semibold">Уровень:</span> {{ degreeLabel(user.degree_type) }}
                </p>
                <p v-if="user.course" class="text-sm text-gray-600">
                  <span class="font-semibold">Курс:</span> {{ user.course }}
                </p>
                <p v-if="user.group_number" class="text-sm text-gray-600">
                  <span class="font-semibold">Группа:</span> {{ user.group_number }}
                </p>
                <p v-if="user.topic" class="text-sm text-gray-600">
                  <span class="font-semibold">Тема:</span> {{ user.topic }}
                </p>
                <p v-if="user.supervisor" class="text-sm text-gray-600">
                  <span class="font-semibold">Руководитель:</span> {{ user.supervisor }}
                </p>
                <p v-if="user.co_author" class="text-sm text-gray-600">
                  <span class="font-semibold">Соавтор:</span> {{ user.co_author }}
                </p>
              </div>
            </div>
          </div>

          <!-- DESKTOP VIEW -->
          <div class="hidden md:block bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <table class="min-w-full">
              <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                  <th
                    @click="toggleSort('last_name')"
                    class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                  >
                    <div class="flex items-center gap-2">Фамилия <span class="text-gray-400">↕</span></div>
                  </th>
                  <th
                    @click="toggleSort('first_name')"
                    class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                  >
                    <div class="flex items-center gap-2">Имя <span class="text-gray-400">↕</span></div>
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Уровень</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Курс</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Группа</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Тема</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Руководитель</th>
                  <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Соавтор</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr
                  v-for="user in sortedUsers(section.users)"
                  :key="user.id"
                  class="hover:bg-gray-50 transition-colors duration-150"
                >
                  <td class="px-6 py-4 font-semibold text-gray-900">{{ user.last_name }}</td>
                  <td class="px-6 py-4 text-sm text-gray-700">{{ user.first_name }}</td>
                  <td class="px-6 py-4 text-sm text-gray-500">{{ user.email || '—' }}</td>
                  <td class="px-6 py-4 text-sm text-gray-600">{{ degreeLabel(user.degree_type) }}</td>
                  <td class="px-6 py-4 text-sm text-gray-600">{{ user.course || '—' }}</td>
                  <td class="px-6 py-4 text-sm text-gray-600">{{ user.group_number || '—' }}</td>
                  <td class="px-6 py-4 text-sm text-gray-600">{{ user.topic || '—' }}</td>
                  <td class="px-6 py-4 text-sm text-gray-600">{{ user.supervisor || '—' }}</td>
                  <td class="px-6 py-4 text-sm text-gray-600">{{ user.co_author || '—' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    sections: Array,
  },
  data() {
    return {
      selectedSection: '',
      sortBy: 'last_name',
      sortOrder: 'asc',
    }
  },
  computed: {
    filteredSections() {
      if (this.selectedSection === '') {
        return this.sections
      }
      return this.sections.filter((s) => s.id === this.selectedSection)
    },
  },
  methods: {
    degreeLabel(val) {
      if (val === 'bachelor') return 'Бакалавр'
      if (val === 'magistrant') return 'Магистрант'
      return '—'
    },
    toggleSort(field) {
      if (this.sortBy === field) {
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc'
      } else {
        this.sortBy = field
        this.sortOrder = 'asc'
      }
    },
    sortedUsers(users) {
      return [...users].sort((a, b) => {
        const va = (a[this.sortBy] || '').toLowerCase()
        const vb = (b[this.sortBy] || '').toLowerCase()
        return this.sortOrder === 'asc' ? va.localeCompare(vb) : vb.localeCompare(va)
      })
    },
  },
}
</script>
