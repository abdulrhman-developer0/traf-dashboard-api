<script setup>
defineProps({
    errors: Object,
});
</script>

<template>
    <div class="mb-4" v-if="Object.keys(errors).length">
        <p class="text-sm text-red">Something went wrong!</p>
        <ul class="ml-4 list-disc list-inside">
            <li
                v-for="(error, i) in errors"
                :key="i"
                class="text-sm text-red"
            >
                {{ error.includes('data.') ? error.replace('data.', '')  : error }}
            </li>
        </ul>
    </div>
</template>


<style>
.text-red {
  color: red;
}
.text-sm {
  font-size: 14px;
}
</style>
