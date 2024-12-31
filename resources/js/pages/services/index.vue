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



</script>

<template>
  <Head title="الخدمات" />  
  <section class="admindashboard">

    <VRow class="match-height">
      <VCol cols="9">
        <MainChart 
          :chart="props.data.chart"
          :mainTitle="'عدد الخدمات'"
          :title="'عدد الخدمات'" 
          :subtitle="data.stats.year_total_services"
          :year="props.year"
          :targetRoute="'/services'"
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
        <VRow>
          <VCol cols="3" v-for="service in data.services.data.items">
            <div class="serviceBlock">
              <div class="imagdiv">
                <img :src="service.photo" v-if="service.photo" />
                <span v-else>الصورة غير متوفرة</span>
              </div>
              <div class="d-flex flex-row mt-4 px-2">
                <div>
                  <h3>{{ service.name }}</h3>
                  <h4>{{ service.category_name }}</h4>
                  <span>{{ service.price }} ر.س</span>
                </div>
                <VSpacer/>
                <div class="d-flex flex-row gap-2">
                  <span class="rating">{{ service.rating }}</span>
                  <VIcon 
                    icon="tabler-star-filled"
                    color="#FFCE31"
                    size="17"
                  />
                </div>

              </div>
            </div>
            
          </VCol>
        </VRow>
        <PaginationLinks :data="data.services.meta" v-if="data.services.data.items.length" />
      </VCol>
    </VRow>
  </section>
</template>
