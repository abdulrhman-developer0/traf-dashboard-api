<script setup>
import ar from 'apexcharts/dist/locales/ar.json'

const props = defineProps({
  title: String,
  value: Number,
  icon: String,
})



</script>

<template>
  <VCard class="statecard py-5">
    <VCardText class="d-flex flex-row gap-4 py-0 px-0 pr-2 pl-5">
    
        <div style="border-left: 3px solid #C4174F;height: 53px"></div>

        <div class="d-flex flex-column gap-5">
            <h4 class="mt-2">
                {{ props.title }}
            </h4>
            <h5>
                {{ props.value }}
            </h5>
            
        </div>
        <VSpacer/>
        <VAvatar
            rounded-0
            size="28"
            style="background: #F9E8ED;border-radius: 0;"
        >
            <VIcon
                :icon="props.icon"
                size="18"
                style="color: #C4174F"
            />
        </VAvatar>
        
        </VCardText>
        <VCardText class="py-0 px-0 pr-6 ">
        <p class="mb-0  pt-5">
            منذ أسبوع
            <VIcon class="tabler-arrow-up mx-1" color="#4DC80C" size="20"/>
            <span>+4.5%</span>
            
            </p>
        </VCardText>
    </VCard>
</template>


