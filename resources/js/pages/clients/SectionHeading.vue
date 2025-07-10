<script setup lang="ts">
import SingleHeading from '@/pages/clients/SingleHeading.vue';
import SingleSubheading from '@/pages/clients/SingleSubheading.vue';
import Divider from '@/components/volt/Divider.vue';


defineProps({
    heading: String,
    subheading: String,
    divider: Boolean
});
</script>

<template>
    <div class="px-4 sm:px-0">
        <SingleHeading :heading="heading" />
        <SingleSubheading :subheading="subheading" />
        <Divider v-if="divider" />
    </div>
</template>
