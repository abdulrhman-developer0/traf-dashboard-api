<script setup>
import { useChat } from '@/views/apps/chat/useChat'
import { useChatStore } from '@/views/apps/chat/useChatStore'

const props = defineProps({
  isChatContact: {
    type: Boolean,
    required: false,
    default: false,
  },
  user: {
    type: null,
    required: true,
  },
  chat: Object,
  activeChat: Boolean
})

const store = useChatStore()
const { resolveAvatarBadgeVariant } = useChat()

const isChatContactActive = computed(() => {
  const isActive = store.activeChat?.contact.id === props.user.id
  if (!props.isChatContact)
    return !store.activeChat?.chat && isActive
  
  return isActive
})
</script>

<template>
  <li
    :key="store.chatsContacts.length"
    class="chat-contact cursor-pointer d-flex align-center"
    :class="{ 'chat-contact-active': props.activeChat }"
  >
    <VBadge
      dot
      location="bottom right"
      offset-x="3"
      offset-y="0"
      color="success"
      bordered
      :model-value="props.activeChat"
    >
      <VAvatar
        size="60"
        :variant="!props.user.avatar ? 'tonal' : undefined"
        :color="!props.user.avatar ? resolveAvatarBadgeVariant(props.user.status) : undefined"
      >
        <VImg
          v-if="props.user.avatar"
          :src="props.user.avatar"
          alt="John Doe"
        />
        <span v-else>{{ avatarText(user.name) }}</span>
      </VAvatar>
    </VBadge>
    <div class="flex-grow-1 ms-4 overflow-hidden">
      <p class="text-base text-high-emphasis mb-0">
        {{ props.user.name }}
      </p>
      <p class="mb-0 text-truncate text-body-2">
        {{ props.chat.text }}
      </p>
    </div>
    <div
      v-if="props.isChatContact && 'chat' in props.user"
      class="d-flex flex-column align-self-start"
    >
      <div class="text-body-2 text-disabled whitespace-no-wrap">
        {{ formatDateToMonthShort(props.user.chat.lastMessage.time) }}
      </div>
      <VBadge
        v-if="props.user.chat.unseenMsgs"
        color="error"
        inline
        :content="props.user.chat.unseenMsgs"
        class="ms-auto"
      />
    </div>
  </li>
</template>

<style lang="scss">
@use "@core-scss/template/mixins" as templateMixins;
@use "@styles/variables/vuetify.scss";
@use "@core-scss/base/mixins";
@use "vuetify/lib/styles/tools/states" as vuetifyStates;

.chat-contact {
  border-radius: 16px;
  padding-block: 18px;
  padding-inline: 12px;

  @include mixins.before-pseudo;
  @include vuetifyStates.states($active: false);

  &.chat-contact-active {
    box-shadow: none;

    background: #F6F6F6;

  }

  .v-badge--bordered .v-badge__badge::after {
    color: #fff;
  }
}
</style>
