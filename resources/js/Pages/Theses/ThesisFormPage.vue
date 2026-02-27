<template>
  <div class="max-w-4xl mx-auto py-10 px-4">
    <Link
      :href="backUrl"
      class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-black mb-8 transition-all group"
    >
      <span class="mr-2 transform group-hover:-translate-x-1 transition-all">&larr;</span>
      Назад
    </Link>

    <div class="bg-white border border-gray-100 p-5 md:p-12 rounded-2xl md:rounded-[2.5rem] shadow-sm">
      <div class="mb-10">
        <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight text-[#1a1a1a]">
          {{ isEditMode ? 'Редактировать тезис' : 'Создать тезис' }}
        </h1>
        <p v-if="sectionLabel" class="mt-3 text-lg text-gray-400 font-medium">
          {{ sectionLabel }}
        </p>
      </div>

      <ThesesForm :section-id="section_id" :thesis="thesis" />
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import ThesesForm from '@/Components/ThesesForm.vue'

export default {
  name: 'ThesisFormPage',
  components: {
    Link,
    ThesesForm,
  },
  props: {
    section_name: String,
    section_id: {
      type: Number,
      default: null,
    },
    thesis: Object,
  },
  computed: {
    isEditMode() {
      return !!this.thesis
    },
    sectionLabel() {
      return this.section_name || this.thesis?.section?.name || ''
    },
    backUrl() {
      if (this.thesis?.section?.id) {
        return `/sections/${this.thesis.section.id}`
      }
      if (this.section_id) {
        return `/sections/${this.section_id}`
      }
      return '/sections'
    },
  },
}
</script>
