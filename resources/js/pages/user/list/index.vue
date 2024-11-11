<script setup>
import AddNewUserDrawer from './AddNewUserDrawer.vue'
import PaginationLinks from "@/components/PaginationLinks.vue";

import { usePage, Head } from '@inertiajs/vue3'

const props = defineProps({
  users: Object,
})

const page = usePage()

const permissions = computed(() => page.props.user.permissions)

const isAddNewUserDrawerVisible = ref(false);

</script>

<script>
const widgetData = ref([
  {
    title: 'Session',
    value: '21,459',
    change: 29,
    desc: 'Total Users',
    icon: 'tabler-users',
    iconColor: 'primary',
  },
  {
    title: 'Paid Users',
    value: '4,567',
    change: 18,
    desc: 'Last Week Analytics',
    icon: 'tabler-user-plus',
    iconColor: 'error',
  },
  {
    title: 'Active Users',
    value: '19,860',
    change: -14,
    desc: 'Last Week Analytics',
    icon: 'tabler-user-check',
    iconColor: 'success',
  },
  {
    title: 'Pending Users',
    value: '237',
    change: 42,
    desc: 'Last Week Analytics',
    icon: 'tabler-user-search',
    iconColor: 'warning',
  },
])

// Headers
const headers = [
  {
    title: 'User',
    key: 'user',
  },
  {
    title: 'Email',
    key: 'email',
  },
  {
    title: 'Actions',
    key: 'actions',
    sortable: false,
  },
]


</script>


<template>
  <Head title="Users" /> 
  <VLayout >
    <Alert v-if="$page?.props.flash?.status" :status="$page?.props.flash?.status" />

    <!-- 👉 Add New User -->
      <AddNewUserDrawer
        v-model:isDrawerOpen="isAddNewUserDrawerVisible"
      />
      <VMain>
        <!-- 👉 Widgets -->
      <div class="d-flex mb-6">
        <VRow>
          <template
            v-for="(data, id) in widgetData"
            :key="id"
          >
            <VCol
              cols="12"
              md="3"
              sm="6"
            >
              <VCard>
                <VCardText>
                  <div class="d-flex justify-space-between">
                    <div class="d-flex flex-column gap-y-1">
                      <div class="text-body-1 text-high-emphasis">
                        {{ data.title }}
                      </div>
                      <div class="d-flex gap-x-2 align-center">
                        <h4 class="text-h4">
                          {{ data.value }}
                        </h4>
                        <div
                          class="text-base"
                          :class="data.change > 0 ? 'text-success' : 'text-error'"
                        >
                          ({{ prefixWithPlus(data.change) }}%)
                        </div>
                      </div>
                      <div class="text-sm">
                        {{ data.desc }}
                      </div>
                    </div>
                    <VAvatar
                      :color="data.iconColor"
                      variant="tonal"
                      rounded
                      size="42"
                    >
                      <VIcon
                        :icon="data.icon"
                        size="26"
                      />
                    </VAvatar>
                  </div>
                </VCardText>
              </VCard>
            </VCol>
          </template>
        </VRow>
      </div>
      
      <VRow class="mb-4">
        <VCol
          cols="12"
          class="d-flex align-center"
        >
          <VIcon
            size="24"
            icon="tabler-users"
            class="ma-1"
          />
          <h3 class="text-2xl font-weight-medium">
            Users and Roles
            <VIcon
              size="16"
              icon="tabler-arrow-big-right"
              class="ma-1"
            />
            Users
          </h3>
          <VSpacer />
        
        </VCol>
      </VRow>

      <VCard  class="mb-6">
        
        
        <VDivider />

        <VCardText class="d-flex flex-wrap gap-4">
          
          <VSpacer />

          <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
            <!-- 👉 Search  -->
            <div style="inline-size: 15.625rem;">
              <AppTextField
                v-model="searchQuery"
                placeholder="Search Users"
              />
            </div>

            

            <!-- 👉 Add user button -->
            <VBtn
              v-if="permissions.includes('user create')"
              prepend-icon="tabler-plus"
              @click="isAddNewUserDrawerVisible = true"
            >
              Add New User
            </VBtn>
          </div>
        </VCardText>

        <VDivider />
        <!-- SECTION datatable -->
        <VDataTableServer
          v-model:items-per-page="itemsPerPage"
          v-model:model-value="selectedRows"
          v-model:page="page"
          :items="users.data"
          item-value="id"
          :items-length="totalUsers"
          :headers="headers"
          class="text-no-wrap"
          show-select
          @update:options="updateOptions"
        >
          <!-- User -->
          <template #item.user="{ item }">
            <div class="d-flex align-center gap-x-4">
              <VAvatar
                size="34"
                :variant="!item.avatar ? 'tonal' : undefined"
                :color="!item.avatar ? 'info' : undefined"
              >
                <VImg
                  v-if="item.avatar"
                  :src="item.avatar"
                />
                <span v-else></span>
              </VAvatar>
              <div class="d-flex flex-column">
                <h6 class="text-base">
                  {{ item.name }}
                </h6>
                <div class="text-sm">
                  {{ item.email }}
                </div>
              </div>
            </div>
          </template>

        
          <!-- Actions -->
          <template #item.actions="{ item }">
            <IconBtn >
              <VIcon icon="tabler-trash" />
            </IconBtn>

            <IconBtn>
              <VIcon icon="tabler-eye" />
            </IconBtn>

            <VBtn
              icon
              variant="text"
              color="medium-emphasis"
            >
              <VIcon icon="tabler-dots-vertical" />
              <VMenu activator="parent">
                <VList>
                  <VListItem>
                    <template #prepend>
                      <VIcon icon="tabler-eye" />
                    </template>

                    <VListItemTitle>View</VListItemTitle>
                  </VListItem>

                  <VListItem link>
                    <template #prepend>
                      <VIcon icon="tabler-pencil" />
                    </template>
                    <VListItemTitle>Edit</VListItemTitle>
                  </VListItem>

                  <VListItem @click="deleteUser(item.id)">
                    <template #prepend>
                      <VIcon icon="tabler-trash" />
                    </template>
                    <VListItemTitle>Delete</VListItemTitle>
                  </VListItem>
                </VList>
              </VMenu>
            </VBtn>
          </template>

          <!-- pagination -->
          <template #bottom>
            <PaginationLinks :links="users.links" />

            
          </template>
        </VDataTableServer>
        <!-- SECTION -->

      
      </VCard>
      </VMain>
  </VLayout>
  
</template>
