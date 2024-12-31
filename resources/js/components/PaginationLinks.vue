<script setup>
import { Link, router } from "@inertiajs/vue3";


const props = defineProps({
  data: Array,
});


</script>

<template>
  <div class="d-flex flex-row" style="align-items: center;">

    <div class="pagination d-flex flex-row" style="align-items: center;">
      <template v-for="(link, key) in data.links" :key="key" >
        <div v-if="key != 0 && key != data.links.length-1">
          <div  class="mr-1 mb-1 px-4 py-2"
            style="background-color: #fff; color:#140208;border: 1px solid #E55175;border-radius: 8px;cursor: pointer;" 
            :style="link.active ? {'backgroundColor': '#E55175','color': '#fff'} : '' "
            @click="link.url ? router.get(link.url, {},{  preserveState : true, only: ['data']}) : ''"
          >
          {{ link.label }}
          </div>

          <!--<Link v-else class="mr-1 mb-1  px-4 py-2" 
          style="background-color: #fff; color:#140208;border: 1px solid #E55175;border-radius: 8px;"
          :style="link.active ? {'backgroundColor': '#E55175','color': '#fff'} : '' " :href="link.url" v-html="link.label" />-->
        </div>
        <div v-if="key == 0"  class="mr-1 mb-1 px-4 py-2"style=""> 
          <VIcon icon="tabler-chevron-right" color="#9D123F" size="25" @click="link.url ? router.get(link.url, {},{  preserveState : true, only: ['data']}) : ''" /> 
        </div>
        <div v-if="key == data.links.length-1"  class="mr-1 mb-1 px-4 py-2"style=""> 
          <VIcon icon="tabler-chevron-left" color="#9D123F" size="25" @click="link.url ? router.get(link.url, {},{  preserveState : true, only: ['data']}) : ''" /> 
        </div>
      </template>
    </div>


    <VSpacer/>
    <div class="results">
      <span>اظهار نتائج </span> {{ data.from }}-{{ data.to }} <span>من </span> {{ data.total }}

    </div>

  </div>
</template>

<style lang="scss">
.pagination {
  display: flex;
  justify-content: center;
  margin: 20px;
  margin-right: 0;
}

.results {
  color: #9A9A9A;
  font-size: 14px;
  font-weight: 400;

  span:nth-of-type(2){
    color: #140208;
  }
}
</style>
