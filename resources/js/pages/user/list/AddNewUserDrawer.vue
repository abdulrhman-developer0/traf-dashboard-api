<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import ErrorMessages from "@/components/ErrorMessages.vue";

import { useForm } from "@inertiajs/vue3";


const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  
})

const emit = defineEmits([
  'update:isDrawerOpen',
])


// 👉 drawer close
const closeNavigationDrawer = () => {
  form.reset()
  emit('update:isDrawerOpen', false)
  
}

const form = useForm({
    name: "",
    email: "",
    password: "",
});



const submit = () => {
    form.post('/dashboard/users', {
        onSuccess: () => {
          form.reset()
          emit('update:isDrawerOpen', false)

        },
    });
};


</script>

<template>
  <VNavigationDrawer
    temporary
    :width="400"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
   
    <!-- 👉 Title -->
    <AppDrawerHeaderSection
      title="Add new user"
      @cancel="closeNavigationDrawer"
    />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <ErrorMessages :errors="form.errors" />
          <VForm @submit.prevent="submit">
            <VRow>
              <!-- name -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.name"
                  autofocus
                  label="Name"
                  type="text"
                  placeholder="John Doe"
                />
              </VCol>
              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.email"
                  label="Email"
                  type="email"
                  placeholder="johndoe@email.com"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.password"
                  label="Password"
                  placeholder="············"
                  type="text"
                />
              </VCol>
              <VCol cols="12">
                <VBtn
                  block
                  type="submit"
                  :loading="form.processing"
                >
                  Save
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
