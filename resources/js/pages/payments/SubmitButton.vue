<script setup lang="ts">
import { LoaderCircle } from 'lucide-vue-next';
import { useAttrs } from 'vue';
import Button from '@/components/volt/Button.vue';

const attrs = useAttrs();

const { processing, disabled, buttonLabel, loadingLabel } = withDefaults(
    defineProps<{
        processing: boolean;
        disabled: boolean;
        buttonLabel?: string;
        loadingLabel?: string;
    }>(),
    {
        buttonLabel: 'Zapisz',
        loadingLabel: 'Zapisywanie...',
    },
);
</script>

<template>
    <Button type="submit" class="mt-2 w-full" :disabled="disabled" v-bind="attrs">
        <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
        {{ processing ? loadingLabel : buttonLabel }}
    </Button>
</template>
