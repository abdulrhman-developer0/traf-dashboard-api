<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3'

const page = usePage()
const permissions = computed(() => page.props.user.permissions)

const props = defineProps({
    data: Array,
    dropDowns: Array,
    categories: Array
})


const form = useForm({
    'parent_id' : null,
    'category_id' : null,
    'title' : {'en':'','ku':'','ar':''},
    'type' : 'main',
    'url' : null,
    'target' : '_self',
    'order' : 0,
})

const types = ['main', 'dropdown','category','link']
const targets = ['_self', '_blank']
const langs = [{'title':'English','value':'en'},{'title':'Kurdish','value':'ku'},{'title':'Arabic','value':'ar'}]
const activeLang = ref('en')

const places = ['Header', 'Footer']
const activePlace = ref('Header')


const submit = () => {
    form.post('/dashboard/navigation', {
        onSuccess: () => {
            form.reset()
        },
    });
}


</script>

<template>
  <section>
    <Head title="Navigation Control" /> 
    <Alert v-if="$page?.props.flash?.status" :status="$page?.props.flash?.status" />
    <VRow>
      <VCol cols="12" class="d-flex align-center">
        
        <h4 class="text-h4 mb-1">
            Navigation Control
        </h4>
        
      </VCol>
    </VRow>
    <VRow>
      
    </VRow>
    <VRow>
      <VCol cols="8">
        <VTabs
            v-model="activePlace"
            class="mt-0 mb-1"
        >
            <VTab
                v-for="(place,key) in places"
                :key="key"
                class="pb-3"
            >
            
                <span>{{ place }}</span>
            </VTab>
        </VTabs>

        <VWindow
            v-model="activeLang"
            class="mt-2"
            :touch="false"
        >
            <VWindowItem
                v-for="(place,key) in places"
                :key="key"
                class="pb-3"
            >
                <VCard>
                    <VCardItem class="">
                        <VList class="rounded-0  border py-2 mb-2"
                            v-for="(element, index) in data"
                            :key="element.id"
                        >   
                            <VListItem
                                link
                                lines="one"
                                class="list-item-hover-class ma-0"
                            >
                                
                                <div class="d-flex flex-row align-end">
                                    <p
                                        class="text-lg font-weight-medium mb-1 mr-5"
                                        style=" letter-spacing: 0.4px !important; line-height: 18px;"
                                    >
                                        <VIcon
                                            icon="tabler-link"
                                            size="small"
                                            class="mx-1"
                                        />
                                        {{ element.title.en }}
                                    </p>
                                    <VSpacer/>
                                    <VIcon
                                        size="20"
                                        icon="tabler-trash"
                                        color="error"
                                    />
                                </div>

                            </VListItem>
                            <VListItem
                                link
                                lines="one"
                                min-height="66px"
                                class="list-item-hover-class"
                                v-if="element.child_items.length"
                            >
                                <VList class="rounded-0  pa-2 mb-2">
                                    <VListItem
                                        link
                                        lines="one"
                                        class="list-item-hover-class border mb-1"
                                        v-for="(child, key) in element.child_items"
                                    >
                                        <div class="d-flex flex-row align-end">
                                            <p
                                                class="text-sm font-weight-medium mb-1 mr-5"
                                                style=" letter-spacing: 0.4px !important; line-height: 18px;"
                                            >
                                                <VIcon
                                                    icon="tabler-corner-down-right"
                                                    size="small"
                                                    class="mx-1"
                                                />
                                                {{ child.title.en }}
                                            </p>
                                            <VSpacer/>
                                            <VIcon
                                                size="20"
                                                icon="tabler-trash"
                                                color="error"
                                            />
                                        </div>
                                        
                                    </VListItem>
                                </VList>
                            </VListItem>
                        </VList>
                    </VCardItem>
                </VCard>
            </VWindowItem>
        </VWindow>
        

      </VCol>
      <VCol cols="4">
        <VCard>
            <VCardTitle>
                Add Navigation Element
            </VCardTitle>
            <VCardItem class="mt-0 pt-0">
                <ErrorMessages :errors="form.errors" />
                <VForm @submit.prevent="submit">
                    <VRow>
                        <VCol cols="12">
                            <div class=" border mt-2 pt-2">
                                <VTabs
                                    v-model="activeLang"
                                    class="mt-0 mb-1"
                                >
                                    <VTab
                                        v-for="(lang,key) in langs"
                                        :key="lang.value"
                                        class="pb-3"
                                    >
                                    
                                        <span>{{ lang.title }}</span>
                                    </VTab>
                                </VTabs>

                                <VWindow
                                    v-model="activeLang"
                                    class="mt-2"
                                    :touch="false"
                                >
                                    <VWindowItem
                                        v-for="(lang,key) in langs"
                                        :key="lang.value"
                                        class="pb-3  px-2"
                                    >
                                        <VLabel text="Title" class="mb-1" />
                                        <AppTextField
                                            v-model="form.title[lang.value]"
                                            type="text"
                                        />
                                    </VWindowItem>
                                </VWindow>
                            </div>
                        </VCol>

                        <VCol cols="12">
                            
                            <VLabel text="Choose Element Type" class="mb-1" />
                            <AppSelect
                                v-model="form.type"
                                :items="types"
                            />

                        </VCol>
                        
                        <VCol cols="12">
                            <VLabel text="Parent Menu" class="mb-1" />
                            <AppSelect
                                v-model="form.parent_id"
                                :items="dropDowns"
                                item-title="title.en"
                                item-value="id"

                            />

                        </VCol>
                        <VCol cols="12">
                            <VLabel text="Category" class="mb-1" />
                            <AppSelect
                                v-model="form.category_id"
                                :items="categories"
                                item-title="name.en"
                                item-value="id"
                            />

                        </VCol>
                        <VCol cols="12">
                            <VLabel text="URL" class="mb-1" />
                            <AppTextField
                                v-model="form.url"
                                type="text"
                            />

                        </VCol>

                        <VCol cols="12">
                            <VLabel text="URL Target" class="mb-1" />
                            <AppSelect
                                v-model="form.target"
                                :items="targets"
                            />

                        </VCol>

                        <VCol cols="12">
                            <VBtn
                                color="primary"
                                block
                                :disabled="form.processing"
                                type="submit"
                                :loading="form.processing"
                            >
                                Add
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

