<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import Draggable from 'vuedraggable';

const props = defineProps({
    categories: Array,
    tags: Array,
    departments: Array,
    employees: Array
})

const dataObject = useForm({
    'post_category_id' : null,
    'departments' : [],
    'author_option' : 'Hide',
    'authors_ids' : [],
    'title': {},
    'brief': {},
    'meta_keywords' : {},
    'meta_description' : {},
    'tags': [],
})



const postContent = ref([])

const drag = ref(false)


const langs = [{'title':'English','value':'en'},{'title':'Kurdish','value':'ku'},{'title':'Arabic','value':'ar'}]
const activeLang = ref('en')

const authorOptions = ['Hide', 'Writer','Multi'];

const contentTypes = ['title', 'paragraph','image','video','accordions','button','related','comments','quotes','resources']

const headings = ['H1','H2','H3','H4','H5','H6']

const addType = ref(null)


const addContentElement = () => {
    postContent.value.push({'type' : addType.value,'content': {'en':{},'ku':{},'ar':{}}})
}

const removeContentElement = (index) => {
    if(index == 'all'){
        postContent.value = []
    }else {
        postContent.value.splice(index,1)

    }
}


const submit = () => {

    dataObject.transform((data) => ({
        ...data,
        postContent: postContent.value,
    }))
    .post('/dashboard/posts', {
        onSuccess: () => {

        },
    })

}


</script>

<template>
  <section>
    <Head title="Creat Post" /> 
    <VRow>
      <VCol cols="12" class="d-flex align-center">
        
        <h4 class="text-h4 mb-1">
          Creat Post
        </h4>

        <VSpacer />
        
        
      </VCol>
    </VRow>

    <Alert v-if="$page?.props.flash?.status" :status="$page?.props.flash?.status" />


    <VRow>
        <VCol cols="9">
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
                class="mt-6"
                :touch="false"
            >
                <VWindowItem
                    v-for="(lang,key) in langs"
                    :key="lang.value"
                    class="pb-3"
                >
                    <VRow>
                        <VCol cols="8">
                            <VCard class="rounded-0">
                                <VCardTitle class="py-2 border-b-sm">
                                    Post Details ({{ lang.title }})
                                </VCardTitle>
                                <VCardItem class="pa-4">
                                    <VRow>
                                        <VCol cols="12">
                                            <VLabel text="Title" class="mb-1" />
                                            <AppTextField
                                                v-model="dataObject.title[lang.value]"
                                                type="text"
                                            />
                                        </VCol>
                                        <VCol cols="12">
                                            <VLabel text="Brief" class="mb-1" />
                                            <v-textarea
                                                rows="3"
                                                v-model="dataObject.brief[lang.value]"
                                            ></v-textarea>
                                        </VCol>
                                        <VCol cols="6">
                                            <VLabel text="Meta Keywords" class="mb-1" />
                                            <AppTextField
                                                v-model="dataObject.meta_keywords[lang.value]"
                                                type="text"
                                            />
                                        </VCol>
                                        <VCol cols="6">
                                            <VLabel text="Meta Description" class="mb-1" />
                                            <AppTextField
                                                v-model="dataObject.meta_description[lang.value]"
                                                type="text"
                                            />
                                        </VCol>
                                    </VRow>
                                </VCardItem>
                            </VCard>
                        </VCol>
                        <VCol cols="4">
                            <VCard class="rounded-0">
                                <VCardTitle class="py-2 border-b-sm">
                                    Post Image
                                    
                                </VCardTitle>
                                <VCardItem class="pa-4">
                                    <div :class="['dropZone', dragging ? 'dropZone-over' : '']" @dragenter="dragging = true" @dragleave="dragging = false">
                                        <div class="dropZone-info" @drag="onChange">
                                            <span class="fa fa-cloud-upload dropZone-title"></span>
                                            <span class="dropZone-title">Drop file or click to upload</span>
                                            <div class="dropZone-upload-limit-info">
                                            <div>Extension support: png,jpg,jpeg</div>
                                            <div>maximum file size: 5 MB</div>
                                            </div>
                                        </div>
                                        <input type="file">
                                    </div>
                                </VCardItem>
                            </VCard>
                        </VCol>
                    </VRow>
                    <VRow>
                        <VCol>
                            <VCard class="rounded-0">
                                <VCardTitle class="py-2 border-b-sm d-flex">
                                    <div>
                                        Post Contents ({{ lang.title }})
                                    </div>
                                    <VSpacer/>
                                    <VBtn
                                        color="error"
                                        prepend-icon="tabler-trash"
                                        @click="removeContentElement('all')"
                                        size="small"
                                        v-if="postContent.length"
                                    >
                                        Remove All
                                    </VBtn>
                                </VCardTitle>
                                <VCardItem class="py-2 px-1">

                                    <VList class="rounded-0  items ">
                                        <draggable v-model="postContent" group="people"  @start="drag=true"  @end="drag=false" item-key="ID">
                                            <template #item="{element,index}">
                                                <VListItem
                                                    link
                                                    lines="one"
                                                    min-height="66px"
                                                    class="list-item-hover-class ma-0 pa-0"
                                                >
                                                    <VCard class="rounded-0 my-2 mx-2 border" >
                                                        <VCardTitle class="pt-1 px-2 border-b-sm d-flex">
                                                            <div>
                                                                {{ index+1 + ' - ' + element.type }}
                                                            </div>
                                                            <VSpacer/>
                                                            <IconBtn color="error"  @click="removeContentElement(index)">
                                                                <VIcon icon="tabler-trash"/>
                                                            </IconBtn>
                                                        </VCardTitle>
                                                        <VCardItem>

                                                            <div v-if="element.type == 'title'" class="d-flex flex-row gap-2">
                                                                <VRow>
                                                                    <VCol cols="2">
                                                                        <VLabel text="Choose Headding" class="mb-1" />
                                                                        <AppSelect
                                                                            v-model="element.content[lang.value].style"

                                                                            :items="headings"
                                                                        />
                                                                
                                                                    </VCol>
                                                                    <VCol cols="10">
                                                                        <VLabel text="Heading Text" class="mb-1" />
                                                                        <AppTextField
                                                                            v-model="element.content[lang.value].text"

                                                                            type="text"
                                                                        />
                                                                    </VCol>
                                                                </VRow>
                                                            </div>

                                                            <TiptapEditor
                                                                v-if="element.type == 'paragraph'"
                                                                class="border rounded px-3"
                                                                v-model="element.content[lang.value]"
                                                            ></TiptapEditor>

                                                            <div v-if="element.type == 'image'" :class="['dropZone', dragging ? 'dropZone-over' : '']" @dragenter="dragging = true" @dragleave="dragging = false">
                                                                <div class="dropZone-info" @drag="onChange">
                                                                    <span class="fa fa-cloud-upload dropZone-title"></span>
                                                                    <span class="dropZone-title">Drop file or click to upload</span>
                                                                    <div class="dropZone-upload-limit-info">
                                                                    <div>Extension support: png,jpg,jpeg</div>
                                                                    <div>maximum file size: 5 MB</div>
                                                                    </div>
                                                                </div>
                                                                <input type="file">
                                                            </div>

                                                            <div v-if="element.type == 'video'" :class="['dropZone', dragging ? 'dropZone-over' : '']" @dragenter="dragging = true" @dragleave="dragging = false">
                                                                <div class="dropZone-info" @drag="onChange">
                                                                    <span class="fa fa-cloud-upload dropZone-title"></span>
                                                                    <span class="dropZone-title">Drop file or click to upload</span>
                                                                    <div class="dropZone-upload-limit-info">
                                                                    <div>Extension support: mp4,mov</div>
                                                                    <div>maximum file size: 5 MB</div>
                                                                    </div>
                                                                </div>
                                                                <input type="file">
                                                            </div>

                                                            <div v-if="element.type == 'button'" class="d-flex flex-row gap-2">
                                                                <VRow>
                                                                    <VCol cols="5">
                                                                        <VLabel text="Button Text" class="mb-1" />
                                                                        <AppTextField
                                                                            type="text"
                                                                        />
                                                                
                                                                    </VCol>
                                                                    <VCol cols="7">
                                                                        <VLabel text="Button URL" class="mb-1" />
                                                                        <AppTextField
                                                                            type="text"
                                                                        />
                                                                    </VCol>
                                                                </VRow>
                                                            </div>

                                                            <div v-if="element.type == 'related'" class="text-center">
                                                                <h4>Related articles</h4>
                                                            </div>

                                                            <div v-if="element.type == 'comments'" class="text-center">
                                                                <h4>Comments</h4>
                                                            </div>

                                                            <TiptapEditor
                                                                v-if="element.type == 'quotes'"
                                                                class="border rounded px-3"
                                                            ></TiptapEditor>

                                                            <div v-if="element.type == 'resources'">
                                                                <VFileInput
                                                                    chips
                                                                    multiple
                                                                    label="Upload Resources"
                                                                />
                                                            </div>

                                                            <div v-if="element.type == 'accordions'">
                                                                accordions
                                                            </div>
                                                            
                                                            
                                                        </VCardItem>
                                                    </VCard>
                                                </VListItem>
                                            </template>
                                            <template #footer>
                                                <div class="d-flex flex-row gap-2 bg-primary pa-2 mt-4">
                                                    
                                                    <p class="pt-2 pb-0 ma-0">Add Content Element</p>
                                                    <AppSelect
                                                        :items="contentTypes"
                                                        v-model="addType"
                                                    />
                                                    <VBtn
                                                        color="primary"
                                                        prepend-icon="tabler-plus"
                                                        @click="addContentElement"
                                                    >
                                                        Add
                                                    </VBtn>
                                                </div>
                                            </template>
                                        </draggable>
                                    </VList> 

                                    
                                </VCardItem>
                            </VCard>
                        </VCol>
                    </VRow>
                    
                </VWindowItem>

            </VWindow>


        </VCol>


        <VCol cols="3">
            <div class="d-flex justify-content-end mb-6">
                <VBtn
                    color="primary"
                    prepend-icon="tabler-device-floppy"
                    @click="submit(false)"
                >
                    Save Draft
                </VBtn>
                <VBtn
                    color="info"
                    class="mx-2"
                    prepend-icon="tabler-volume"
                    @click="submit(true)"
                >
                    Publish
                </VBtn>

                <VBtn
                    prepend-icon="tabler-trash-x"
                    color="error"
                >
                    Cancel
                </VBtn>
            </div>
            <VCard  class="rounded-0">
                <VCardTitle class="py-2 border-b-sm">
                    Post Options
                </VCardTitle>
                <VCardItem class="pa-4">
                    <VRow>
                        <VCol cols="12">
                            <VLabel text="Post Category" class="mb-1"/>
                            <VAutocomplete
                                v-model="dataObject.post_category_id"
                                :items="categories"
                                item-title="name.en"
                                item-value="id"
                                placeholder="Select Category"
                            ></VAutocomplete>
                        </VCol>
                        <VCol cols="12">
                            <VLabel text="Post Departments" class="mb-1"/>
                            <VAutocomplete
                                v-model="dataObject.departments"
                                :items="departments"
                                item-title="name.en"
                                item-value="id"
                                placeholder="Select Departments"
                                chips
                                multiple
                            ></VAutocomplete>
                        </VCol>
  
                        <VCol cols="12">
                            <VLabel text="Post Tags" class="mb-1"/>
                            <VAutocomplete
                                v-model="dataObject.tags"
                                :items="tags"
                                item-title="name.en"
                                item-value="id"
                                placeholder="Select Tags"
                                chips
                                multiple
                            ></VAutocomplete>
                        </VCol>
                                
                    </VRow>
                </VCardItem>
            </VCard>

            <VCard  class="mt-2 rounded-0">
                <VCardTitle class="py-2 border-b-sm">
                    Post Author
                </VCardTitle>
                <VCardItem class="pa-4">
                    <VRow>
                        <VCol cols="12">
                            <AppSelect
                                :items="authorOptions"
                                v-model="dataObject.author_option"
                            />
                        </VCol>
                        <VCol cols="12" v-if="dataObject.author_option == 'Multi'">
                            <VLabel text="Post Authors" class="mb-1"/>
                            <VAutocomplete
                                v-model="dataObject.authors_ids"
                                :items="employees"
                                item-title="name"
                                item-value="id"
                                placeholder="Select Authors"
                                chips
                                multiple
                                clearable
                                clear-icon="tabler-x"
                            >
                                <template #selection="{ item }">
                                    <VChip>
                                        <template #prepend>
                                            <VAvatar
                                                start
                                                :image="item.raw.avatar"
                                            />
                                        </template>

                                        <span>{{ item.raw.name }}</span>
                                    </VChip>
                                </template>
                            </VAutocomplete>
                        </VCol>
                    </VRow>
                </VCardItem>
            </VCard>
            

        </VCol>
      
    </VRow>
   
  </section>
</template>

<style scoped>
.dropZone {
  width: 100%;
  height: 290px;
  position: relative;
  border: 2px dashed #eee;
}

.dropZone:hover {
  border: 2px solid #2e94c4;
}

.dropZone:hover .dropZone-title {
  color: #1975A0;
}

.dropZone-info {
  color: #A8A8A8;
  position: absolute;
  top: 50%;
  width: 100%;
  transform: translate(0, -50%);
  text-align: center;
}

..dropZone-title {
  color: #787878;
}

.dropZone input {
  position: absolute;
  cursor: pointer;
  top: 0px;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
}

.dropZone-upload-limit-info {
  display: flex;
  justify-content: flex-start;
  flex-direction: column;
}

.dropZone-over {
  background: #5C5C5C;
  opacity: 0.8;
}

.dropZone-uploaded {
  width: 80%;
  height: 200px;
  position: relative;
  border: 2px dashed #eee;
}

.dropZone-uploaded-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  color: #A8A8A8;
  position: absolute;
  top: 50%;
  width: 100%;
  transform: translate(0, -50%);
  text-align: center;
}

.removeFile {
  width: 200px;
}
</style>