<script setup lang="ts">
import InputText from '@/components/volt/InputText.vue';
import FormField from '@/components/FormField.vue';
import { useAttrs } from 'vue';

const attrs = useAttrs();

const { id, label, error, modelValue, placeholder } = defineProps<{
    id: string;
    label: string;
    error?: string;
    modelValue: any;
    placeholder?: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
}>();
</script>

<template>
    <FormField :id="id" :label="label" :error="error">
        <InputText :id="id" :modelValue="modelValue" @update:modelValue="(value) => emit('update:modelValue', value)" size="small" :placeholder="placeholder" v-bind="attrs" />
    </FormField>
</template>
