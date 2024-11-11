<script setup>
import AddNewUserDrawer from './AddNewUserDrawer.vue'
import { avatarText } from '@core/utils/formatters'
//import { paginationMeta } from '@core/utils/helpers'
import moment from 'moment'
import sweetalert2 from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

const Swal = sweetalert2
const searchQuery = ref('')
const totalPage = ref(1)
const totalUsers = ref(0)
const users = ref([])
const isSnackbarVisible = ref(false)
const snackbarMessage = ref('Something went wrong, kindly try again.')
const alartColor = ref('error')
const loading = ref(true)
const btnloading = ref(false)
const user = ref()
const dataId = ref()
const isAddNewUserDrawerVisible = ref(false)
const userRoles = ref([])

const options = ref({
  page: 1,
  itemsPerPage: 10,
  sortBy: [],
  groupBy: [],
  search: undefined,
})

// Headers
const headers = [
  {
    title: 'Name',
    key: 'name',
  },
  {
    title: 'Email',
    key: 'email',
  },
  {
    title: 'Role',
    key: 'role',
  },
  {
    title: 'Status',
    key: 'status',
  },
  {
    title: 'Created at',
    key: 'created_at',
  },
  {
    title: 'Actions',
    key: 'actions',
    sortable: false,
  },
]



const resolveUserStatusVariant = stat => {
  const statLowerCase = stat.toLowerCase()
  if (statLowerCase === 'pending')
    return 'warning'
  if (statLowerCase === 'active')
    return 'success'
  if (statLowerCase === 'inactive')
    return 'secondary'
  
  return 'primary'
}


// 👉 List
const userListMeta = [
  {
    icon: 'tabler-user',
    color: 'primary',
    title: 'Session',
    stats: '21,459',
    percentage: +29,
    subtitle: 'Total Users',
  },
  {
    icon: 'tabler-user-plus',
    color: 'error',
    title: 'Paid Users',
    stats: '4,567',
    percentage: +18,
    subtitle: 'Last week analytics',
  },
  {
    icon: 'tabler-user-check',
    color: 'success',
    title: 'Active Users',
    stats: '19,860',
    percentage: -14,
    subtitle: 'Last week analytics',
  },
  {
    icon: 'tabler-user-exclamation',
    color: 'warning',
    title: 'Pending Users',
    stats: '237',
    percentage: +42,
    subtitle: 'Last week analytics',
  },
]

const resolveUserRoleVariant = role => {
  const roleLowerCase = role.toLowerCase()
  if (roleLowerCase === 'subscriber')
    return {
      color: 'warning',
      icon: 'tabler-circle-check',
    }
  if (roleLowerCase === 'manager')
    return {
      color: 'success',
      icon: 'tabler-user',
    }
  if (roleLowerCase === 'support')
    return {
      color: 'primary',
      icon: 'tabler-chart-pie-2',
    }
  if (roleLowerCase === 'content creator')
    return {
      color: 'info',
      icon: 'tabler-edit',
    }
  if (roleLowerCase === 'admin')
    return {
      color: 'secondary',
      icon: 'tabler-device-laptop',
    }
  
  return {
    color: 'primary',
    icon: 'tabler-user',
  }
}


</script>

<template>
  <section>
    <VSnackbar
      v-model="isSnackbarVisible"
      location="bottom end"
      variant="flat"
      :color="alartColor"
    >
      {{ snackbarMessage }}
    </VSnackbar>
    
    <VRow>
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
      <div class="d-flex align-center flex-wrap gap-4">
        <VBtn
          prepend-icon="tabler-plus"
          @click="isAddNewUserDrawerVisible = true"
        >
          Add New User
        </VBtn>
      </div>
      </VCol>


      <VCol cols="12">
        <VCard>
   

          <!-- SECTION datatable -->
          <VDataTableServer
            v-model:items-per-page="options.itemsPerPage"
            v-model:page="options.page"
            :items="users"
            :items-length="totalUsers"
            :headers="headers"
            :loading="loading"
            class="text-no-wrap"
            @update:options="options = $event"
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
            <template #no-data>
              <VAlert
                v-show="!loading"
                :value="true"
                color="error"
                icon="warning"
              >
                No data to show
              </VAlert>
              <span v-show="loading">Loading...</span>
            </template>
            <!-- User -->
            <template #item.name="{ item }">
              <div class="d-flex align-center">
                <VAvatar
                  size="34"
                  :variant="!item.raw.avatar ? 'tonal' : undefined"
                  class="me-3"
                >
                  <VImg
                    v-if="item.raw.avatar"
                    :src="item.raw.avatar"
                  />
                  <span v-else>{{ avatarText(item.raw.name) }}</span>
                </VAvatar>

                <div class="d-flex flex-column">
                  <h6 class="text-base">
                    <RouterLink
                      :to="{ name: 'user-view-id', params: { id: item.raw.id } }"
                      class="font-weight-medium user-list-name"
                    >
                      {{ item.raw.name }}
                    </RouterLink>
                  </h6>
                  <span class="text-sm text-medium-emphasis">{{ item.raw.title }}</span>
                </div>
              </div>
            </template>

            <!-- Email -->
            <template #item.email="{ item }">
              <span class="font-weight-medium">{{ item.raw.email }}</span>
            </template>

            <!-- 👉 Role -->
            <template #item.role="{ item }">
              <div
                v-for="role in item.raw.roles"
                v-if="item.raw.roles"
                class="d-flex align-center gap-4"
              >
                <VAvatar
                  :size="30"
                  :color="resolveUserRoleVariant(role.name).color"
                  variant="tonal"
                >
                  <VIcon
                    :size="20"
                    :icon="resolveUserRoleVariant(role.name).icon"
                  />
                </VAvatar>
                <span class="text-capitalize">{{ role.name }}</span>
              </div>
              <div
                v-else
                class="d-flex align-center gap-4"
              >
                <span class="text-capitalize">N/A</span>
              </div>
            </template>
            

            <!-- Status -->
            <template #item.status="{ item }">
              <VChip
                :color="resolveUserStatusVariant(item.raw.status)"
                size="small"
                label
                class="text-capitalize"
              >
                {{ item.raw.status }}
              </VChip>
            </template>
            <!-- Status -->
            <template #item.created_at="{ item }">
              {{ moment(item.raw.created_at).format("MM/DD/YYYY, h:mm A") }}
            </template>

            <!-- Actions -->
            <template #item.actions="{ item }">
              <IconBtn @click="deleteUser(item.raw.id)">
                <VIcon icon="tabler-trash" />
              </IconBtn>

              <IconBtn n @click="getData(item.raw.id)" :loading="btnloading && item.raw.id==dataId">
                <VIcon icon="tabler-edit" />
              </IconBtn>
            </template>

            <!-- pagination -->
            <template #bottom>
              <VDivider />
              <div class="d-flex align-center justify-sm-space-between justify-center flex-wrap gap-3 pa-5 pt-3">
                <p class="text-sm text-disabled mb-0">
                  {{ paginationMeta(options, totalUsers) }}
                </p>

                <VPagination
                  v-model="options.page"
                  :length="Math.ceil(totalUsers / options.itemsPerPage)"
                  :total-visible="$vuetify.display.xs ? 1 : Math.ceil(totalUsers / options.itemsPerPage)"
                >
                  <template #prev="slotProps">
                    <VBtn
                      variant="tonal"
                      color="default"
                      v-bind="slotProps"
                      :icon="false"
                    >
                      Previous
                    </VBtn>
                  </template>

                  <template #next="slotProps">
                    <VBtn
                      variant="tonal"
                      color="default"
                      v-bind="slotProps"
                      :icon="false"
                    >
                      Next
                    </VBtn>
                  </template>
                </VPagination>
              </div>
            </template>
          </VDataTableServer>
          <!-- SECTION -->
        </VCard>

        <!-- 👉 Add New User -->
        <AddNewUserDrawer
          v-model:isDrawerOpen="isAddNewUserDrawerVisible"
          @user-data="addNewUser"
          :dataId="dataId"
          v-model:user="user"
          v-model:userRoles="userRoles"

        />
      </VCol>
    </VRow>
  </section>
</template>

<style lang="scss">
.app-user-search-filter {
  inline-size: 31.6rem;
}

.text-capitalize {
  text-transform: capitalize;
}

.user-list-name:not(:hover) {
  color: rgba(var(--v-theme-on-background), var(--v-medium-emphasis-opacity));
}
</style>
