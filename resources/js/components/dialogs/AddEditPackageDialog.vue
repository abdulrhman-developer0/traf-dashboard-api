<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
const toast = useToast();
import moment from 'moment'

import { useI18n } from 'vue-i18n'
const { t } = useI18n() 

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  package: Object,
})

const emit = defineEmits([
  'update:isDialogVisible', 'actionReturn'
])


const form = useForm({
    name: null,
    price: null,
    price_after: null,
    duration_in_days: null,
    ads_discount: null,
});


const submit = () => {
    if(props.package.id){
        form.put('/pricing/'+props.package.id, {
            preserveState: false,
            onSuccess: () => {
                
                form.reset()
                emit('update:isDialogVisible', false)
                emit('actionReturn')

            },
        });

    }else {
        form.post('/pricing', {
            preserveState: false,
            onSuccess: () => {
                
                form.reset()
                emit('update:isDialogVisible', false)
                emit('actionReturn')

            },
        });

    }
    
};


const onReset = () => {
    form.reset()
    emit('update:isDialogVisible', false)
    emit('actionReturn')
}

watch(props, () => {
    if(props.package){

        form.name = props.package.name
        form.price = props.package.price
        form.price_after = props.package.price_after
        form.duration_in_days = props.package.duration_in_days
        form.ads_discount = props.package.ads_discount
    }
    else {
        form.name = null
        form.price = null
        form.price_after = null
        form.duration_in_days = null
        form.ads_discount = null
    }
})


</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 600"
    :model-value="props.isDialogVisible"
    @update:model-value="onReset"
  >
    <DialogCloseBtn @click="onReset" />

    <VCard class="pa-0"  style="border-radius: 20px;">
      <VCardItem class="pa-0">
        <VCardTitle class="pa-4 pb-0">
           <h4> {{ props.package.id ? 'تعديل' : 'اضافة' }}  باقة</h4>
        </VCardTitle>
        </VCardItem>
        <VCardText class="contentcard">
            <ErrorMessages :errors="form.errors" />

            <VForm @submit.prevent="submit">
                <VRow>
                    <VCol cols="12">
                        <AppTextField
                            v-model="form.name"
                            type="text"
                            placeholder="الإسم"
                            prepend-inner-icon="tabler-pencil"
                        />
                    </VCol>
                    <VCol cols="6">
                        <AppTextField
                            v-model="form.price"
                            type="number"
                            placeholder="السعر قبل"
                            prepend-inner-icon="tabler-cash"
                            suffix="ر.س"
                        />
                    </VCol>
                    <VCol cols="6">
                        <AppTextField
                            v-model="form.price_after"
                            type="number"
                            placeholder="السعر بعد"
                            prepend-inner-icon="tabler-cash"
                            suffix="ر.س"
                        />
                    </VCol>
                    <VCol cols="12">
                        <AppTextField
                            v-model="form.duration_in_days"
                            type="number"
                            placeholder="عدد الأيام"
                            prepend-inner-icon="tabler-clock"
                            suffix="يوم"

                        />
                    </VCol>
                    <VCol cols="12">
                        <AppTextField
                            v-model="form.ads_discount"
                            type="number"
                            placeholder="نسبة خصم الاعلان"
                            prepend-inner-icon="tabler-percentage"
                            suffix="%"

                        />
                    </VCol>
                    <VCol cols="12">
                        <VBtn type="submit"  color="#C4174F" block variant="flat" @click="submit()" :loading="form.processing">
                            {{ props.package.id ? 'تعديل' : 'اضافة' }} 
                        </VBtn>
                    </VCol>
                </VRow>
            </VForm>
            
        </VCardText>
    </VCard>
  </VDialog>
</template>

