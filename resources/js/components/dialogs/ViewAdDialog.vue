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
  ad: Object,
})

const emit = defineEmits([
  'update:isDialogVisible', 'actionReturn'
])


const form = useForm({
    status: null,
    notes: null
});

const showNotes = ref(false)

const action = (status) => {
    form.status = status
    if(status == 'rejected'){
        showNotes.value = true
    }else {
        submit()
    }
}

const submit = () => {
    form.put('/ads/'+props.ad.id, {
        preserveState: false,
        onSuccess: () => {
            
            form.reset()
            showNotes.value = false
            emit('update:isDialogVisible', false)

        },
    });
    
};


const onReset = () => {
    showNotes.value = false

    emit('update:isDialogVisible', false)
}


</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 875"
    :model-value="props.isDialogVisible"
    @update:model-value="onReset"
  >
    <DialogCloseBtn @click="onReset" />

    <VCard class="viewAdDialog"  v-if="!showNotes">
        <VCardItem>
            <VImg :src="ad.url" />
        </VCardItem>
        <VCardItem>
            <div>
                <h3 class="ml-10">عدد الأيام: {{ad.duration_in_days}} يوم</h3>
                <h3>السعر: {{ad.total_price}} ر.س</h3>
            </div>
        </VCardItem>
        <VCardItem v-if="ad.notes">
            <div>
                <h3 class="ml-2">سبب الرفض</h3>
                <p>{{ad.notes}}</p>
            </div>
        </VCardItem>
        <VCardItem class="actionbuttons mt-5"  v-if="ad.status == 'under-review'">
            <VBtn color="#C4174F" variant="flat" @click="action('pending-payment')" :loading="form.processing">قبول</VBtn>
            <VBtn color="#C4174F" class="mx-2" variant="outlined" @click="action('rejected')">رفض</VBtn>
        </VCardItem>
    </VCard>
    <VCard class="viewAdDialog"  v-if="showNotes">
        <VCardItem>
            <VLabel text="سبب الإلغاء" class="mb-2" />
            <v-textarea
                v-model="form.notes"
                rows="4"
                auto-grow
                prepend-inner-icon="tabler-pencil"

            ></v-textarea>
        </VCardItem>
        <VCardItem class="actionbuttons mt-5">
            <VBtn color="#C4174F" variant="flat" @click="submit" :loading="form.processing">ارسال</VBtn>
            <VBtn color="#C4174F" class="mx-2" variant="outlined" @click="onReset">إلغاء</VBtn>
        </VCardItem>
    </VCard>
  </VDialog>
</template>

