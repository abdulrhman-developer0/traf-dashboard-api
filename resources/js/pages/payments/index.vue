<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import moment from 'moment'
import { useI18n } from 'vue-i18n'
const { t } = useI18n() 
import "moment/dist/locale/ar"
moment.locale('ar_SA')
const props = defineProps({
  data: Array,
  year: Number,

})



const statsCards = [
  {
    title: 'عدد التحويلات',
    key: 'total_in_payments',
    icon: 'tabler-credit-card-refund',
  },
  {
    title: 'المبلغ الكلي',
    key: 'in_payments_amount',
    icon: 'tabler-cash-banknote',
  },
  {
    title: 'عدد الإرسالات',
    key: 'total_out_payments',
    icon: 'tabler-credit-card-pay',
  },
  {
    title: 'المبلغ الكلي المرسل',
    key: 'out_payments_amount',
    icon: 'tabler-cash-banknote',
  },
]


</script>

<template>
  <Head title="الدفع" />  
  <section class="admindashboard">
    <VRow class="match-height">
      <VCol cols="12" sm="6" md="4" lg="3" v-for="card in statsCards">
        <StatisticsCard :title="card.title" :value="data.stats[card.key]" :icon="card.icon" />
      </VCol>
    </VRow>

    <VRow class="match-height">
      <VCol cols="12">
        <MainChart 
          :chart="props.data.chart"
          :mainTitle="'المبلغ الكلي'"
          :title="'المبلغ الكلي'" 
          :subtitle="data.stats.year_in_payments_amount+' ر.س'"
          :year="props.year"
          :targetRoute="'/payments'"
        />
      </VCol>

      

    </VRow>

    <VRow>
      <VCol cols="12">
        
      </VCol>
    </VRow>
  </section>
</template>
