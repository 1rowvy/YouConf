<template>
  <div class="max-w-4xl mx-auto py-10 px-4">
    <Link
      :href="`/user/${user_data.id}`"
      class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-black mb-8 transition-all group"
    >
      <span class="mr-2 transform group-hover:-translate-x-1 transition-all">&larr;</span>
      Назад к профилю
    </Link>

    <div class="bg-white border border-gray-100 p-8 md:p-12 rounded-[2.5rem] shadow-sm">
      <div class="mb-10">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-[#1a1a1a]">
          Редактировать профиль
        </h1>
      </div>

      <div v-if="hasErrors" class="mb-8 p-4 bg-red-50 rounded-xl">
        <ul class="space-y-1">
          <li v-for="(error, key) in formErrors" :key="key" class="text-sm text-red-600 font-medium">
            {{ getErrorMessage(error) }}
          </li>
        </ul>
      </div>

      <form @submit.prevent="updateProfile" class="space-y-6">
        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2">Имя</label>
          <input
            v-model="form.first_name"
            type="text"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-[#1a1a1a] placeholder-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
            required
          />
          <p v-if="formErrors.first_name" class="mt-2 text-sm text-red-500 font-medium">
            {{ getErrorMessage(formErrors.first_name) }}
          </p>
        </div>

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2">Фамилия</label>
          <input
            v-model="form.last_name"
            type="text"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-[#1a1a1a] placeholder-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
            required
          />
          <p v-if="formErrors.last_name" class="mt-2 text-sm text-red-500 font-medium">
            {{ getErrorMessage(formErrors.last_name) }}
          </p>
        </div>

        <div class="pt-8 border-t border-gray-100">
          <button
            type="submit"
            class="px-10 py-4 bg-black text-white rounded-full hover:bg-gray-800 shadow-lg shadow-black/10 transition-all duration-300 font-bold text-base"
          >
            Сохранить
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/inertia-vue3'

export default {
  components: { Link },
  props: {
    user_data: Object,
    errors: Object,
  },
  data() {
    return {
      form: {
        first_name: this.user_data.first_name,
        last_name: this.user_data.last_name,
      },
      formErrors: this.errors || {},
    }
  },
  computed: {
    hasErrors() {
      return Object.keys(this.formErrors).length > 0
    },
  },
  watch: {
    errors(newErrors) {
      this.formErrors = newErrors || {}
    },
  },
  methods: {
    updateProfile() {
      Inertia.put(`/user/${this.user_data.id}`, this.form, {
        onError: (errors) => {
          this.formErrors = errors
        },
      })
    },
    getErrorMessage(errorArray) {
      if (Array.isArray(errorArray)) {
        return errorArray.join(', ')
      }
      return errorArray
    },
  },
}
</script>
