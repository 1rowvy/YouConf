<template>
  <div class="max-w-4xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-10">
      <div>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-[#1a1a1a]">
          Уведомления
        </h1>
        <p v-if="unreadCount > 0" class="mt-3 text-lg text-gray-400 font-medium">
          {{ unreadCount }} непрочитанных
        </p>
      </div>
      <button
        v-if="unreadCount > 0"
        @click="markAllAsRead"
        class="px-6 py-3 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition-all duration-300 font-bold text-sm"
      >
        Прочитать все
      </button>
    </div>

    <div v-if="notifications.data && notifications.data.length > 0" class="space-y-4">
      <div
        v-for="notification in notifications.data"
        :key="notification.id"
        class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm transition-all duration-300 cursor-pointer hover:shadow-md"
        :class="{ 'border-l-4 border-l-blue-500': !notification.read_at }"
        @click="openNotification(notification)"
      >
        <div class="flex items-start gap-4">
          <div
            class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
            :class="notification.read_at ? 'bg-gray-100' : 'bg-blue-50'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="notification.read_at ? 'text-gray-400' : 'text-blue-500'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-[#1a1a1a]" :class="{ 'text-gray-500': notification.read_at }">
              {{ notification.data.message }}
            </p>
            <div v-if="notification.data.custom_data" class="mt-2 flex items-center gap-2">
              <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-gray-100 text-gray-500">
                {{ notification.data.custom_data.old }}
              </span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
              <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600">
                {{ notification.data.custom_data.new }}
              </span>
            </div>
            <p class="mt-2 text-xs text-gray-400 font-medium">
              {{ formatDate(notification.created_at) }}
            </p>
          </div>
          <div v-if="!notification.read_at" class="flex-shrink-0 w-2.5 h-2.5 rounded-full bg-blue-500 mt-2"></div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-20 bg-gray-50 rounded-2xl">
      <p class="text-gray-400 font-medium">Уведомлений пока нет</p>
    </div>

    <div v-if="notifications.last_page > 1" class="flex justify-center gap-2 mt-10">
      <Link
        v-for="page in notifications.last_page"
        :key="page"
        :href="`/notifications?page=${page}`"
        class="w-10 h-10 flex items-center justify-center rounded-full text-sm font-bold transition-all duration-200"
        :class="page === notifications.current_page
          ? 'bg-black text-white'
          : 'text-gray-400 hover:bg-gray-100 hover:text-[#1a1a1a]'"
      >
        {{ page }}
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import dayjs from 'dayjs'
import 'dayjs/locale/ru'
import relativeTime from 'dayjs/plugin/relativeTime'

dayjs.extend(relativeTime)

export default {
  components: { Link },
  props: {
    notifications: Object,
    unreadCount: Number,
  },
  methods: {
    formatDate(date) {
      return dayjs(date).locale('ru').fromNow()
    },
    markAllAsRead() {
      Inertia.post('/notifications/mark-all-as-read', {}, {
        preserveScroll: true,
      })
    },
    openNotification(notification) {
      if (!notification.read_at) {
        Inertia.post(`/notifications/${notification.id}/mark-as-read`, {}, {
          preserveScroll: true,
        })
      }
      if (notification.data.thesis_id) {
        Inertia.visit(`/theses/${notification.data.thesis_id}`)
      }
    },
  },
}
</script>
