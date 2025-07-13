<script setup lang="ts">
const { heading } = defineProps<{
    heading: string;
}>();
</script>

<template>
    <h3 class="text-base/7 font-semibold text-gray-900">{{ heading }}</h3>
</template>
