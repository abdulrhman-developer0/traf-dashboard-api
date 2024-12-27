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
    title: 'عدد الإشتراكات الاجمالي',
    key: 'subscriptions_count',
    icon: 'tabler-packages',
  },
  {
    title: 'إشتراكات جديدة',
    key: 'new_subscriptions',
    icon: 'tabler-package-import',
  },
  {
    title: 'إشتراكات منتهية',
    key: 'expired_subscriptions',
    icon: 'tabler-package-off',
  },
  {
    title: 'عدد الباقات',
    key: 'total_packages',
    icon: 'tabler-box',
  },
]


</script>

<template>
  <Head title="التسعير" />  
  <section class="admindashboard">
    <VRow>
      <VCol cols="12" sm="6" md="4" lg="3" v-for="card in statsCards">
        <StatisticsCard :title="card.title" :value="data.stats[card.key]" :icon="card.icon" />
      </VCol>
    </VRow>

    <VRow class="match-height">
      <VCol cols="12">
        <VCard flat class="px-0 tablemaincard">
          <VCardItem
            class="py-3 px-3 mb-6"
            title="الإشتراكات"
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
            <MainChart :chart="props.data.chart" :title="'إشتراكات'" />
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
