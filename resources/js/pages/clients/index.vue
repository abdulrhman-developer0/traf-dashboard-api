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

const headers = [
  {
    title: 'الأسم',
    key: 'client_name',
  },
  {
    title: 'رقم التليفون',
    key: 'phone',
  },
  {
    title: 'تاريخ التسجيل',
    key: 'created_at',
  },
  {
    title: '',
    key: 'actions',
  },
]

const statsCards = [
  {
    title: 'عدد المستخدمين',
    key: 'clients_count',
    icon: 'tabler-users-group',
  },
  {
    title: 'مستخدمين جدد',
    key: 'new_clients',
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

const deleteRecord = (id) => {
  if(confirm('هل انت متأكد من الحذف؟')){
    router.delete(`/clients/${id}`);
  }
}

</script>

<template>
  <Head title="العملاء" />  
  <section class="admindashboard">
    <VRow>
      <VCol cols="12" sm="6" md="4" lg="3" v-for="card in statsCards">
        <StatisticsCard :title="card.title" :value="data.stats[card.key]" :icon="card.icon" />
      </VCol>
    </VRow>

    <VRow class="match-height">
      <VCol cols="12">
        <MainChart 
          :chart="props.data.chart"
          :mainTitle="'عدد العملاء'"
          :title="'عملاء'" 
          :subtitle="data.stats.year_total_clients"
          :year="props.year"
          :targetRoute="'/clients'"
        />
        
      </VCol>
    </VRow>

    <VRow>
      <VCol cols="12">
        <VCard flat class="px-5 tablemaincard">
          <VCardText class="py-0 px-0">
            <VDataTable
              :items="data.clients.data.items"
              item-value="id"
              :headers="headers"
              :items-per-page="100"
              class="text-no-wrap"
            >

              <template #item.created_at="{ item }">
                {{ moment(item.created_at).format("DD MMMM, YYYY") }}
              </template>
              

              <template #item.actions="{ item }">
                <VBtn color="#C4174F" variant="flat" @click="deleteRecord(item.id)" >مسح</VBtn>
              </template>

              <!-- pagination -->
              <template #bottom>
                
                
              </template>

            </VDataTable>
          </VCardText>
        </VCard>
        <PaginationLinks :links="data.clients.meta.links" />
      </VCol>
    </VRow>
  </section>
</template>
