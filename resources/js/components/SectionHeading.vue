<script setup lang="ts">
import SingleHeading from '@/components/SingleHeading.vue';
import SingleSubheading from '@/components/SingleSubheading.vue';
import Divider from '@/components/volt/Divider.vue';
import Tag from '@/components/volt/Tag.vue';

const { heading, subheading, divider } = defineProps<{
    heading: string;
    subheading?: string;
    divider?: boolean;
    tag?: string;
    severity?: string;
}>();
</script>

<template>
    <div class="my-8 px-4 sm:px-0">
        <div class="flex gap-2">
            <Tag v-if="tag" :severity="severity">{{ tag.toUpperCase() }}</Tag>
            <SingleHeading :heading="heading" />
        </div>
        <SingleSubheading :subheading="subheading" />
        <Divider v-if="divider" />
    </div>
</template>
