<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import moment from 'moment'
import { useI18n } from 'vue-i18n'
const { t } = useI18n() 


const props = defineProps({
  stats: Array,
})


</script>

<template>
  <Head :title="t('Dashboard')" />  
  <section class="admindashboard">
    wdqwdqw
  </section>
</template>
