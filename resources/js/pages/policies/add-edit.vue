<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import moment from 'moment'
import { useI18n } from 'vue-i18n'
const { t } = useI18n() 
import "moment/dist/locale/ar"
moment.locale('ar_SA')

const props = defineProps({
  policy: Object,
})

const form = useForm({
  title: props.policy ? props.policy.title : null,
  content: props.policy ? (props.policy.content ? props.policy.content : []) : [{'title':null,'content':null}]
})


const submit = () => {

if(props.policy){
  form.put('/policies/'+props.policy.id, {
      onSuccess: () => {
        form.reset()
      },
  });
}else {
  form.post('/policies', {
    onSuccess: () => {
      form.reset()
    },
});
}
  
};

</script>

<template>
  <Head title="السياسات" />  
  <section class="admindashboard">
    <ErrorMessages :errors="form.errors" />

    <VRow class="addpolicypage">
      <VCol cols="12">
        
        <AppTextField
          v-model="form.title"
          type="text"
          placeholder="اسم السياسة"
          prepend-inner-icon="tabler-pencil"

        />
        
      </VCol>
      <VCol cols="12"  v-for="(item,index) in form.content" class="pb-1">
        <VCard flat class="addpackagecard px-6 py-4">
          <VCardText class="d-flex flex-row gap-2" style="align-items: center;">
            
            <div class="flex-fill">
              <VRow>
                <VCol cols="12" class="py-0">
                  
                  <AppTextField
                    v-model="item.title"
                    type="text"
                    class="mb-4"
                    placeholder="عنوان السياسة"
                    prepend-inner-icon="tabler-pencil"

                  />
                </VCol>
                <VCol cols="12" class="py-0 m-0">
                  <v-textarea
                    v-model="item.content"
                    rows="4"
                    placeholder="محتوى السياسة"
                    auto-grow
                    prepend-inner-icon="tabler-pencil"

                  ></v-textarea>
                </VCol>
              </VRow>
            </div>
            <IconBtn @click="form.content.splice(index,1)">
              <VIcon icon="tabler-trash" size="20" color="error" density="comfortable" />
            </IconBtn>
          </VCardText>
        </VCard>
      </VCol>
      <VCol cols="12" class="text-center">
        <IconBtn @click="form.content.push({'title':null,'content':null})" 
        vaiant="outlined" size="40" >
          <VIcon icon="tabler-plus" size="40" color="#E55175" density="comfortable" />
        </IconBtn>
      </VCol>
      <VCol cols="12">
        <VBtn block color="#C4174F" size="large" style="border-radius: 8px;" @click="submit" :loading="form.processing">
          {{ props.policy ? 'تعديل' : 'اضافة' }}

        </VBtn>
      </VCol>
    </VRow>
  </section>
</template>
