<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import moment from 'moment'
import { useI18n } from 'vue-i18n'
const { t } = useI18n() 
import "moment/dist/locale/ar"
moment.locale('ar_SA')
const props = defineProps({
  data: Array,
})


const deleteRecord = (id) => {
  if(confirm('هل انت متأكد من الحذف؟')){
    router.delete(`/policies/${id}`);
  }
}

</script>

<template>
  <Head title="السياسات" />  
  <Alert v-if="$page?.props.flash?.status" :status="$page?.props.flash?.status" />

  <section class="admindashboard">
    <VRow>
      <VCol cols="12">
        <VBtn color="#E55175" variant="default" class="addbtn" @click="router.get('/policies/create')" >
          <VIcon icon="tabler-plus" class="ml-2" />

          اضافة سياسة جديدة
        </VBtn>

      </VCol>
      <VCol cols="12"  v-for="policy in data.policies.data" class="pb-1">
        <VCard flat class="policyCard">
          <VCardText class="d-flex flex-row pa-0 ma-0 mb-3" style="align-items: center;">
            <h3 class="mt-1">{{ policy.title }}</h3>
            <VSpacer/>
            <VIcon icon="tabler-pencil" style="cursor: pointer;" class="ml-2" @click="router.get('/policies/'+policy.id)" />
            <VIcon icon="tabler-trash" color="error" style="cursor: pointer;" @click="deleteRecord(policy.id)" />

          </VCardText>
          <VCardText class="row pa-0 ma-0">
            <p class="mb-0" v-for="c in policy.content"><b class="ml-1">{{ c.title }}:</b>{{ c.content }}</p>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </section>
</template>
