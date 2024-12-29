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


const isDialogVisible = ref(false)
const selectedItem = ref({})

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

const deleteRecord = (id) => {
  if(confirm('هل انت متأكد من الحذف؟')){
    router.delete(`/pricing/${id}`);
  }
}

const actionReturn = () => {
  selectedItem.value = {}

}
</script>

<template>
  <Head title="التسعير" />  
  <Alert v-if="$page?.props.flash?.status" :status="$page?.props.flash?.status" />

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
          :mainTitle="'المبلغ الكلي'"
          :title="'إجمالي الاشتراكات'" 
          :subtitle="data.stats.year_total_amount_subscriptions+' ر.س'"
          :year="props.year"
          :targetRoute="'/pricing'"
        />
        
      </VCol>
    </VRow>

    <VRow>
      <VCol cols="12">
        <VBtn color="#E55175" variant="default" class="addbtn" @click="isDialogVisible = true" >
          <VIcon icon="tabler-plus" class="ml-2" />

          اضافة باقة جديدة
        </VBtn>

      </VCol>
      <VCol cols="6" v-for="item in data.packages.data.items">
        
        <VCard flat class="packageCard">
          <VCardText class="d-flex flex-row pa-0 ma-0" style="align-items: center;">
            <div>
              <h3 class="mb-1">{{ item.name }} <span>(وفر {{item.ads_discount}}%)</span></h3>
              <h4 class=""><span class="ml-2">{{ item.duration_in_days }} يوم</span>بقيمة {{ item.price }} ر.س</h4>
            </div>
            <VSpacer/>
            <div>
              <VIcon icon="tabler-pencil" style="cursor: pointer;" class="ml-2" @click="selectedItem = item, isDialogVisible= true" />
              <VIcon icon="tabler-trash" color="error" style="cursor: pointer;" @click="deleteRecord(item.id)" />
            </div>
          </VCardText>
        </VCard>
      </VCol>
      <VCol cols="12">
        <PaginationLinks :links="data.packages.meta.links" />

      </VCol>
    </VRow>

    <AddEditPackageDialog
      v-model:isDialogVisible="isDialogVisible"
      v-model:package="selectedItem"
      @action-return="actionReturn"

    />

  </section>
</template>
