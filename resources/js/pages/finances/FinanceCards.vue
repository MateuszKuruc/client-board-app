<script setup lang="ts">
import ReusableCard from '@/components/ReusableCard.vue';

const props = defineProps<{
    firstHeading: string;
    secondHeading: string;
    value: number;
    percentage: number;
}>();
</script>

<template>
    <div class="my-8 grid grid-cols-2 gap-4">
        <ReusableCard :heading="firstHeading" :value="value" />
        <ReusableCard :heading="secondHeading" :percentage="percentage" />
    </div>
</template>
