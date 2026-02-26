<template>
  <form
    @submit.prevent="submit"
    :class="{ 'pointer-events-none opacity-50': isSubmitting }"
  >
    <div class="flex flex-col lg:flex-row gap-8">
      <div class="flex-1 space-y-6">
        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2">Название</label>
          <input
            v-model="form.title"
            type="text"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-[#1a1a1a] placeholder-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
            required
            placeholder="Введите название"
          />
        </div>

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2">Описание</label>
          <textarea
            v-model="form.description"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-[#1a1a1a] placeholder-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400 resize-none"
            required
            placeholder="Введите описание"
            rows="5"
          ></textarea>
        </div>

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2">Соавторы</label>
          <div class="space-y-3">
            <div
              v-for="(coAuthor, index) in form.co_authors"
              :key="index"
              class="flex items-center gap-3"
            >
              <input
                v-model="form.co_authors[index]"
                type="text"
                class="flex-1 px-4 py-3 rounded-xl border border-gray-200 bg-white text-[#1a1a1a] placeholder-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
                placeholder="Имя соавтора"
              />
              <button
                type="button"
                @click="removeCoAuthor(index)"
                class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-red-50 text-red-500 hover:bg-red-100 transition-all duration-200"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>
          </div>
          <button
            type="button"
            @click="addCoAuthor"
            class="mt-3 inline-flex items-center text-sm font-bold text-gray-400 hover:text-black transition-all duration-200"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Добавить соавтора
          </button>
        </div>
      </div>

      <div class="lg:w-80">
        <FileUpload
          :initialFiles="thesis?.media || []"
          :disabled="isSubmitting"
          @files-updated="handleFilesUpdate"
          @file-removed="handleFileRemove"
        />
      </div>
    </div>

    <div class="flex justify-end mt-10 pt-8 border-t border-gray-100">
      <button
        class="px-10 py-4 bg-black text-white rounded-full hover:bg-gray-800 shadow-lg shadow-black/10 transition-all duration-300 font-bold text-base"
        type="submit"
        :disabled="isSubmitting"
      >
        <span v-if="isSubmitting" class="inline-flex items-center">
          <svg
            class="animate-spin h-5 w-5 mr-3 text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
          </svg>
          Отправка...
        </span>
        <span v-else>{{ isEditMode ? 'Обновить' : 'Создать' }}</span>
      </button>
    </div>
  </form>
</template>

<script>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import FileUpload from '@/Components/FileUpload.vue'

export default {
  components: {
    FileUpload,
  },
  props: {
    thesis: Object,
    sectionId: Number,
  },
  data() {
    return {
      form: {
        title: this.thesis ? this.thesis.title : '',
        description: this.thesis ? this.thesis.description : '',
        co_authors:
          this.thesis && this.thesis.co_authors
            ? this.thesis.co_authors.split(',')
            : [''],
      },
      newFiles: [],
      isEditMode: !!this.thesis,
      isSubmitting: false,
    }
  },
  methods: {
    async submit() {
      this.isSubmitting = true

      const formData = new FormData()
      formData.append('title', this.form.title)
      formData.append('description', this.form.description)
      formData.append('co_authors', JSON.stringify(this.form.co_authors))

      this.newFiles.forEach((file) => {
        formData.append('attachments[]', file)
      })

      try {
        if (this.isEditMode) {
          await this.$inertia.post(`/theses/${this.thesis.id}/update`, formData)
        } else {
          await this.$inertia.post(`/theses/${this.sectionId}/apply`, formData)
        }
      } catch (error) {
        console.error(error)
      } finally {
        this.isSubmitting = false
      }
    },
    handleFilesUpdate(files) {
      this.newFiles = files.filter((file) => file instanceof File)
    },
    handleFileRemove(fileId) {
      this.$inertia
        .delete(`/theses/${this.thesis.id}/media/${fileId}`)
        .then(() => {})
        .catch((error) => {
          console.error(error)
        })
    },
    addCoAuthor() {
      this.form.co_authors.push('')
    },
    removeCoAuthor(index) {
      this.form.co_authors.splice(index, 1)
    },
  },
}
</script>
