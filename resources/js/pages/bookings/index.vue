<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import moment from 'moment'
import defaultavatar from '@images/default-avatar.png'

import { useI18n } from 'vue-i18n'

const { t } = useI18n() 
import "moment/dist/locale/ar"
moment.locale('ar_SA')
const props = defineProps({
  data: Array,
})

const viewMode = ref('year')

var months = ['يناير', 'فبراير', 'مارس', 'ابريل', 'مايو', 'يونيو', 'يوليو', 'اغسطس', 'سبتمبر', 'اكتوبر', 'نوفمبر', 'ديسمبر'];
var weekDays = [ 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت' ];

const year = ref(null)
const month = ref(null)
const monthName = ref(null)
const day = ref(null)
const dayName = ref(null)
const monthDays = ref([])

const getColor = (status) => {
  if(status == 'pending') return 'pendingService'
  if(status == 'canceled') return 'canceledService'
  if(status == 'confirmed') return 'confirmedService'
  if(status == 'done') return 'doneService'
}

const getDaysArray = (year, month) => {
  var monthIndex = month - 1; // 0..11 instead of 1..12
  var date = new Date(year, monthIndex, 1);
  var result = [];
  var day = {}
  while (date.getMonth() == monthIndex) {
    day = {}
    day.name = weekDays[date.getDay()]
    day.number = date.getDate()
    result.push(day);
    date.setDate(date.getDate() + 1);
  }
  return result;
}

function updateViewMode(mode) {
  viewMode.value = mode === 'year' ? 'year' : 'months'
}

function updateYear(selectedyear) {
  year.value = selectedyear
}

function updateMonth(selectedmonth) {
  month.value = selectedmonth+1
  monthName.value = months[month.value-1]
  monthDays.value = getDaysArray(year.value,month.value)
}

const getData = (mday,name) => {
  day.value = mday
  dayName.value = name

  router.get('/bookings', {
    date: year.value+'-'+month.value+'-'+day.value,
    }, {
      preserveState : true,
      only: ['data'],
      onSuccess: () => {

      
    },
  });
}

onMounted(() => {
    let date = new Date()
    year.value = date.getFullYear()
    month.value = date.getMonth()+1
    monthName.value = months[month.value-1]
    day.value = date.getDate()
    dayName.value = weekDays[date.getDay()]

    monthDays.value = getDaysArray(year.value,month.value)
})

</script>

<template>
  <Head title="الحجوزات" />  
  <section class="admindashboard">
    <VRow class="mb-2 mt-4">
      <VCol class="d-flex flex-row gap-6" style="align-items: center;">
        
        <div>
          <VBtn variant="default" >
            <div class="d-flex flex-row gap-3" style="align-items: center;">
              <VIcon color="#140208" icon="tabler-caret-down-filled" size="15" />
              <span class="monthbutton">{{ monthName }} {{ year }}</span>

            </div>

            <!-- SECTION Menu -->
            <VMenu
              activator="parent"
              width="230"
              location="bottom start"
              offset="5px"
              :close-on-content-click='false'

              
            >
            <v-locale-provider locale="ar">
              <v-date-picker 
                @update:year="updateYear"
                @update:view-mode="updateViewMode"
                @update:month="updateMonth"
                scrollable
                :view-mode="viewMode"
                :hide-header="true"
                border="md"
              ></v-date-picker>
            </v-locale-provider>

            </VMenu>
            <!-- !SECTION -->
          </VBtn>
        </div>
        <div class="d-flex flex-row gap-2 dateshow" style="align-items: center;">
          <div class="d-flex flex-column gap-1 text-center" >
            <span>{{ dayName }}</span>
            <span>{{ monthName }} {{ year }}</span>
          </div>
          <h3>{{ day }}</h3>
        </div>
      </VCol>
    </VRow>

    <VRow class="mb-4">
      <VCol>
        <v-sheet
          class="mx-auto"
          max-width="1000"
          style="background: none;"
        >
          <v-slide-group
            show-arrows
            v-model="day"
          >
            <v-slide-group-item
              v-for="mday in monthDays"
              :key="mday.number"
              v-slot="{ isSelected, toggle }"
              class="d-flex flex-row gap-1"
            >
              <div class="d-flex flex-column dayCard ml-2"  :class="mday.number == day ? 'selectedDay' : ''"
                @click="getData(mday.number,mday.name)"
              >
                <h4>{{ mday.number }}</h4>
                <span>{{ mday.name }}</span>
              </div>
            </v-slide-group-item>
          </v-slide-group>
        </v-sheet>
        
      </VCol>
    </VRow>
    <VTimeline
      side="end"
      align="start"
      line-inset="8"
      truncate-line="start"
      density="compact"
      class="v-timeline--variant-outlined"
      v-if="data.bookings.data.items.length"
    >
      <VTimelineItem
          size="x-small"
          dot-color="rgb(var(--v-theme-surface))"
          v-for="booking in data.bookings.data.items"
          class="bookingTimeLine"
          :class="getColor(booking.status)"
        >
          <template #icon>
            <VIcon
              icon="tabler-circle"
              color="#EFA1AF"
              size="22"

            />
          </template>
          <!-- 👉 Header -->
          <div class="d-flex justify-space-between align-center flex-wrap mb-4">
            <span class="app-timeline-title">
              {{ booking.service_name }}
            </span>
            <span class="app-timeline-meta">
              {{ moment(booking.created_at).fromNow() }}
            </span>
          </div>

          

          <div class="d-flex flex-row gap-10 mb-1">
            <div class="d-flex flex-row gap-2" style="align-items: center;">
              <h3>مقدم الخدمة: </h3>
              <VImg :src="booking.provider_photo ? booking.provider_photo : defaultavatar" style="border-radius: 50%; width: 24px;height: 24px;" />

              <h4>{{ booking.provider_name }}</h4>
              <div class="d-flex flex-row gap-2 mr-2" style="align-items: center;">
                <span class="rating">{{ booking.provider_rating }}</span>
                <VIcon 
                  icon="tabler-star-filled"
                  color="#FFCE31"
                  size="11"
                />
              </div>

            </div>
            <div class="d-flex flex-row gap-2" style="align-items: center;">
              <h3>العميل: </h3>
              <VImg :src="booking.client_photo ? booking.client_photo : defaultavatar" style="border-radius: 50%; width: 24px;height: 24px;" />

              <h4>{{ booking.client_name }}</h4>
              <div class="d-flex flex-row gap-2 mr-2" style="align-items: center;">
                <span class="rating">{{ booking.client_rating }}</span>
                <VIcon 
                  icon="tabler-star-filled"
                  color="#FFCE31"
                  size="11"
                />
              </div>
            </div>

            
          </div>

          <!-- 👉 Content -->
          <p class="app-timeline-text mt-1 mb-1">
            {{ booking.from }} - {{ booking.to }}
          </p>
        </VTimelineItem>

    </VTimeline>
    <VListItem v-else class="border px-1 mt-3 text-center">
      لا يوجد حجوزات لهذا اليوم
    </VListItem>

    <PaginationLinks :links="data.bookings.meta.links" v-if="data.bookings.data.items.length" />

  </section>
</template>
