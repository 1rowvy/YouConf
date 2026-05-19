<template>
  <div class="max-w-4xl mx-auto py-10 px-4">
    <Link
      :href="`/sections/${thesis.section.id}`"
      class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-black mb-8 transition-all group"
    >
      <span class="mr-2 transform group-hover:-translate-x-1 transition-all">&larr;</span>
      Назад к секции
    </Link>

    <div class="bg-white border border-gray-100 p-5 md:p-12 rounded-2xl md:rounded-[2.5rem] shadow-sm">
      <div class="mb-10">
        <div class="mb-6">
          <span
            :class="statusClasses[thesis.status_id]"
            class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase"
          >
            {{ thesis.status.name }}
          </span>
        </div>

        <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight text-[#1a1a1a]">
          {{ thesis.title }}
        </h1>
      </div>

      <div class="mb-12">
        <p class="text-base md:text-lg text-gray-500 font-medium leading-relaxed">
          {{ thesis.description }}
        </p>
      </div>

      <div class="space-y-3 mb-10">
        <div class="flex items-center gap-3">
          <span class="text-sm font-bold text-gray-400 w-24">Раздел</span>
          <span class="text-sm font-medium text-[#1a1a1a]">{{ thesis.section.name }}</span>
        </div>
        <div class="flex items-center gap-3">
          <span class="text-sm font-bold text-gray-400 w-24">Автор</span>
          <span class="text-sm font-medium text-[#1a1a1a]">{{ thesis.user.first_name }}</span>
        </div>
      </div>

      <div v-if="mediaFiles && mediaFiles.length" class="mb-10">
        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Файлы</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div
            v-for="file in mediaFiles"
            :key="file.id"
            class="group relative bg-gray-50 rounded-xl p-3 cursor-pointer hover:bg-gray-100 transition-all duration-200"
            @click="downloadFile(file)"
          >
            <div v-if="isImage(file)" class="aspect-square rounded-lg overflow-hidden mb-2">
              <img
                :src="file.original_url"
                alt=""
                class="w-full h-full object-cover"
              />
            </div>
            <div v-else class="aspect-square rounded-lg bg-gray-100 flex items-center justify-center mb-2">
              <span class="text-2xl" v-html="getFileIcon(file)"></span>
            </div>
            <p class="text-xs font-medium text-gray-500 truncate">{{ file.name }}</p>
          </div>
        </div>
      </div>

      <div v-if="thesis.review_comment && !isExpert" class="mb-8 p-5 bg-amber-50 border border-amber-100 rounded-xl">
        <p class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-2">Рецензия эксперта</p>
        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ thesis.review_comment }}</p>
      </div>

      <div v-if="isThesisOwner && thesis.status_id === 3" class="mb-6 flex items-center gap-2">
        <span class="text-sm text-gray-500">
          Использовано правок:
          <span :class="thesis.revision_count >= revision_limit ? 'text-red-500 font-bold' : 'font-semibold text-gray-700'">
            {{ thesis.revision_count }} / {{ revision_limit }}
          </span>
        </span>
        <span v-if="thesis.revision_count >= revision_limit" class="text-xs text-red-500 font-medium">
          — лимит исчерпан
        </span>
      </div>

      <div class="pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-center gap-4">
        <div v-if="isExpert" class="w-full sm:w-auto">
          <select
            v-model="thesis.status_id"
            @change="updateStatus(thesis.id, thesis.status_id)"
            class="w-full px-5 py-3 rounded-xl border border-gray-200 bg-white text-sm font-bold text-[#1a1a1a] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
          >
            <option
              v-for="status in statuses"
              :key="status.id"
              :value="status.id"
            >
              {{ status.name }}
            </option>
          </select>
        </div>

        <button
          @click="toggleChat"
          class="px-8 py-3 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition-all duration-300 font-bold text-sm inline-flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
          Чат
        </button>

        <Link
          v-if="isThesisOwner && !isDisabled"
          :href="`/theses/${thesis.id}/edit`"
          class="sm:ml-auto px-10 py-3 bg-black text-white rounded-full hover:bg-gray-800 shadow-lg shadow-black/10 transition-all duration-300 font-bold text-sm"
        >
          Редактировать
        </Link>
      </div>
    </div>

    <div
      v-if="isChatOpen"
      class="fixed inset-0 flex items-center justify-center z-50 bg-black/40 backdrop-blur-sm"
      @click.self="toggleChat"
    >
      <div class="bg-white rounded-[2rem] shadow-2xl w-11/12 md:w-1/2 max-h-[80vh] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-8 py-6 border-b border-gray-100">
          <h2 class="text-xl font-extrabold text-[#1a1a1a]">Обсуждение</h2>
          <button
            @click="toggleChat"
            class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-all duration-200"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
        <div class="flex-1 overflow-y-auto px-8 py-4">
          <Chat
            v-if="thesis.chat"
            :chat="thesis.chat"
            :messages="thesis.chat.messages"
            :thesis="thesis"
            :isActive="!isDisabled"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Chat from '@/Components/Chat.vue'
import { Link } from '@inertiajs/inertia-vue3'

export default {
  components: {
    Chat,
    Link,
  },
  props: {
    thesis: Object,
    messages: Array,
    mediaFiles: Array,
    statuses: Array,
    revision_limit: {
      type: Number,
      default: 1,
    },
  },
  data() {
    return {
      isChatOpen: false,
      statusClasses: {
        1: 'bg-orange-50 text-orange-600',
        2: 'bg-emerald-50 text-emerald-700',
        3: 'bg-blue-50 text-blue-600',
        4: 'bg-red-50 text-red-500',
      },
    }
  },
  computed: {
    isThesisOwner() {
      return this.$page.props.user_data.id === this.thesis.user.id
    },
    isDisabled() {
      if ([1, 2, 4].includes(this.thesis.status_id)) return true
      if (this.thesis.status_id === 3 && this.thesis.revision_count >= this.revision_limit) return true
      return false
    },
    isExpert() {
      return this.$page.props.role === 'expert'
    },
  },
  methods: {
    toggleChat() {
      this.isChatOpen = !this.isChatOpen
    },
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
    async updateStatus(thesisId, statusId) {
      try {
        await this.$inertia.post(`/theses/${thesisId}/status`, {
          status_id: statusId,
        })
        this.$emit('status-updated')
      } catch (error) {
        console.error(error)
      }
    },
  },
}
</script>
