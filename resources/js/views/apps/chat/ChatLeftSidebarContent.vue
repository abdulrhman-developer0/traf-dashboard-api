<script setup>
import { router  } from '@inertiajs/vue3'

import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { useChat } from './useChat'
import ChatContact from '@/views/apps/chat/ChatContact.vue'
import { useChatStore } from '@/views/apps/chat/useChatStore'
import defaultavatar from '@images/default-avatar.png'

const props = defineProps({
  search: {
    type: String,
    required: true,
  },
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  currentUser: Object,
  chats: Array,
  otherUser: Object,
  contacts: Array,
})

const emit = defineEmits([
  'openChatOfContact',
  'showUserProfile',
  'close',
  'update:search',
])

const { resolveAvatarBadgeVariant } = useChat()
const search = useVModel(props, 'search', emit)
const store = useChatStore()
</script>

<template>
  <div
    v-if="props.currentUser"
    class="chat-list-header d-flex flex-row gap-4 pb-5"
    
  >
   
    <VAvatar
      size="96"
      class="cursor-pointer"
      @click="$emit('showUserProfile')"
    >
      <VImg
        :src="props.currentUser.avatar"
      />
    </VAvatar>
    <div class="flex-grow-1 ms-4 overflow-hidden">
      <div class="mb-0" style="font-size: 24px;font-weight: 700;color: #374557;">
        {{ props.currentUser.name }}
      </div>
      <p class="mb-0" style="font-size: 18px;font-weight: 400;color: #A098AE;">
        {{ props.currentUser.type }}
      </p>
    </div>
  </div>
  <VDivider />

  <div class="d-flex flex-column gap-4 pb-4 pt-4 px-6" >
    <div class="d-flex flex-row" style="align-items: center;">
      <h5 class="cheader">
        Contacts
      </h5>
      <VSpacer />
      <span class="subtext">View All</span>
    </div>
   
    <div class="d-flex flex-row gap-3">
      <VAvatar
        v-for="item in props.contacts"
        size="48"
        class="cursor-pointer"
        @click="router.get('/dashboard/messages?user='+item.contact.id)"

      >
        <VImg
          :src="item.contact.avatar ? item.contact.avatar : defaultavatar"
          :alt="item.contact.name"
        />
      </VAvatar>
    </div>
    
  </div>
  <VDivider />

  <PerfectScrollbar
    tag="ul"
    class="d-flex flex-column gap-y-1 chat-contacts-list px-3 py-2 list-none"
    :options="{ wheelPropagation: false }"
    

  >

    <li class="list-none">
      <h5 class="chat-contact-header">
        Chats
      </h5>
    </li>

    <ChatContact
      v-for="chat in props.chats"
      :key="`chat-${props.currentUser.id != chat.receiver.id ? chat.receiver.id : chat.sender.id}`"
      :user="props.currentUser.id != chat.receiver.id ? chat.receiver : chat.sender"
      :chat="chat"
      :activeChat="props.otherUser?.id == (props.currentUser.id != chat.receiver.id ? chat.receiver.id : chat.sender.id) ? true : false"
      @click="router.get('/dashboard/messages?user='+(props.currentUser.id != chat.receiver.id ? chat.receiver.id : chat.sender.id))"
    />


  </PerfectScrollbar>
</template>

<style lang="scss">
.chat-contacts-list {
  --chat-content-spacing-x: 16px;

  padding-block-end: 0.75rem;

  .chat-contact-header {
    margin-block: 0.5rem 0.25rem;
    color: #374557;
    font-weight: 600;
    font-size: 18px;
  }

  .chat-contact-header,
  .no-chat-items-text {
    margin-inline: var(--chat-content-spacing-x);
  }
}



</style>
