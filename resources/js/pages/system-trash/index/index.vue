<script setup>
import moment from 'moment'

const props = defineProps({
  data: Object,
})

const activeTab = ref('Users')
const selectedModule = ref('Users')



const modules = ref([
  
  {
    name: 'Users',
    model: 'User',
    icon: 'tabler-users',
  },
  {
    name: 'Roles',
    model: 'Role',
    icon: 'tabler-users-group',
  },
  {
    name: 'Permissions',
    model: 'Permission',
    icon: 'tabler-lock',
  },
])

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Details', key: 'details' },
  { title: 'Created', key: 'created_at' },
  { title: 'Deleted', key: 'deleted_at' },
  { title: 'Actions', key: 'actions' },
]
</script>

<template>
  <section>
    
    <VRow>
      <VCol
        cols="12"
        class="d-flex align-center"
      >
        <VIcon
          size="24"
          icon="tabler-trash"
          class="ma-1"
        />
        <h3 class="text-2xl font-weight-medium">
          System Trash
        </h3>
      </VCol>
    </VRow>
    <VCard class="pa-3 mt-4">
      <VRow>
        <VCol
          cols="12"
          sm="4"
          lg="3"
          class="position-relative"
        >
          <VTabs
            v-model="activeTab"
            direction="vertical"
            class="v-tabs-pill"
            grow
          >
            <VTab
              v-for="m in modules"
              :key="m.name"
              :value="m.name"
              class="ma-1"
            >
              <VIcon
                :icon="m.icon"
                :size="20"
                start
              />
              {{ m.name }}
            </VTab>
          </VTabs>
        </VCol>
        <VCol
          cols="12"
          sm="8"
          lg="9"
        >
          <VWindow
            v-model="activeTab"
            class="faq-v-window disable-tab-transition"
          >
            <VWindowItem
              v-for="m in modules"
              :key="m.name"
              :value="m.name"
            >
              <VRow class="pa-0 ma-0">
                <VCol cols="12">
                  <div class="d-flex align-center mb-1">
                    <VAvatar
                      rounded
                      color="primary"
                      variant="tonal"
                      class="me-3"
                      size="large"
                    >
                      <VIcon
                        :size="32"
                        :icon="m.icon"
                      />
                    </VAvatar>

                    <div>
                      <h6 class="text-h6">
                        {{ m.name }}
                      </h6>
                    </div>
                  </div>
                </VCol>
                <VCol>
                  <VDataTableServer
                    v-show="!loading"
                    v-model:items-per-page="data.per_page"
                    v-model:page="data.current_page"
                    :items="data.data"
                    :items-length="data.total"
                    :headers="headers"
                    class="text-no-wrap"
                  >

                    <!-- Data -->
                    <template #item.details="{ item }">
                      
                      <div v-if="selectedModule == 'User' || selectedModule == 'Role' || selectedModule == 'Permission'">
                        <h5>Name: {{ item.name ? item.name : 'N/A' }}</h5>
                      </div>
                    </template>
                    

                    <template #item.created_at="{ item }">
                      {{ moment(item.created_at).format("MM/DD/YYYY, h:mm A") }}
                    </template>

                    <template #item.deleted_at="{ item }">
                      {{ moment(item.deleted_at).format("MM/DD/YYYY, h:mm A") }}
                    </template>

                    <!-- Actions -->
                    <template #item.actions="{ item }">
                      <VBtn
                        variant="flat"
                        color="success"
                        size="x-small"
                      >
                        Restore
                      </VBtn>
                    </template>

                    <!-- pagination -->
                   
                  </VDataTableServer>
                </VCol>
              </VRow>
            </VWindowItem>
          </VWindow>
        </VCol>
      </VRow>
    </VCard>
  </section>
</template>


