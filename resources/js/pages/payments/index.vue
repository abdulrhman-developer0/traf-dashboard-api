<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import moment from 'moment'
import { useI18n } from 'vue-i18n'
const { t } = useI18n() 
import "moment/dist/locale/ar"
moment.locale('ar_SA')

import paymentinimage from '../../../images/payment-in.png'
const props = defineProps({
  data: Array,
  year: Number,

})


const tabs = ['الإستقبال','الإرسال']
const activeTab = ref(0)

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
            <VRow>
              <VCol cols="6" v-if="data.bookings['paid'].data.items.length"  v-for="item in data.bookings['paid'].data.items" class="pb-1">
                <VCard flat class="paymentCard">
                  <div class="d-flex flex-row gap-4">
                    <div class="d-flex flex-column">
                      <img :src="paymentinimage">
                    </div>
                    <div class="d-flex flex-column flex-fill gap-2" >
                      <div class="d-flex flex-row" style="align-items: center;">
                        <h3 class="mt-1">{{ item.service_name }} <span>{{ moment(item.date).format("DD MMMM, YYYY") }}</span></h3>
                        <VSpacer/>
                        <span class="paymentstatus">تمت</span>
                      </div>
                      <p class="mb-0">
                        <span>اسم العميل: </span>
                        <span class="ml-4 mr-1">{{ item.client_name }}</span>
                        <span>{{ item.client_phone }}</span>
                      </p>
                      <h4>
                        {{ item.paid_amount }} ر.س
                      </h4>
                    </div>
                  </div>
                </VCard>
              </VCol>
              <VCol cols="12" v-else>
                <VListItem  class="border px-1 mt-3 text-center">
                  لا يوجد بيانات
                </VListItem>

              </VCol>
            </VRow>
            <PaginationLinks v-if="data.bookings['paid'].data.items.length" :data="data.bookings['paid'].meta" />


          </VWindowItem>

          <VWindowItem>
            <VRow>
              <VCol cols="6" v-if="data.bookings['refund'].data.items.length"  v-for="item in data.bookings['refund'].data.items" class="pb-1">
                <VCard flat class="paymentCard">
                  <div class="d-flex flex-row gap-4">
                    <div class="d-flex flex-column">
                      <img :src="paymentinimage">
                    </div>
                    <div class="d-flex flex-column flex-fill gap-2" >
                      <div class="d-flex flex-row" style="align-items: center;">
                        <h3 class="mt-1">{{ item.service_name }} <span>{{ moment(item.date).format("DD MMMM, YYYY") }}</span></h3>
                        <VSpacer/>
                        <span class="paymentstatus">تمت</span>
                      </div>
                      <p class="mb-0">
                        <span>اسم العميل: </span>
                        <span class="ml-4 mr-1">{{ item.client_name }}</span>
                        <span>{{ item.client_phone }}</span>
                      </p>
                      <h4>
                        {{ item.paid_amount }} ر.س
                      </h4>
                    </div>
                  </div>
                </VCard>
              </VCol>
              <VCol cols="12" v-else>
                <VListItem  class="border px-1 mt-3 text-center">
                  لا يوجد بيانات
                </VListItem>

              </VCol>
            </VRow>
            <PaginationLinks v-if="data.bookings['refund'].data.items.length" :data="data.bookings['refund'].meta" />

          </VWindowItem>

        </VWindow>
      </VCol>
    </VRow>
  </section>
</template>
