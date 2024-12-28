<script setup>
import { Head, useForm, router, usePage  } from '@inertiajs/vue3'
import defaultavatar from '@images/default-avatar.png'

const page = usePage()
const permissions = computed(() => page.props.user.permissions)

const props = defineProps({
    user : Object,
})

const dataObject = ref({
    email: props.user.email ? props.user.email : '',
    name: props.user.name ? props.user.name : '',

})

const avatarFile = ref(null)
const avatarFileImage = ref(null)


const openFileDialog = () => {
    document.getElementById('avatar-input-file').click();
}

const uploadAvatar = () => {
    avatarFileImage.value = URL.createObjectURL(avatarFile.value)
}

const resetAvatar = () => {
    avatarFile.value = ''
    avatarFileImage.value = ''
}

const avatarSrc = () => {
    if(props.user && props.user.avatar && !avatarFileImage.value)
        return props.user.avatar
        
    else if(avatarFileImage.value)
        return avatarFileImage.value

    else
        return defaultavatar
}

const submit = () => {
    dataObject.value.newAvatar = avatarFile.value
    router.post('/my-profile', dataObject.value, {
        preserveState : false,
        onSuccess: () => { 

        }, 
      
    });

}


</script>

<template>
  <section>
    <Head title="ملفي الشخصي" /> 

    <Alert v-if="$page?.props.flash?.status" :status="$page?.props.flash?.status" />
    <VRow >
      <VCol cols="12" class="pb-0">
        <div class="d-flex justify-content-between">
            <VSpacer/>
                <VBtn
                    prepend-icon="tabler-device-floppy"
                    color="success"
                    class="mt-2"
                    @click="submit"
                >
                    حفظ 
              </VBtn>
        </div>
      </VCol>
    </VRow>

    <VRow>
        <VCol cols="5">
            <VCard class="d-flex justify-content-center justify-end gap-20 pa-3 mb-4">
                <VImg :src="avatarSrc()" width="3rem" @click="openFileDialog" style="cursor: pointer;"/>

                <div class="justify-end mt-15">
                    <VBtn 
                        class="bg-primary text-white ml-3"
                        @click="openFileDialog"
                    >
                        رفع صورة جديدة
                    </VBtn>
                    <VBtn
                        color="secondary"
                        variant="tonal"
                        @click="resetAvatar"
                    >
                        استعادة
                    </VBtn>
                    <p class="mt-2">الملفات المسموح بها: JPG, GIF او PNG.</p>
                    <VFileInput
                        accept="image/*"
                        label="File input"
                        style="display: none;"
                        id="avatar-input-file"
                        @change="uploadAvatar"
                        v-model="avatarFile"
                    />
                </div>
            </VCard>
            
            <VCard>
                <VCardItem>
                    <VRow>
                        <VCol cols="6">
                            <VLabel text="الإسم" class="mb-1"/>
                            <AppTextField
                                v-model="dataObject.name"
                                type="text"
                            />
                        </VCol>
                        <VCol cols="6">
                            <VLabel text="البريد الألكتروني" class="mb-1"/>
                            <AppTextField
                                v-model="dataObject.email"
                                type="text"
                            />
                        </VCol>
                        
                    </VRow>
                </VCardItem>
            </VCard>
        </VCol>


    </VRow>



  </section>
</template>


