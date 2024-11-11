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
    title: 'Operation',
    key: 'operation',
  },
  {
    title: 'Description',
    key: 'description',
  },
  {
    title: 'status',
    key: 'status',
  },
  {
    title: 'At',
    key: 'created_at',
  },
]


const resolveStatus = status => {
  if (status)
    return 'success'
  else 
    return 'error'

  return 'primary'
}


</script>

<template>
  <section>
    <Head title="System Log" /> 

    <VRow>
      <VCol cols="12">
        <VCard title="System Log">

          <!-- SECTION datatable -->
          <VDataTableServer
            v-model:items-per-page="data.per_page"
            v-model:page="data.current_page"
            :items="data.data"
            :items-length="data.total"
            :headers="headers"
            class="text-no-wrap"
          >
            <template #no-data>
              <VAlert v-show="!data" :value="true" color="error" icon="warning">
                No data to show
              </VAlert>
            </template>

            <template #item.operation="{ item }">
              <span class="font-weight-medium">{{ item.operation }}</span>
            </template>

            <template #item.status="{ item }">
              <VChip
                :color="resolveStatus(item.status)"
                size="small"
                label
                class="text-capitalize"
                variant="outlined"
              >
                {{ item.status ? 'Success' : 'Failed' }}
              </VChip>
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
