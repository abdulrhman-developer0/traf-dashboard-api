<script setup>
import { Head, useForm, router, usePage } from '@inertiajs/vue3'
import moment from 'moment'
import { useToast } from "vue-toastification";
const toast = useToast();

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
    title: 'طلبات الانضمام',
    key: 'total_requests',
    icon: 'tabler-user-plus',
  },
  {
    title: 'الطلبات المرفوضة',
    key: 'rejected_count',
    icon: 'tabler-user-x',
  },
  {
    title: 'الطلبات المقبولة',
    key: 'approved_count',
    icon: 'tabler-user-check',
  },
  {
    title: 'طلبات قيد الإنتظار',
    key: 'pending_count',
    icon: 'tabler-user-question',
  },
]

const form = useForm({
    status: null,
});


const changeStatus = (status,id) => {
    form.status = status
    form.put('/requests/'+id, {
        preserveState: false,
        onSuccess: () => {
            if(status == 'approved'){
                toast.success('تم قبول طلب الانضمام');

            }else {
                toast.success('تم رفض طلب الانضمام');

            }
            form.reset()
        },
    });
    
};


</script>

<template>
  <Head title="طلبات الإنضمام" />  
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
          :mainTitle="'طلبات الأنضمام'"
          :title="'طلبات انضمام'" 
          :subtitle="data.stats.year_total_requests"
          :year="props.year"
          :targetRoute="'/requests'"
        />
        
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

                <VBtn color="#C4174F" variant="flat" @click="changeStatus('approved',item.id)" >قبول</VBtn>
                <VBtn color="#C4174F" class="mx-2" variant="outlined" @click="changeStatus('rejected',item.id)">رفض</VBtn>

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
