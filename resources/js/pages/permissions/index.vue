<script setup>
import PaginationLinks from "@/components/PaginationLinks.vue";
import moment from 'moment'

import { Head  } from '@inertiajs/vue3'

const props = defineProps({
  data: Object,
})

</script>

<template>
  <section>
    <Head title="Permissions" /> 
    <Alert v-if="$page?.props.flash?.status" :status="$page?.props.flash?.status" />
    <VRow>
      <VCol cols="12" class="d-flex align-center">
        <h4 class="text-h4 mb-1">
          Permissions
        </h4>
        
      </VCol>
    </VRow>

    <VRow>
      <VCol cols="12">
        <VCard>
          <VCardItem
            v-for="(group,key) in data"
            :key="key"
          >

            <VTable class="permission-table text-no-wrap mb-2" v-if="key != 'All'">
              

                <tr>
                  <td colspan="3">
                    <h6 class="text-h5">
                      {{ key }}
                    </h6>
                  </td>
                </tr>
                <tr>
                  <td>
                    ID
                  </td>
                  <td style="width: 50%;">
                    Section
                  </td>
                  <td>
                    Added at
                  </td>
                </tr>

                <tr
                  v-for="permission in group"
                  :key="permission.id"
                >
                  <td>
                    <h6 class="text-h6">
                      {{ permission.id }}
                    </h6>
                  </td>
                  <td>
                    <h6 class="text-h6">
                      {{ permission.name }}
                    </h6>
                  </td>
                  <td>
                    {{ moment(permission.created_at).format("MM/DD/YYYY, h:mm A") }}
                  </td>
                  
                </tr>


            </VTable>
          </VCardItem>
          
        </VCard>

      </VCol>
    </VRow>

  </section>
  
</template>
