<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { useChat } from './useChat'
import { useChatStore } from '@/views/apps/chat/useChatStore'

const props = defineProps({
  currentUser: Object,
})

const emit = defineEmits(['close'])


// composables
const store = useChatStore()
const { resolveAvatarBadgeVariant } = useChat()

const userStatusRadioOptions = [
  {
    title: 'Online',
    value: 'online',
    color: 'success',
  },
  {
    title: 'Away',
    value: 'away',
    color: 'warning',
  },
  {
    title: 'Do not disturb',
    value: 'busy',
    color: 'error',
  },
  {
    title: 'Offline',
    value: 'offline',
    color: 'secondary',
  },
]

const isAuthenticationEnabled = ref(true)
const isNotificationEnabled = ref(false)
</script>

<template>
  <template v-if="store.profileUser">
    <!-- Close Button -->
    <div class="pt-2 me-2 text-end">
      <IconBtn @click="$emit('close')">
        <VIcon
          class="text-medium-emphasis"
          color="disabled"
          icon="tabler-x"
        />
      </IconBtn>
    </div>

    <!-- User Avatar + Name + Role -->
    <div class="text-center px-6">
      <VBadge
        location="bottom right"
        offset-x="7"
        offset-y="4"
        bordered
        :color="resolveAvatarBadgeVariant(props.currentUser.status)"
        class="chat-user-profile-badge mb-3"
      >
        <VAvatar
          size="84"
          :variant="!props.currentUser.avatar ? 'tonal' : undefined"
          :color="!props.currentUser.avatar ? resolveAvatarBadgeVariant(props.currentUser.status) : undefined"
        >
          <VImg
            v-if="props.currentUser.avatar"
            :src="props.currentUser.avatar"
          />
          <span
            v-else
            class="text-3xl"
          >{{ avatarText(props.currentUser.name) }}</span>
        </VAvatar>
      </VBadge>
      <h5 class="text-h5">
        {{ props.currentUser.name }}
      </h5>
      <p class="text-capitalize text-medium-emphasis mb-0">
        {{ props.currentUser.name }}
      </p>
    </div>

  </template>
</template>

<style lang="scss">
.chat-settings-section {
  .v-switch {
    .v-input__control {
      .v-selection-control__wrapper {
        block-size: 18px;
      }
    }
  }
}
</style>
