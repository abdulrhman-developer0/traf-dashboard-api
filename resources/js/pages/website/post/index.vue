<script setup>
import PaginationLinks from "@/components/PaginationLinks.vue";
import moment from 'moment'
import { Head, useForm, usePage, router } from '@inertiajs/vue3'
const page = usePage()
const permissions = computed(() => page.props.user.permissions)

const props = defineProps({
  data: Object,
})

const isDialogVisible = ref(false)
const selectedItem = ref(null)

const search = ''

// Headers
const headers = [
  {
    title: 'ID',
    key: 'id',
  },
  {
    title: 'Name',
    key: 'name',
  },
  {
    title: 'status',
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

const form = useForm({});

const deleteRecord = (id) => {
  // This is a simple confirmation message, to be enhanced later with a confirmation model - Abed Said
  if(confirm("Do you really want to delete?")){

    form.delete(`/dashboard/posts/${id}`);
 
  }
}

const actionReturn = () => {
  selectedItem.value = null

}

</script>

<template>
  <section>
    <Head title="Posts" /> 
    <Alert v-if="$page?.props.flash?.status" :status="$page?.props.flash?.status" />
    <VRow>
      <VCol cols="12" class="d-flex align-center">
        
        <h4 class="text-h4 mb-1">
          Posts
        </h4>
        
      </VCol>
    </VRow>
    <VRow>
      <VCol cols="12">
        <VCard>
          <VCardText class="d-flex justify-space-between flex-wrap">
            <div style="inline-size: 15.625rem;">
              <AppTextField
                v-model="search"
                placeholder="Search Posts"
              />
            </div>
            <VBtn
              prepend-icon="tabler-plus"
              @click="router.get('/dashboard/posts/create')"
              v-if="permissions.includes('system-setting-manage-collages-add')"

            >
              Add Post
            </VBtn>
          </VCardText>
          
          
          <!-- SECTION datatable -->
          <VDataTable
            :items="data.data"
            :headers="headers"
            class="text-no-wrap"
          >
            <template #item.name="{ item }">
              <span class="font-weight-medium">{{ item.title?.en }}</span>
            </template>

            <template #item.status="{ item }">
              <VChip
                    :color="item.status == 'Draft' ? 'error' : 'success'"
                    size="small"
                    label
                    class="text-capitalize"
              >
                {{ item.status }}
              </VChip>
            </template>

            <template #item.created_at="{ item }">
              {{ moment(item.created_at).format("MM/DD/YYYY, h:mm A") }}
            </template>

            <!-- Actions -->
          <template #item.actions="{ item }">
            

            <IconBtn  @click="isDialogVisible = true, selectedItem = item"
            v-if="permissions.includes('system-setting-manage-collages-edit')"
            >
              <VIcon icon="tabler-pencil"/>
            </IconBtn>
            <IconBtn @click="deleteRecord(item.id)"
            v-if="permissions.includes('system-setting-manage-collages-delete')"
            >
              <VIcon icon="tabler-trash"  />
            </IconBtn>

          </template>
            
            
            <!-- pagination -->
            <template #bottom>
              <VDivider />
              <PaginationLinks :links="data.links" />
              
            </template>
          </VDataTable>
          <!-- SECTION -->
        </VCard>

      </VCol>
    </VRow>
    <AddEditPostCategoryDialog
      v-model:isDialogVisible="isDialogVisible"
      v-model:category="selectedItem"
      @action-return="actionReturn"
    />
  </section>
</template>

