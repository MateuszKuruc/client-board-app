<script setup lang="ts">
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';

const { id, label, error } = defineProps<{
    id: string;
    label: string;
    error?: string;
}>();

</script>

<template>
    <div class="grid gap-2">
        <Label :for="id">{{ label }}</Label>
        <slot />
        <InputError :message="error" />
    </div>
</template>
