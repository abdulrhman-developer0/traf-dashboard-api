<script setup>
import AdminNavItems from '@/navigation/vertical/admin'

import { themeConfig } from '@themeConfig'
import { usePage  } from '@inertiajs/vue3'
const page = usePage()
const title = computed(() => page.props.title)


// Components
import Footer from '@/layouts/components/Footer.vue'
import NavBarNotifications from '@/layouts/components/NavBarNotifications.vue'
import NavSearchBar from '@/layouts/components/NavSearchBar.vue'
import NavbarShortcuts from '@/layouts/components/NavbarShortcuts.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'
import NavBarI18n from '@core/components/I18n.vue'

// @layouts plugin
import { VerticalNavLayout } from '@layouts'
</script>

<template>
  <VerticalNavLayout :nav-items="AdminNavItems">
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <IconBtn
          id="vertical-nav-toggle-btn"
          class="ms-n3 d-lg-none"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon
            size="26"
            icon="tabler-menu-2"
          />
        </IconBtn>

        <h3 class="mainpagetitle">
          <VIcon
            size="26"
            icon="tabler-hand-stop"
            color="#E55175"
          />
          مرحبًا بعودتك، {{ page.props.user.name }}
        </h3>
        <VSpacer />
        <NavSearchBar />
        <VSpacer />

        <UserProfile class="ml-4" />
      </div>
    </template>

    <!-- 👉 Pages -->
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>

  </VerticalNavLayout>
</template>
