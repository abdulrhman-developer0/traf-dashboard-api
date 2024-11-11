<script setup>
import girlUsingMobile from '@images/pages/girl-using-mobile.png'
import { useForm, router, usePage } from "@inertiajs/vue3";
const page = usePage()
const permissions = computed(() => page.props.user.permissions)

const props = defineProps({
  roles: Array,
  permissionsList: Array,
});

const isRoleDialogVisible = ref(false)
const isUsersDialogVisible = ref(false)
const roleDetail = ref(null)

const editPermission = value => {

  isRoleDialogVisible.value = true
  roleDetail.value = value
}

const actionReturn = () => {
  roleDetail.value = null

}

const showRoleUsers = (role) => {
  isUsersDialogVisible.value = true
  roleDetail.value = role

}

const deleteRecord = (id) => {
  // This is a simple confirmation message, to be enhanced later with a confirmation model - Abed Said
  if(confirm("Do you really want to delete?")){

    router.delete(`/dashboard/roles/${id}`);
 
  }
}

</script>

<template>
  <VRow>
    <!-- 👉 Roles -->
    <VCol
      v-for="item in roles"
      :key="item.id"
      cols="12"
      sm="6"
      lg="4"
    >
      <VCard>
        <VCardText class="d-flex align-center pb-4">
          <div class="text-body-1">
            Total {{ item.users?.length }} users
          </div>

          <VSpacer />

          
        </VCardText>

        <VCardText>
          <div class="d-flex justify-space-between align-center">
            <div>
              <h5 class="text-h5 text-capitalize">
                {{ item.name }}
              </h5>
              <div class="d-flex align-center">
                <a
                  href="javascript:void(0)"
                  @click="editPermission(item)"
                  v-if="permissions.includes('roles-and-permissions-roles-edit')"

                >
                  Edit Role
                </a>
              </div>
            </div>
            <div>
              <IconBtn>
                <VIcon
                  icon="tabler-trash"
                  class="text-high-emphasis"
                  color="error"
                  @click="deleteRecord(item.id)"
                  v-if="permissions.includes('roles-and-permissions-roles-delete')"

                />
              </IconBtn>
              <IconBtn>
                <VIcon
                  icon="tabler-users"
                  color="info"
                  @click="showRoleUsers(item)"

                />
              </IconBtn>
            </div>
          </div>
        </VCardText>
      </VCard>
    </VCol>

    <!-- 👉 Add New Role -->
    <VCol
      cols="12"
      sm="6"
      lg="4"
      v-if="permissions.includes('roles-and-permissions-roles-add')"

    >
      <VCard
        class="h-100"
        :ripple="false"
        @click="isRoleDialogVisible = true"

      >
        <VRow
          no-gutters
          class="h-100"
        >
          <VCol
            cols="5"
            class="d-flex flex-row justify-center align-center "
          >
            <VIcon
              icon="tabler-plus"
              size="xx-large"
              class="mx-1"
            />
            <VBtn
              size="small"
            >
                Add New Role
            </VBtn>
            
          </VCol>

          <VCol cols="7">
            <VCardText class="d-flex flex-column align-end justify-end gap-4">
              
              <div class="text-end mt-5">
                Add new role,<br> if it doesn't exist.
              </div>
            </VCardText>
          </VCol>
        </VRow>
      </VCard>
    </VCol>
  </VRow>
  <AddEditRoleDialog
    v-model:is-dialog-visible="isRoleDialogVisible"
    v-model:selectedRole="roleDetail"
    :permissionsList="permissionsList"
    @action-return="actionReturn"
  />
  <ShowRoleUsersDialog
    v-model:isUsersDialogVisible="isUsersDialogVisible"
    :role="roleDetail"

  />
</template>
