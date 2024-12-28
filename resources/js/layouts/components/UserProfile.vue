<!-- eslint-disable vue/html-indent -->
<script setup>
import defaultavatar from '@images/default-avatar.png'
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()

const user = computed(() => page.props.user)
const logout = () => {
  router.post(route('logout'))
}
</script>

<template>

  <VBtn variant="default" >
    <div class="d-flex flex-row gap-3" style="align-items: center;">
      <VImg :src="user.avatar ? user.avatar : defaultavatar" style="border-radius: 50%; width: 40px;height: 40px;" />
      <span class="avatar-name">{{ user.name }}</span>
      <VIcon color="#140208"icon="tabler-caret-down-filled" size="15" />
    </div>

      <!-- SECTION Menu -->
      <VMenu
        activator="parent"
        width="230"
        location="bottom end"
        offset="14px"
        
      >
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar
                    color="primary"
                    variant="tonal"
                  >
                    <VImg :src="user.avatar ? user.avatar : defaultavatar" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              {{ user.name }}
            </VListItemTitle>
            <VListItemSubtitle>
              <span class="">{{ user.email }}</span>
            </VListItemSubtitle>
          </VListItem>

          <VDivider class="my-2" />

          <!-- 👉 Profile -->
          <VListItem link @click="router.get('/my-profile')">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="tabler-user"
                size="22"
              />
            </template>

            <VListItemTitle >{{ $t('Profile') }}</VListItemTitle>
          </VListItem>

          <!-- 👉 Settings -->
          <VListItem link @click="router.get('/my-settings')">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="tabler-settings"
                size="22"
              />
            </template>

            <VListItemTitle >{{ $t('Settings') }}</VListItemTitle>
          </VListItem>

          

          <!-- Divider -->
          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <VListItem @click="logout">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="tabler-logout"
                size="22"
              />
            </template>

            

            <VListItemTitle>{{ $t('Logout') }}</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VBtn>
</template>
