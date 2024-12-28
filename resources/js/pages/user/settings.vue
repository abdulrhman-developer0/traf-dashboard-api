<script setup>

import { Head,useForm  } from '@inertiajs/vue3'



const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.put(route("change-password"), {
        onSuccess: () => form.reset(),
        onError: () => form.reset(),
        preserveScroll: true,
    });
};

</script>

<template>
  <section>
    <Head title="اعداداتي" /> 
    <Alert v-if="$page?.props.flash?.status" :status="$page?.props.flash?.status" />
    <VRow>
      <VCol cols="12">
        <VCard flat class="tablemaincard">
            <VCardText>
                <h3>تغيير كلمة المرور</h3>
            </VCardText>
            <VCardItem>
                <VForm @submit.prevent="submit" class="">
                    <ErrorMessages :errors="form.errors" />

                    <VRow>
                        <VCol cols="6">
                            <VLabel text="كلمة المرور الحالية" class="mb-1"/>
                            <AppTextField
                                type="text"
                                v-model="form.current_password"
                            />
                        </VCol>
                    </VRow>

                    <VRow>
                        <VCol cols="6">
                            <VLabel text="كلمة المرور الجديدة" class="mb-1"/>
                            <AppTextField
                                type="text"
                                v-model="form.password"
                            />
                        </VCol>
                    </VRow>

                    <VRow>
                        <VCol cols="6">
                            <VLabel text="تأكيد كلمة المرور الجديدة" class="mb-1"/>
                            <AppTextField
                                type="text"
                                v-model="form.password_confirmation"
                            />
                        </VCol>
                    </VRow>

                    <VRow>
                        <VCol cols="6" class="d-flex justify-center align-center gap-2">
                            <VBtn
                                type="submit"
                                :loading="form.processing"
                            >
                                تغير كلمة المرور
                            </VBtn>
                
                        </VCol>
                    </VRow>

                </VForm>
                
            </VCardItem>
        </VCard>
      </VCol>
    </VRow>
  </section>
</template>
