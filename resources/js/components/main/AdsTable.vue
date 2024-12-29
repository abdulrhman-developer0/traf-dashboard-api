<script setup>
import { router } from '@inertiajs/vue3'

import ar from 'apexcharts/dist/locales/ar.json'

const props = defineProps({
  data: Array,
})



</script>

<template>
    <VCard flat class="px-5 tablemaincard">
        <VCardText class="py-0 px-0">
            <VDataTable
                :items="data.items"
                item-value="id"
                :headers="headers"
                :items-per-page="100"
                class="text-no-wrap"
            >


                <!-- pagination -->
                <template #bottom>
                
                
                </template>

            </VDataTable>
        </VCardText>
    </VCard>
</template>


