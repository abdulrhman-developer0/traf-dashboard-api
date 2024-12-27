<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import moment from 'moment'
import { useI18n } from 'vue-i18n'
const { t } = useI18n() 
import "moment/dist/locale/ar"
moment.locale('ar_SA')
const props = defineProps({
  data: Array,
})



</script>

<template>
  <Head title="انواع الخدمات" />  
  <section class="admindashboard">
    <VRow>
      <VCol cols="12">
        <VRow>
          <VCol cols="4" v-for="category in data.categories.data">
            <VCard flat class="servicecatblock">
              <img :src="category.icon">
              <h3 class="mt-4">{{ category.name }}</h3>
            </VCard>
          </VCol>
        </VRow>
      </VCol>
    </VRow>
  </section>
</template>
