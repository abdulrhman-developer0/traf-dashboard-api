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



</script>

<template>
  <Head title="الرئيسية" />  
  <section class="admindashboard">
    <VRow>
      <VCol cols="12" sm="6" md="4" lg="3">
        <VCard class="statecard py-5">
          <VCardText class="d-flex flex-row gap-4 py-0 px-0 pr-2 pl-5">
            
            <div style="border-left: 3px solid #C4174F;height: 53px"></div>

            <div class="d-flex flex-column gap-5">
              <h4 class="mt-2">
                المستخدمين
              </h4>
              <h5>
                {{ data.stats.users_count }}
              </h5>
              
            </div>
            <VSpacer/>
            <VAvatar
              rounded-0
              size="28"
              style="background: #F9E8ED;border-radius: 0;"
            >
              <VIcon
                icon="tabler-users-group"
                size="18"
                style="color: #C4174F"
              />
            </VAvatar>
            
          </VCardText>
          <VCardText class="py-0 px-0 pr-6 ">
            <p class="mb-0  pt-5">
                منذ أسبوع
                <VIcon class="tabler-arrow-up mx-1" color="#4DC80C" size="20"/>
                <span>+4.5%</span>
                
              </p>
          </VCardText>
        </VCard>
      </VCol>
      <VCol cols="12" sm="6" md="4" lg="3">
        <VCard class="statecard py-5">
          <VCardText class="d-flex flex-row gap-4 py-0 px-0 pr-2 pl-5">
            
            <div style="border-left: 3px solid #C4174F;height: 53px"></div>

            <div class="d-flex flex-column gap-5">
              <h4 class="mt-2">
                متوسط الزائرين
              </h4>
              <h5>
                {{ data.stats.visitors_count }}
              </h5>
            </div>
            <VSpacer/>
            <VAvatar
              rounded-0
              size="28"
              style="background: #F9E8ED;border-radius: 0;"
            >
              <VIcon
                icon="tabler-users"
                size="18"
                style="color: #C4174F"
              />
            </VAvatar>
            
          </VCardText>
          <VCardText class="py-0 px-0 pr-6 ">
            <p class="mb-0  pt-5">
                منذ أسبوع
                <VIcon class="tabler-arrow-up mx-1" color="#4DC80C" size="20"/>
                <span>+4.5%</span>
                
              </p>
          </VCardText>
        </VCard>
      </VCol>
      <VCol cols="12" sm="6" md="4" lg="3">
        <VCard class="statecard py-5">
          <VCardText class="d-flex flex-row gap-4 py-0 px-0 pr-2 pl-5">
            
            <div style="border-left: 3px solid #C4174F;height: 53px"></div>

            <div class="d-flex flex-column gap-5">
              <h4 class="mt-2">
                مقدمي الخدمة
              </h4>
              <h5>
                {{ data.stats.providers_count }}
              </h5>
            </div>
            <VSpacer/>
            <VAvatar
              rounded-0
              size="28"
              style="background: #F9E8ED;border-radius: 0;"
            >
              <VIcon
                icon="tabler-user-dollar"
                size="18"
                style="color: #C4174F"
              />
            </VAvatar>
            
          </VCardText>
          <VCardText class="py-0 px-0 pr-6 ">
            <p class="mb-0  pt-5">
                منذ أسبوع
                <VIcon class="tabler-arrow-up mx-1" color="#4DC80C" size="20"/>
                <span>+4.5%</span>
                
              </p>
          </VCardText>
        </VCard>
      </VCol>
      <VCol cols="12" sm="6" md="4" lg="3">
        <VCard class="statecard py-5">
          <VCardText class="d-flex flex-row gap-4 py-0 px-0 pr-2 pl-5">
            
            <div style="border-left: 3px solid #C4174F;height: 53px"></div>

            <div class="d-flex flex-column gap-5">
              <h4 class="mt-2">
                مجموع الحجوزات
              </h4>
              <h5>
                {{ data.stats.bookings_count }}
              </h5>
            </div>
            <VSpacer/>
            <VAvatar
              rounded-0
              size="28"
              style="background: #F9E8ED;border-radius: 0;"
            >
              <VIcon
                icon="tabler-cash"
                size="18"
                style="color: #C4174F"
              />
            </VAvatar>
            
          </VCardText>
          <VCardText class="py-0 px-0 pr-6 ">
            <p class="mb-0  pt-5">
                منذ أسبوع
                <VIcon class="tabler-arrow-up mx-1" color="#4DC80C" size="20"/>
                <span>+4.5%</span>
                
              </p>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>

    <VRow class="match-height">
      <VCol cols="8">
        <VCard flat class="px-0 tablemaincard">
          <VCardItem
            class="py-3 px-3"
            title="عدد الخدمات"
            :subtitle="data.stats.services_count"
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

          <VCardText class="py-0 ">
            <IndexChart />
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
