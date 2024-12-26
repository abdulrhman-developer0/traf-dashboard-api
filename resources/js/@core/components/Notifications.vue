<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import defaultavatar from '@images/default-avatar.png'

import { usePage, router } from '@inertiajs/vue3'

const page = usePage()

const notifications = computed(() => page.props.user.notifications)

const props = defineProps({
  notifications: {
    type: Array,
    required: true,
  },
  badgeProps: {
    type: Object,
    required: false,
    default: undefined,
  },
  location: {
    type: null,
    required: false,
    default: 'bottom end',
  },
})

const markRead = notificationId => {

  router.put('/dashboard/notifications/'+notificationId, {}, {
    preserveState: false,
    onSuccess: () => {
    },
  });
}

const viewAll = () => {
  let url = null
  if(page.props.user.type == 'Admin') {
    url = '/dashboard/admin/notifications'
  }else if(page.props.user.type == 'Instructor'){
    url = '/dashboard/notifications'

  }else{
    url = '/dashboard'

  }

  router.get(url)
}

</script>

<template>
  <IconBtn id="notification-btn">

    <VBadge
      v-bind="props.badgeProps"
      :model-value="notifications.some(n => !n.read)"
      color="#3269D3"
      dot
      offset-x="-3"
      offset-y="-3"
      class="bounce"
    >
      <VIcon icon="tabler-bell-filled" size="32" color="#9A9A9A"/>
    </VBadge>

    <VMenu
      activator="parent"
      width="380px"
      :location="props.location"
      offset="12px"
      :close-on-content-click="false"
    >
      <VCard class="d-flex flex-column">
        <!-- 👉 Header -->
        <VCardItem class="notification-section">
          <VCardTitle class="text-h6">
            
            {{ $t('Notifications') }}
          </VCardTitle>

          
        </VCardItem>

        <VDivider />

        <!-- 👉 Notifications list -->
        <PerfectScrollbar
          :options="{ wheelPropagation: false }"
          style="max-block-size: 23.75rem;"
        >
          <VList class="notification-list rounded-0 py-0">
            <template
              v-for="(notification, index) in notifications"
              :key="notification.title"
            >
              <VDivider v-if="index > 0" />
              <VListItem
                link
                lines="one"
                min-height="66px"
                class="list-item-hover-class"
              >
                <!-- Slot: Prepend -->
                <!-- Handles Avatar: Image, Icon, Text -->
                <div class="d-flex align-start gap-3">
                  <VAvatar
                    :color="notification.color && !notification.img ? notification.color : undefined"
                    :variant="notification.img ? undefined : 'tonal' "
                  >
                    <span v-if="notification.text">{{ avatarText(notification.text) }}</span>
                    <VImg
                      v-if="notification.img"
                      :src="notification.img"
                    />
                    <VImg
                      v-else
                      :src="defaultavatar"
                    />
                    <VIcon
                      v-if="notification.icon"
                      :icon="notification.icon"
                    />
                  </VAvatar>

                  <div>
                    <p
                      class="text-body-2 mb-2"
                      style=" letter-spacing: 0.4px !important; line-height: 18px;"
                    >
                      {{ notification.data.message }}
                    </p>
                    <p
                      class="text-sm text-disabled mb-0"
                      style=" letter-spacing: 0.4px !important; line-height: 18px;"
                    >
                      {{ notification.create_at }}
                    </p>
                  </div>
                  <VSpacer />

                  <div class="d-flex flex-column align-end">
                    <VIcon
                      size="10"
                      icon="tabler-circle-filled"
                      :color="!notification.read ? 'primary' : '#a8aaae'"
                      :class="`${notification.read ? 'visible-in-hover' : ''}`"
                      class="mb-2"
                      v-if="!notification.read"

                    />

                    <VIcon
                      size="20"
                      icon="tabler-x"
                      class="visible-in-hover"
                      @click="markRead(notification.id)"
                      v-if="!notification.read"

                    />
                  </div>
                </div>
              </VListItem>
            </template>

            <VListItem
              v-show="!notifications.length"
              class="text-center text-medium-emphasis"
              style="block-size: 56px;"
            >
              <VListItemTitle>{{ $t('No Notification Found!') }}</VListItemTitle>
            </VListItem>
          </VList>
        </PerfectScrollbar>

        <VDivider />

        <!-- 👉 Footer -->
        <VCardText
          v-show="notifications.length"
          class="pa-4"
        >
          <VBtn
            block
            size="small"
            @click="viewAll()"
          >
            
            {{ $t('View All Notifications') }}
          </VBtn>
        </VCardText>
      </VCard>
    </VMenu>
  </IconBtn>
</template>

<style lang="scss">
.notification-section {
  padding-block: 0.75rem;
  padding-inline: 1rem;
}

.list-item-hover-class {
  .visible-in-hover {
    display: none;
  }

  &:hover {
    .visible-in-hover {
      display: block;
    }
  }
}

.notification-list.v-list {
  .v-list-item {
    border-radius: 0 !important;
    margin: 0 !important;
    padding-block: 0.75rem !important;
  }
}

// Badge Style Override for Notification Badge
.notification-badge {
  .v-badge__badge {
    /* stylelint-disable-next-line liberty/use-logical-spec */
    min-width: 18px;
    padding: 0;
    block-size: 18px;
  }
}


</style>
