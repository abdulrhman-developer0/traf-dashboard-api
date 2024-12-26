<script setup>
import { useTheme } from 'vuetify'
import { router } from "@inertiajs/vue3";

const { global } = useTheme()

const authProviders = [
  {
    icon: 'tabler-brand-facebook-filled',
    color: '#4267b2',
    colorInDark: '#497CE2',
    url: 'auth/facebook'
  },
  {
    icon: 'tabler-brand-google-filled',
    color: '#dd4b39',
    colorInDark: '#db4437',
    url: 'auth/google'
  },
]
</script>

<template>
  <div class="d-flex justify-center flex-wrap gap-1">
    <VBtn
      v-for="link in authProviders"
      :key="link.icon"
      icon
      variant="text"
      size="small"
      :color="global.name.value === 'dark' ? link.colorInDark : link.color"
      @click="router.get(link.url)"
    >
      <VIcon
        size="20"
        :icon="link.icon"
      />
    </VBtn>
  </div>
</template>
