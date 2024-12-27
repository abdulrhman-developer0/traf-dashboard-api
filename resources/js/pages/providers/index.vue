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
    title: 'الأسم',
    key: 'provider_name',
  },
  {
    title: 'النوع',
    key: 'type',
  },
  {
    title: 'رقم التسجيل الضريبي',
    key: 'tax_number',
  },
  {
    title: 'التاريخ',
    key: 'created_at',
  },
  {
    title: '',
    key: 'actions',
  },
]

const statsCards = [
  {
    title: 'عدد مقدمي الخدمة',
    key: 'providers_count',
    icon: 'tabler-users-group',
  },
  {
    title: 'مقدمي خدمة جدد',
    key: 'new_providers',
    icon: 'tabler-users-plus',
  },
  {
    title: 'تسجيل خروج',
    key: 'logouts_count',
    icon: 'tabler-logout',
  },
  {
    title: 'حسابات ممسوحة',
    key: 'deleted_accounts',
    icon: 'tabler-trash',
  },
]


</script>

<template>
  <Head title="مقدمي الخدمات" />  
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
            title="مقدمي الخدمات"
            :subtitle="data.stats.total_providers"
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
            <MainChart :chart="props.data.chart" :title="'مقدمي الخدمات'"/>
          </VCardText>

        </VCard>
      </VCol>

      

    </VRow>

    <VRow>
      <VCol cols="12">
        <VCard flat class="px-5 tablemaincard">
          <VCardText class="py-0 px-0">
            <VDataTable
              :items="data.providers.data.items"
              item-value="id"
              :headers="headers"
              :items-per-page="100"
              class="text-no-wrap"
            >

              <template #item.created_at="{ item }">
                {{ moment(item.created_at).format("DD MMMM, YYYY") }}
              </template>
              
              <template #item.tax_number="{ item }">
                {{ item.tax_number ? item.tax_number : 'غير متوفر' }}
              </template>

              <template #item.type="{ item }">
                <span v-if="item.is_personal">مقدم خدمة</span>
                <span v-else>مشغل</span>
              </template>

              <template #item.actions="{ item }">
                {{ moment(item.created_at).format("DD MMMM, YYYY") }}
              </template>

              <!-- pagination -->
              <template #bottom>
                
                
              </template>

            </VDataTable>
          </VCardText>
        </VCard>
        <PaginationLinks :links="data.providers.meta.links" />
      </VCol>
    </VRow>
  </section>
</template>
