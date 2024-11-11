<script setup>
import PaginationLinks from "@/components/PaginationLinks.vue";
import moment from 'moment'
import { Head } from '@inertiajs/vue3'

const props = defineProps({
  data: Object,
})


// Headers
const headers = [
  {
    title: 'ID',
    key: 'id',
  },
  {
    title: 'Action',
    key: 'event',
  },
  {
    title: 'Where',
    key: 'subject_type',
  },
  {
    title: 'Record ID',
    key: 'subject_id',
  },
  {
    title: 'By',
    key: 'causer',
  },
  {
    title: 'At',
    key: 'created_at',
  },
]


const resolveAction = action => {
  const statLowerCase = action.toLowerCase()
  if (statLowerCase === 'created')
    return 'success'
  if (statLowerCase === 'updated')
    return 'warning'
  if (statLowerCase === 'deleted')
    return 'error'
  if (statLowerCase === 'restored')
    return 'info'

  return 'primary'
}
</script>

<template>
  <section>
    <Head title="Activity Log" /> 
    <VRow>
      <VCol cols="12">
        <VCard title="Activity Log">
          <!-- SECTION datatable -->
          <VDataTableServer
            v-model:items-per-page="data.per_page"
            v-model:page="data.current_page"
            :items="data.data"
            :items-length="data.total"
            :headers="headers"
            class="text-no-wrap"
          >
            <template #top>
              <VProgressLinear
                v-show="loading"
                indeterminate
                color="primary"
                height="8"
                :rounded="false"
              />
            </template>
            
            <!-- Client -->
            <template #item.id="{ item }">
              <span class="font-weight-medium">{{ item.id }}</span>
            </template>

            <!-- description -->
            <template #item.event="{ item }">
              <VChip
                :color="resolveAction(item.event)"
                size="small"
                label
                class="text-capitalize"
                variant="outlined"
              >
                {{ item.event.slice(0, item.event.length - 1) }}
              </VChip>
            </template>

            <template #item.subject_type="{ item }">
              {{ item.subject_type.replace('App\\Models\\', '') }}
            </template>
            
            <template #item.subject_id="{ item }">
              {{ item.subject_id }}
            </template>
            <template #item.causer="{ item }">
              {{ item.causer ? item.causer.name : '' }}
            </template>
            <template #item.created_at="{ item }">
              {{ moment(item.created_at).format("MM/DD/YYYY, h:mm A") }}
            </template>
            
            
            <!-- pagination -->
            <template #bottom>
              <VDivider />
              <PaginationLinks :links="data.links" />
              
            </template>
          </VDataTableServer>
          <!-- SECTION -->
        </VCard>
      </VCol>
    </VRow>
  </section>
</template>

<style lang="scss">
.app-client-search-filter {
  inline-size: 31.6rem;
}

.text-capitalize {
  text-transform: capitalize;
}

.client-list-name:not(:hover) {
  color: rgba(var(--v-theme-on-background), var(--v-medium-emphasis-opacity));
}
</style>
