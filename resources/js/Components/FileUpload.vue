<template>
  <div>
    <label class="block text-sm font-bold text-gray-700 mb-2">Файлы</label>
    <div
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="handleDrop"
      @click="$refs.fileInput.click()"
      class="rounded-2xl border-2 border-dashed p-8 text-center cursor-pointer transition-all duration-200"
      :class="[
        disabled ? 'pointer-events-none opacity-50' : '',
        isDragging
          ? 'border-black bg-gray-50'
          : 'border-gray-200 hover:border-gray-400 hover:bg-gray-50'
      ]"
    >
      <div class="flex flex-col items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        <p class="text-sm text-gray-400 font-medium">
          Перетащите файлы или нажмите для выбора
        </p>
      </div>
      <input
        type="file"
        @change="handleFileUpload"
        multiple
        class="hidden"
        ref="fileInput"
        accept="image/*, .pdf, .doc, .docx"
      />
    </div>

    <div v-if="allFiles.length" class="mt-4 space-y-3">
      <div
        v-for="(file, index) in allFiles"
        :key="file.id || file.name"
        class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl group"
      >
        <div v-if="isImage(file)" class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0">
          <img
            :src="getPreviewUrl(file)"
            class="w-full h-full object-cover"
          />
        </div>
        <div v-else class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
          <span class="text-lg" v-html="getFileIcon(file)"></span>
        </div>

        <span class="flex-1 text-sm font-medium text-gray-700 truncate">
          {{ file.name || file.file_name }}
        </span>

        <button
          @click.stop="removeFile(index)"
          class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all duration-200 opacity-0 group-hover:opacity-100"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FileUpload',
  props: {
    initialFiles: { type: Array, default: () => [] },
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      newFiles: [],
      isDragging: false,
    }
  },

  computed: {
    allFiles() {
      return [...this.initialFiles, ...this.newFiles]
    },
  },

  methods: {
    isImage(file) {
      if (file.type) return file.type.startsWith('image/')
      if (file.mime_type) return file.mime_type.startsWith('image/')
      return false
    },

    getPreviewUrl(file) {
      if (file instanceof File) {
        return URL.createObjectURL(file)
      }
      return file.original_url
    },

    getFileIcon(file) {
      const ext =
        file.name?.split('.').pop() || file.file_name?.split('.').pop()
      return (
        {
          pdf: '📄',
          doc: '📝',
          docx: '📝',
        }[ext] || '📁'
      )
    },

    handleFileUpload(e) {
      this.newFiles = [...this.newFiles, ...Array.from(e.target.files)]
      this.$emit('files-updated', this.allFiles)
    },

    handleDrop(e) {
      this.isDragging = false
      const files = Array.from(e.dataTransfer.files)
      this.newFiles = [...this.newFiles, ...files]
      this.$emit('files-updated', this.allFiles)
    },

    removeFile(index) {
      const fileToRemove = this.allFiles[index]
      if (fileToRemove.id) {
        this.$emit('file-removed', fileToRemove.id)
      }
      this.newFiles.splice(index, 1)
      this.$emit('files-updated', this.allFiles)
    },
  },
}
</script>
