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

const tabs = ['قيد المراجعة','قيد الإنتظار','اعلانات فعالة','اعلانات مرفوضة']
const activeTab = ref(0)


const statsCards = [
  {
    title: 'عدد الإعلانات',
    key: 'total_in_ads',
    icon: 'tabler-ad',
  },
  {
    title: 'الملبغ الكلي',
    key: 'in_ads_amount',
    icon: 'tabler-currency-dollar',
  },
  {
    title: 'اعلانات اليوم',
    key: 'in_ads_today',
    icon: 'tabler-ad',
  },
  {
    title: 'مبلغ اليوم',
    key: 'in_ads_today_amount',
    icon: 'tabler-currency-dollar',
  },
]


</script>

<template>
  <Head title="الإعلانات" />  
  <Alert v-if="$page?.props.flash?.status" :status="$page?.props.flash?.status" />

  <section class="admindashboard">
    <VRow class="match-height">
      <VCol cols="12" sm="6" md="4" lg="3" v-for="card in statsCards">
        <StatisticsCard :title="card.title" :value="data.stats[card.key]" :icon="card.icon" />
      </VCol>
    </VRow>

    <VRow>
      <VCol cols="12">
        <VBtn color="#E55175" variant="default" class="addbtn" @click="router.get('/policies/create')" >
          تعديل الأسعار
        </VBtn>

      </VCol>
    </VRow>

    <VRow class="customTabs">
      <VCol cols="12">
        <VTabs
          v-model="activeTab"
          grow
        >
          <VTab
            v-for="(tab, index) in tabs"
            :key="type"
          >
            {{ tab }}
          </VTab>
        </VTabs>
      </VCol>
      <VCol cols="12">
        <VWindow
          v-model="activeTab"
          :touch="false"
        >
          <VWindowItem>
            <AdsTable :data="data.ads['under-review']" :type="'under-review'" />
            <PaginationLinks :links="data.ads['under-review'].meta.links" />

          </VWindowItem>

          <VWindowItem>
            <AdsTable :data="data.ads['waiting']" :type="'waiting'" />

          </VWindowItem>


          <VWindowItem>
            <AdsTable :data="data.ads['approved']" :type="'approved'" />

          </VWindowItem>

          <VWindowItem>
            <AdsTable :data="data.ads['rejected']" :type="'rejected'" />

          </VWindowItem>

        </VWindow>
      </VCol>
    </VRow>
  </section>
</template>
