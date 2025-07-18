<template>
    <Button
        unstyled
        :pt="theme"
        :ptOptions="{
            mergeProps: ptViewMerge,
        }"
    >
        <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
            <slot :name="slotName" v-bind="slotProps ?? {}" />
        </template>
    </Button>
</template>

<script setup lang="ts">
import Button, { type ButtonPassThroughOptions, type ButtonProps } from 'primevue/button';
import { ref } from 'vue';
import { ptViewMerge } from './utils';

interface Props extends /* @vue-ignore */ ButtonProps {}
defineProps<Props>();

const theme = ref<ButtonPassThroughOptions>({
    root: `
     inline-flex items-center justify-center gap-2 rounded-md font-medium
    px-4 py-2 text-sm
    transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2
    border border-gray-300 bg-white text-gray-700
    hover:bg-gray-50 enabled:active:bg-gray-100
    focus:ring-blue-500 shadow-sm
    disabled:opacity-50 disabled:cursor-not-allowed
  `,

    loadingIcon: ``,
    icon: `p-right:order-1 p-bottom:order-2`,
    label: `font-medium p-icon-only:invisible p-icon-only:w-0
        p-small:text-sm p-large:text-[1.125rem]`,
    pcBadge: {
        root: `min-w-4 h-4 leading-4`,
    },
});
</script>
