<template>
  <div class="max-w-4xl mx-auto py-10 px-4">
    <h2 class="text-2xl sm:text-3xl font-semibold mb-4">
      Расписание выступлений: {{ sectionName }}
    </h2>
    <div v-if="loading" class="text-center">Загрузка...</div>
    <div v-else>
      <div class="space-y-4">
        <div
          v-for="thesis in theses"
          :key="thesis.title"
          class="bg-white p-6 rounded-lg shadow-md"
        >
          <h3 class="text-xl font-bold text-blue-600">
            {{ thesis.title }}
          </h3>

          <p class="mt-2 text-gray-700">
            <span class="font-semibold">Участник:</span>
            {{ thesis.user.first_name }} {{ thesis.user.last_name }}
          </p>

          <p class="mt-2 text-gray-700">
            <span class="font-semibold">Описание:</span>
            {{ thesis.description }}
          </p>

          <p class="mt-2 text-gray-700">
            <span class="font-semibold">Время:</span>
            {{ thesis.start_time }} - {{ thesis.end_time }}
          </p>

          <p class="mt-2 text-gray-700">
            <span class="font-semibold">Место:</span>
            {{ thesis?.location?.name }}
          </p>

          <div
            v-for="file in thesis?.attachments"
            :key="file.id"
            class="flex items-center space-x-2 p-2 bg-gray-50 rounded-lg cursor-pointer"
            @click="downloadFile(file)"
          >
            <div
              v-if="isImage(file)"
              class="w-full h-40 flex items-center justify-center overflow-hidden mb-2"
            >
              <img
                :src="file.original_url"
                alt="File preview"
                class="max-w-full max-h-full object-contain rounded"
              />
            </div>
            <div
              v-else
              class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded"
            >
              <span class="text-gray-500" v-html="getFileIcon(file)"></span>
            </div>
            <div v-if="!isImage(file)" class="flex-1 text-left">
              <span class="font-semibold">{{ file.name }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    theses: {
      type: Array,
      required: true,
    },
    sectionName: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
    }
  },
  methods: {
    isImage(file) {
      return file.mime_type.startsWith('image/')
    },
    getFileIcon(file) {
      const ext = file.file_name?.split('.').pop()
      return (
        {
          pdf: '📄',
          doc: '📝',
          docx: '📝',
        }[ext] || '📁'
      )
    },
    downloadFile(file) {
      const link = document.createElement('a')
      link.href = file.original_url
      link.download = file.name
      link.target = '_blank'
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
    },
  },
}
</script>
