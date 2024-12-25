<script setup>

const props = defineProps({
  chat: Array,
  currentUser: Object,
  otherUser: Object

})


const contact = computed(() => ({
  id: store.activeChat?.contact.id,
  avatar: store.activeChat?.contact.avatar,
}))

const resolveFeedbackIcon = feedback => {
  if (feedback.isSeen)
    return {
      icon: 'tabler-checks',
      color: 'success',
    }
  else if (feedback.isDelivered)
    return {
      icon: 'tabler-checks',
      color: undefined,
    }
  else
    return {
      icon: 'tabler-check',
      color: undefined,
    }
}

const msgGroups = computed(() => {
  let messages = []
  const _msgGroups = []
  if (props.chat) {
    messages = props.chat
    let msgSenderId = messages.length ? messages[0].sender_id : null
    let msgGroup = {
      senderId: msgSenderId,
      messages: [],
    }
    messages.forEach((msg, index) => {
      if (msgSenderId === msg.sender_id) {
        msgGroup.messages.push({
          message: msg.text,
          time: msg.created_at,
        })
      } else {
        msgSenderId = msg.sender_id
        _msgGroups.push(msgGroup)
        msgGroup = {
          senderId: msg.sender_id,
          messages: [{
            message: msg.text,
            time: msg.created_at,
          }],
        }
      }
      if (index === messages.length - 1)
        _msgGroups.push(msgGroup)
    })
  }
  
  return _msgGroups
})
</script>

<template>
  <div class="chat-log pa-6">
    <div
      v-for="(msgGrp, index) in msgGroups"
      :key="msgGrp.senderId + String(index)"
      class="chat-group d-flex align-start"
      :class="[{
        'flex-row-reverse': msgGrp.senderId !== props.otherUser.id,
        'mb-6': msgGroups.length - 1 !== index,
      }]"
    >
      <div
        class="chat-body d-inline-flex flex-column"
        :class="msgGrp.senderId !== props.otherUser.id ? 'align-end' : 'align-start'"
      >
        <div
          v-for="(msgData, msgIndex) in msgGrp.messages"
          :key="msgData.time"
          class="chat-content py-2 px-4 " 
          style="background-color: rgb(var(--v-theme-surface));"
          :class="[
            msgGrp.senderId === props.otherUser.id ? 'other-messages-box chat-left' : 'my-messages-box text-white chat-right',
            msgGrp.messages.length - 1 !== msgIndex ? 'mb-2' : 'mb-1',
          ]"
        >
          <p class="mb-0 text-base">
            {{ msgData.message }}
          </p>
        </div>
        <div :class="{ 'text-right': msgGrp.senderId !== props.otherUser.id }">
          <span class="text-sm ms-2 text-disabled">{{ formatDate(msgGrp.messages[msgGrp.messages.length - 1].time, { hour: 'numeric', minute: 'numeric' }) }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang=scss>
.chat-log {
  .chat-body {
    max-inline-size: calc(100% - 6.75rem);

    .chat-content {
      border-end-end-radius: 16px;
      border-end-start-radius: 16px;

      p {
        overflow-wrap: anywhere;
      }

      &.chat-left {
        border-start-end-radius: 16px;
      }

      &.chat-right {
        border-start-start-radius: 16px;
      }
    }
  }
}
</style>
