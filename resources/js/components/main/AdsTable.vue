<script setup>
import { router } from '@inertiajs/vue3'

import moment from 'moment'
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
    title: 'مدة الإعلان',
    key: 'duration_in_days',
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

</script>

<template>
    <VCard flat class="px-5 tablemaincard">
        <VCardText class="py-0 px-0">
            <VDataTable
                :items="data.data.items"
                item-value="id"
                :headers="headers"
                :items-per-page="100"
                class="text-no-wrap"
            >


                <template #item.type="{ item }">
                    <span v-if="item.is_personal">مقدم خدمة</span>
                    <span v-else>مشغل</span>
                </template>
                <template #item.created_at="{ item }">
                    {{ moment(item.created_at).format("DD MMMM, YYYY") }}
                </template>

                <!-- pagination -->
                <template #bottom>
                
                
                </template>

            </VDataTable>
        </VCardText>
    </VCard>
</template>


