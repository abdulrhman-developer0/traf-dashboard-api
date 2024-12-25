<script setup>
import authV1BottomShape from '@images/svg/auth-v1-bottom-shape.svg?raw'
import authV1TopShape from '@images/svg/auth-v1-top-shape.svg?raw'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import ErrorMessages from "@/components/ErrorMessages.vue";

import NavBarI18n from '@core/components/I18n.vue'
import { themeConfig } from '@themeConfig'
import { Head, useForm } from "@inertiajs/vue3";

import BlankLayout from '../../layouts/blank.vue'



definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
  status: String,
})

const { locale } = useI18n({ useScope: 'global' })

const form = useForm({
    email: "",
    password: "",
    remember: null,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};


const isPasswordVisible = ref(false)
</script>

<template>
  <Head title="Login" /> 
  <BlankLayout>
    <div class="auth-wrapper d-flex align-center justify-center pa-4">
      
      <div class="position-relative my-sm-16">
        <VNodeRenderer
          :nodes="h('div', { innerHTML: authV1TopShape })"
          class="text-primary auth-v1-top-shape d-none d-sm-block"
        />

        <VNodeRenderer
          :nodes="h('div', { innerHTML: authV1BottomShape })"
          class="text-primary auth-v1-bottom-shape d-none d-sm-block"
        />
        <!-- 👉 Auth Card -->
        <VCard
          class="auth-card"
          max-width="460"
          :class="$vuetify.display.smAndUp ? 'pa-6' : 'pa-0'"
        >
        
          <VCardItem class="justify-center">
            <VCardTitle>
              <RouterLink to="/">
                <div class="app-logo">
                  <VNodeRenderer :nodes="themeConfig.app.logo" />
                  <h1 class="app-logo-title">
                    {{ themeConfig.app.title }}
                  </h1>
                </div>
              </RouterLink>
            </VCardTitle>
          </VCardItem>

          <VCardText>
            <p class="mb-0 text-lg">
              فضلا تسجيل الدخول إلى حسابك
            </p>
          </VCardText>

          <VCardText>
            <ErrorMessages :errors="form.errors" />

            <VForm @submit.prevent="submit">
              <VRow>
                <!-- email -->
                <VCol cols="12">
                  <AppTextField
                    v-model="form.email"
                    autofocus
                    label="البريد الألكتروني"
                    type="email"
                    placeholder="johndoe@email.com"
                  />
                </VCol>

                <!-- password -->
                <VCol cols="12">
                  <AppTextField
                    v-model="form.password"
                    label="كلمة المرور"
                    placeholder="············"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  />

                  <!-- remember me checkbox -->
                  <div class="d-flex align-center justify-space-between flex-wrap my-6">
                    <VCheckbox
                      v-model="form.remember"
                      label="تذكرني"
                    />

                    <RouterLink
                      class="text-primary"
                    >
                      نسيت كلمة المرور؟
                    </RouterLink>
                  </div>

                  <!-- login button -->
                  <VBtn
                    block
                    type="submit"
                    :loading="form.processing"
                  >
                    دخول
                  </VBtn>
                </VCol>

                
              </VRow>
            </VForm>
          </VCardText>
        </VCard>
      </div>
    </div>
  </BlankLayout>

</template>

<style lang="scss">
@use "../../../styles/@core/template/pages/page-auth";
</style>