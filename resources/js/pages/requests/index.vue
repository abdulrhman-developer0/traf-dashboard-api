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

const headers = [
  {
    title: 'مقدم الخدمة',
    key: 'provider_name',
  },
  {
    title: 'العميل',
    key: 'client_name',
  },
  {
    title: 'الموعد',
    key: 'date',
  },
  {
    title: 'الحالة',
    key: 'status',
  },
]



</script>

<template>
  <Head title="طلبات الانضمام" />  
  <section class="admindashboard">
    dqwqwd
  </section>
</template>
