<script setup lang="ts">
import FormField from '@/components/FormField.vue';
import DatePicker from '@/components/volt/DatePicker.vue';

defineProps<{
    id: string;
    label: string;
    modelValue: any;
    placeholder: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
}>();
</script>

<template>
    <FormField :id="id" :label="label">
        <DatePicker
            :modelValue="modelValue"
            @update:modelValue="(value) => emit('update:modelValue', value)"
            showIcon
            iconDisplay="input"
            :placeholder="placeholder"
        />
    </FormField>
</template>
