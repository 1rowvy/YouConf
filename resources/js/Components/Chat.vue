<template>
  <div>
    <div class="overflow-y-auto max-h-[50vh] mb-6 space-y-6">
      <div v-if="messages.length === 0" class="text-center py-12">
        <p class="text-gray-400 font-medium">Сообщений пока нет</p>
      </div>

      <div v-for="(group, index) in groupedMessages" :key="index">
        <div class="flex items-center gap-4 mb-4">
          <div class="flex-1 h-px bg-gray-100"></div>
          <span class="text-xs font-bold text-gray-300 uppercase">{{ formatDate(group.date) }}</span>
          <div class="flex-1 h-px bg-gray-100"></div>
        </div>

        <div class="space-y-3">
          <div v-for="message in group.messages" :key="message.id">
            <div
              v-if="message.user.id === $page.props.user_data.id"
              class="flex justify-end"
            >
              <div class="bg-black text-white rounded-2xl rounded-br-md px-4 py-3 max-w-[80%] sm:max-w-md">
                <p class="text-sm">{{ message.message }}</p>
                <span class="text-[10px] text-gray-400 mt-1 block">
                  {{ formatTime(message.created_at) }}
                </span>
              </div>
            </div>

            <div v-else class="flex justify-start">
              <div class="bg-gray-100 text-[#1a1a1a] rounded-2xl rounded-bl-md px-4 py-3 max-w-[80%] sm:max-w-md">
                <p class="text-xs font-bold text-gray-400 mb-1">{{ message.user.first_name }}</p>
                <p class="text-sm">{{ message.message }}</p>
                <span class="text-[10px] text-gray-400 mt-1 block">
                  {{ formatTime(message.created_at) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="isActive" class="flex items-center gap-3">
      <input
        v-model="newMessage"
        @keyup.enter="sendMessage"
        placeholder="Введите сообщение..."
        class="flex-1 px-5 py-3 rounded-full border border-gray-200 bg-white text-sm text-[#1a1a1a] placeholder-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-gray-400"
      />
      <button
        @click="sendMessage"
        class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-black text-white hover:bg-gray-800 transition-all duration-200"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script>
import moment from 'moment-timezone'
export default {
  name: 'Chat',
  props: {
    chat: Object,
    messages: Array,
    isActive: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      newMessage: '',
      userTimezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    }
  },
  computed: {
    groupedMessages() {
      const groups = []
      let currentDate = null

      this.messages.forEach((message) => {
        const messageDate = moment
          .utc(message.created_at)
          .tz(Intl.DateTimeFormat().resolvedOptions().timeZone)
          .format('YYYY-MM-DD')

        if (messageDate !== currentDate) {
          currentDate = messageDate
          groups.push({ date: currentDate, messages: [] })
        }

        groups[groups.length - 1].messages.push(message)
      })

      return groups
    },
  },
  methods: {
    sendMessage() {
      if (this.newMessage.trim() === '') return

      this.$inertia.post(
        `/chats/${this.chat.id}/messages`,
        {
          message: this.newMessage,
        },
        {
          preserveScroll: true,
          onSuccess: () => {
            this.newMessage = ''
          },
        },
      )
    },
    formatTime(date) {
      return moment
        .utc(date)
        .tz(Intl.DateTimeFormat().resolvedOptions().timeZone)
        .format('HH:mm')
    },
    formatDate(date) {
      return moment(date).format('DD.MM.YY')
    },
  },
}
</script>
