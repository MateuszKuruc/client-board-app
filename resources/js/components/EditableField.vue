<script setup lang="ts">
import DatePicker from '@/components/volt/DatePicker.vue';
import InputText from '@/components/volt/InputText.vue';
import Select from '@/components/volt/Select.vue';
import dayjs from '@/plugins/dayjs';
import { PropType } from 'vue';

interface EditableFieldProps {
    label: string;
    isEditing: boolean;
    modelValue: string | Date;
    type?: 'text' | 'select' | 'picker';
    options?: string[];
}

const { label, isEditing, modelValue, type, options } = defineProps({
    label: String as PropType<EditableFieldProps['label']>,
    isEditing: Boolean as PropType<EditableFieldProps['isEditing']>,
    modelValue: [String, Date] as PropType<EditableFieldProps['modelValue']>,
    type: {
        type: String as PropType<EditableFieldProps['type']>,
        default: 'text',
    },
    options: {
        type: Array as PropType<EditableFieldProps['options']>,
        default: () => [] as string[],
    },
} as const);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();
</script>

<template>
    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <label class="text-sm/6 font-medium text-gray-900">{{ label }}</label>
        <p v-if="!isEditing && type !== 'picker'" class="mt-1 px-2 py-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">
            {{ label === 'Cena' ? modelValue + ' zł' : modelValue }}
        </p>

        <p v-else-if="!isEditing && type === 'picker'" class="mt-1 px-2 py-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">
            {{ dayjs(modelValue).format('DD-MM-YYYY') }}
        </p>

        <Select
            v-if="isEditing && type === 'select'"
            :options="options"
            :modelValue="modelValue"
            @update:modelValue="(val) => emit('update:modelValue', val)"
        />

        <DatePicker
            v-else-if="isEditing && type === 'picker'"
            showIcon
            iconDisplay="input"
            dateFormat="dd-mm-yy"
            :modelValue="modelValue"
            @update:modelValue="(val) => emit('update:modelValue', val)"
        />

        <InputText
            v-else-if="isEditing"
            type="text"
            :value="modelValue"
            @input="(e) => emit('update:modelValue', e.target.value)"
            class="mt-1 px-2 py-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"
        />
    </div>
</template>
