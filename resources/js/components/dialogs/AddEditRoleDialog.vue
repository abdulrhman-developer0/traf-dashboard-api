<script setup>
import { useForm, router } from "@inertiajs/vue3";

const props = defineProps({
  selectedRole: {
    type: Object,
    required: false,
  },
  permissionsList: Array,
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const rolePermissions = ref(props.role ? props.role.permissions_names : [])
const loading = ref(false)

const role = ref({'name':'','permissions_names': []})

const viewOnlyElements = ['Dashboard','System Trash','Activity log','System log']

const emit = defineEmits([
  'update:isDialogVisible',
])


const submit = () => {
    loading.value = true
    if(props.selectedRole){
      router.put('/dashboard/roles/'+props.selectedRole.id, role.value, {
        preserveState : true,
        onSuccess: () => {
            loading.value = false
            emit('actionReturn')
            emit('update:isDialogVisible', false)
        },
      });
    }else {
      router.post('/dashboard/roles', role.value, {
        preserveState : true,
        onSuccess: () => {
            loading.value = false
            role.value = {'name':'','permissions_names': []}
            emit('update:isDialogVisible', false)
        },
      });
    }
    
};

const onReset = () => {
  loading.value = false
  emit('update:isDialogVisible', false)
  emit('actionReturn')

}

const selectOrUnSelectAllGroup = (group,type) => {
  group.forEach(item => {
    item.permissions.forEach(permission => {
      if(type == 'select'){
        if(!role.value.permissions_names.includes(permission.name)){
          role.value.permissions_names.push(permission.name)
        }
      }else {
        if(role.value.permissions_names.includes(permission.name)){
          role.value.permissions_names.splice(role.value.permissions_names.indexOf(permission.name),1)
        }
      }
    });

  });
}

watch(props, () => {
  if(props.selectedRole){
    role.value.name = props.selectedRole.name
    role.value.permissions_names = props.selectedRole.permissions_names
  }else {
    role.value.name = ''
    role.value.permissions_names = []

  }
})

</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 900"
    :model-value="props.isDialogVisible"
    @update:model-value="onReset"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="onReset" />

    <VCard class="pa-sm-10 pa-2">
      <VCardText>
        <!-- 👉 Title -->
        <h4 class="text-h4 text-center mb-2">
          {{ props.selectedRole ? 'Edit' : 'Add New' }} Role
        </h4>
        <p class="text-body-1 text-center mb-6">
          Set Role Permissions
        </p>

        <!-- 👉 Role name -->
        <AppTextField
          v-model="role.name"
          label="Role Name"
          placeholder="Enter Role Name"
          class="text-capitalize"
        />

        <h5 class="text-h5 my-6">
          Permissions
        </h5>

        <!-- 👉 Role Permissions -->

        <div
          v-for="(group,key) in permissionsList"
          :key="key"
        >
          <div class="d-flex flex-row">
            <h6 class="text-h5 font-weight-bold"  v-if="key != 'All'">
              {{ key }}
              
            </h6>
            <VSpacer/>
            <VBtn
              color="primary"
              size="x-small"
              class="mr-2"
              @click="selectOrUnSelectAllGroup(group,'select')"
            >
              Select All
            </VBtn>
            <VBtn
              color="error"
              size="x-small"
              @click="selectOrUnSelectAllGroup(group,'unselect')"
            >
              UnSelect All
            </VBtn>
          </div>
          
          <VDivider class="mt-1"  v-if="key != 'All'"/>
          <VTable class="permission-table text-no-wrap mb-6"  v-if="key != 'All'">
            

            <template
              v-for="(element,g) in group"
              :key="element.id"
            >
              <tr v-if="key != 'All'">
                <td style="width: 30%;">
                  <h6 class="text-h6 font-weight-medium my-0">
                    <VIcon
                      icon="tabler-corner-down-right"
                      size="small"
                      class="mx-1"
                    />
                    {{ element.name }}
                  </h6>
                </td>
                <td width="15%">
                  <div class="d-flex justify-end">
                    <VCheckbox
                      label="View"
                      :value="key.replace(/\s/g, '-').toLowerCase()+'-'+element.name.replace(/\s/g, '-').toLowerCase()+'-view'"
                      v-model="role.permissions_names"
                    />
                  </div>
                </td>
                <td>
                  <div class="d-flex justify-end">
                    <VCheckbox
                      label="Add"
                      :value="key.replace(/\s/g, '-').toLowerCase()+'-'+element.name.replace(/\s/g, '-').toLowerCase()+'-add'"
                      v-model="role.permissions_names"
                      v-if="!viewOnlyElements.includes(element.name)"
                    />
                  </div>
                </td>
                <td>
                  <div class="d-flex justify-end">
                    <VCheckbox
                      label="Edit"
                      :value="key.replace(/\s/g, '-').toLowerCase()+'-'+element.name.replace(/\s/g, '-').toLowerCase()+'-edit'"
                      v-model="role.permissions_names"
                      v-if="!viewOnlyElements.includes(element.name)"

                    />
                  </div>
                </td>
                <td>
                  <div class="d-flex justify-end">
                    <VCheckbox
                      label="Delete"
                      :value="key.replace(/\s/g, '-').toLowerCase()+'-'+element.name.replace(/\s/g, '-').toLowerCase()+'-delete'"
                      v-model="role.permissions_names"
                      v-if="!viewOnlyElements.includes(element.name)"

                    />
                  </div>
                </td>
              </tr>
              
            </template>
          </VTable>
        </div>

        <!-- 👉 Actions button -->
        <div class="d-flex align-center justify-center gap-4">
          <VBtn @click="submit" :loading="loading">
            {{ selectedRole ? 'Update' : 'Add'}} Role
          </VBtn>

          <VBtn
            color="secondary"
            variant="tonal"
            @click="onReset"
          >
            Cancel
          </VBtn>
        </div>
      </VCardText>
    </VCard>
  </VDialog>
</template>

<style lang="scss">
.permission-table {
  td {
    border-block-end: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));

    .v-checkbox {
      min-inline-size: 4.75rem;
    }

    &:not(:first-child) {
      padding-inline: 0.5rem;
    }

    .v-label {
      white-space: nowrap;
    }
  }
}
</style>
