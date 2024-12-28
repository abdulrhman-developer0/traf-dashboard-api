<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import moment from 'moment'
import { useI18n } from 'vue-i18n'
const { t } = useI18n() 
import "moment/dist/locale/ar"
moment.locale('ar_SA')
import defaultavatar from '@images/default-avatar.png'

const props = defineProps({
  data: Array,
})

const tabs = ['تقارير','تقيمات','أنشطة','خدمات']
const activeTab = ref(0)
</script>

<template>
  <Head title="مقدم خدمة" />  
  <section class="admindashboard showpage">

    <VRow class="maindata">
      <VCol cols="6" class="d-flex flex-row gap-6" style="align-items: center;justify-content: center;">
        <img :src="data.photo ? data.photo : defaultavatar" style="border-radius: 50%; width: 194px;height: 194px;" />
        <div>
          <h3>{{ data.name }}</h3>
          <h4>مقدم خدمة</h4>
          <h4>{{ data.phone }}</h4>
        </div>
      </VCol>
      <VCol cols="6" class="d-flex flex-row text-center" style="align-items: center;justify-content: center;">
        
        <div class="flex-fill">
          <h5>
            عدد الجلسات
          </h5>
          <span>{{ data.bookings_stats.total }}</span>
        </div>

        <div class="flex-fill">
          <h5>
            تقييم
          </h5>
          <div class="d-flex flex-row gap-2 mr-2" style="align-items: center;justify-content: center;">
            <span class="rating">{{ data.rating }}</span>
            <VIcon 
              icon="tabler-star-filled"
              color="#FFCE31"
              size="24"
            />
          </div>
        </div>

      </VCol>
    </VRow>

    <VRow>
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
          </VWindowItem>

          <VWindowItem>
          </VWindowItem>


          <VWindowItem>
          </VWindowItem>

          <VWindowItem>
          </VWindowItem>

        </VWindow>
      </VCol>
    </VRow>
  </section>
</template>
