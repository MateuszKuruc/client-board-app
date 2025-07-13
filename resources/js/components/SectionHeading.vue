<script setup lang="ts">
import Divider from '@/components/volt/Divider.vue';
import SingleHeading from '@/components/SingleHeading.vue';
import SingleSubheading from '@/components/SingleSubheading.vue';

const { heading, subheading, divider } = defineProps<{
    heading: string;
    subheading?: string;
    divider?: boolean;
}>();
</script>

<template>
    <div class="px-4 sm:px-0 my-8">
        <SingleHeading :heading="heading" />
        <SingleSubheading :subheading="subheading" />
        <Divider v-if="divider" />
    </div>
</template>
