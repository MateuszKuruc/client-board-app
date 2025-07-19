<script setup lang="ts">
import FormField from '@/components/FormField.vue';
import { useAttrs } from 'vue';
import Select from '@/components/volt/Select.vue';

const { id, label, error, modelValue, options, optionLabel, optionValue, placeholder } = defineProps<{
    id: string;
    label: string;
    error?: string;
    modelValue: any;
    options: any[];
    optionLabel?: string;
    optionValue?: any;
    placeholder?: string;
}>();

const attrs = useAttrs();

const emit = defineEmits<{
    (event: 'update:modelValue', value: any): void;
}>();
</script>

<template>
    <FormField :id="id" :label="label" :error="error">
        <Select
            :modelValue="modelValue"
            @update:modelValue="(value) => emit('update:modelValue', value)"
            :options="options"
            :optionLabel="optionLabel"
            :optionValue="optionValue"
            editable
            :placeholder="placeholder"
            required
            size="small"
            v-bind="attrs"
        />
    </FormField>
</template>
