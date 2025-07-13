<script setup lang="ts">
const { subheading } = defineProps<{
    subheading: string;
}>();
</script>

<template>
    <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">{{ subheading }}</p>
</template>
