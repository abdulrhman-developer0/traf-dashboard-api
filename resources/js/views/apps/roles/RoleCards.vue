<script setup>
import girlUsingMobile from '@images/pages/girl-using-mobile.png'
import { useForm, router, usePage } from "@inertiajs/vue3";
const page = usePage()
const permissions = computed(() => page.props.user.permissions)
import { useI18n } from 'vue-i18n'
const { t } = useI18n() 

const props = defineProps({
  roles: Array,
  permissionsList: Array,
});

const isRoleDialogVisible = ref(false)
const roleDetail = ref(null)

const editPermission = value => {

  isRoleDialogVisible.value = true
  roleDetail.value = value
}

const actionReturn = () => {
  roleDetail.value = null

}

const deleteRecord = (id) => {
  // This is a simple confirmation message, to be enhanced later with a confirmation model - Abed Said
  if(confirm(t('"Do you really want to delete?"'))){

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
            {{ $t('Total users:') }} {{ item.users_count }} 
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
                  v-if="item.name == 'Admin'"

                >
                  
                  {{ $t('View Role') }} (<span class="errortext"> {{ $t('This role cannot be edited') }}</span>)
                </a>
                <a
                  href="javascript:void(0)"
                  @click="editPermission(item)"
                  v-else

                >
                  
                  {{ $t('Edit Role') }}
                </a>
                
              </div>
              
            </div>
            <IconBtn>
              <VIcon
                icon="tabler-trash"
                class="text-high-emphasis"
                color="error"
                @click="deleteRecord(item.id)"
                v-if="item.name != 'Admin'"

              />
            </IconBtn>
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
            cols="12"
            class="d-flex flex-row justify-center align-center "
          >
            
            <VBtn
              size="large"
              variant="flat"
            >
                
                {{ $t('Add New Role') }}
            </VBtn>
            
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
</template>
