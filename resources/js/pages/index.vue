<script setup>
import { Head } from '@inertiajs/vue3'
import moment from 'moment'
import { useI18n } from 'vue-i18n'
const { t } = useI18n() 
import "moment/dist/locale/ar"
moment.locale('ar_SA')
const props = defineProps({
  data: Array,
  year: Number,
})

const year = ref(null)


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

const bufferValue = ref(20)


const statsCards = [
  {
    title: 'المستخدمين',
    key: 'users_count',
    icon: 'tabler-users-group',
  },
  {
    title: 'العملاء',
    key: 'clients_count',
    icon: 'tabler-users',
  },
  {
    title: 'مقدمي الخدمة',
    key: 'providers_count',
    icon: 'tabler-user-dollar',
  },
  {
    title: 'مجموع الحجوزات',
    key: 'bookings_count',
    icon: 'tabler-cash',
  },
]


</script>

<template>
  <Head title="الرئيسية" />  
  <section class="admindashboard">
    <VRow>
      <VCol cols="12" sm="6" md="4" lg="3" v-for="card in statsCards">
        <StatisticsCard :title="card.title" :value="data.stats[card.key]" :icon="card.icon" />
      </VCol>
    </VRow>

    <VRow class="match-height">
      <VCol cols="9">
        <MainChart 
          :chart="props.data.chart"
          :mainTitle="'عدد الخدمات'"
          :title="'خدمات مقدمة'" 
          :subtitle="data.stats.year_bookings_count"
          :year="props.year"
          :targetRoute="'/'"
        />

        
      </VCol>

      <VCol cols="3">
        <VCard flat class="pa-4 tablemaincard">
          <VCardItem
            class="py-3 px-6 mb-4"
            title="متوسط استخدام الخدمة"
          >
            
          </VCardItem>

          <VCardText class="px-8 avcategorylist" >
            <VRow v-for="cat in props.data.category_stats" class="match-height">
              <VCol cols="6">
                <h5>{{ cat.name }}</h5>
              </VCol>
              <VCol cols="6" style="align-content: center;">
                <VProgressLinear
                  v-model="cat.percentage"
                  color="primary"
                  :buffer-value="bufferValue"
                  height="2"
                  :rounded="false"
                  

                />
              </VCol>
            </VRow>
          </VCardText>

        </VCard>
      </VCol>

    </VRow>

    <VRow>
      <VCol cols="12">
        <VCard flat class="px-5 tablemaincard">
          <VCardText class="py-8 px-8">
            <h3>الحجوزات</h3>
          </VCardText>
          <VCardText class="py-0 px-0">
            <VDataTable
              :items="data.bookings.data.items"
              item-value="id"
              :headers="headers"
              :items-per-page="100"
              class="text-no-wrap"
            >

              <template #item.date="{ item }">
                {{ moment(item.date).format("DD MMMM, YYYY") }}
              </template>

              <template #item.status="{ item }">
                <VChip
                  variant="flat"
                  class="statusChip px-8"
                  :class="item.status"
                >
                  {{ t(item.status) }}
                </VChip>
              </template>

              <!-- pagination -->
              <template #bottom>
                
                
              </template>

            </VDataTable>
          </VCardText>
        </VCard>
        <PaginationLinks :links="data.bookings.meta.links" />
      </VCol>
    </VRow>
  </section>
</template>
