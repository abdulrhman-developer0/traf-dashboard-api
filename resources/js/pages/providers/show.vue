<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import moment from 'moment'
import { useI18n } from 'vue-i18n'
const { t } = useI18n() 
import "moment/dist/locale/ar"
moment.locale('ar_SA')
import defaultavatar from '@images/default-avatar.png'

const props = defineProps({
  data: Array,
})


</script>

<template>
  <Head title="مقدم خدمة" />  
  <section class="admindashboard">

    <VRow>
      <VCol cols="12" class="d-flex flex-row gap-4">
        <VImg :src="data.photo ? data.photo : defaultavatar" style="border-radius: 50%; width: 194px;height: 194px;" />
        <div>
          <h3>{{ data.name }}</h3>
        </div>
      </VCol>
    </VRow>
  </section>
</template>
