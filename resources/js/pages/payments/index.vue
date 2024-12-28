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
        <VCard flat class="px-0 tablemaincard">
          <VCardItem
            class="py-3 px-3 mb-6"
            title="المبلغ الكلي"
            :subtitle="data.stats.total_subscriptions"
          >
            <template #append>
              <VBtn
                variant="tonal"
                append-icon="tabler-chevron-down"
              >
                2024
              </VBtn>
            </template>
          </VCardItem>

          <VCardText class="py-0 " >
            <MainChart :chart="props.data.chart" :title="'المبلغ الكلي'" />
          </VCardText>

        </VCard>
      </VCol>

      

    </VRow>

    <VRow>
      <VCol cols="12">
        
      </VCol>
    </VRow>
  </section>
</template>
