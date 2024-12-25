<script setup>

import enimage from '../../../images/lang/en.png'
import arimage from '../../../images/lang/ar.png'

const props = defineProps({
  languages: {
    type: Array,
    required: true,
  },
  location: {
    type: null,
    required: false,
    default: 'bottom end',
  },
})

const { locale } = useI18n({ useScope: 'global' })
</script>

<template>
  <VBtn variant="default" >
    <div class="d-flex flex-row gap-3" style="align-items: center;">
      <img :src="locale == 'en' ? enimage : arimage" width="24px" height="24px" style="border-radius: 50%" alt="">
      <span style="color: #374557;font-size: 18px;font-weight: 600" >{{ $t('lang') }}</span>
      <VIcon color="#9A9A9A"icon="tabler-caret-down-filled" />
    </div>

    <!-- Menu -->
    <VMenu
      activator="parent"
      :location="props.location"
      offset="12px"
      width="175"
    >
      <!-- List -->
      <VList
        :selected="[locale]"
        color="primary"
      >
        <!-- List item -->
        <VListItem
          v-for="lang in props.languages"
          :key="lang.i18nLang"
          :value="lang.i18nLang"
          @click="locale = lang.i18nLang"
        >
          <!-- Language label -->
          <VListItemTitle>
            {{ lang.label }}
          </VListItemTitle>
        </VListItem>
      </VList>
    </VMenu>
  </VBtn>
</template>
