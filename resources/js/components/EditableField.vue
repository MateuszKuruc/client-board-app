<script setup lang="ts">
import Chip from '@/components/volt/Chip.vue';
import DatePicker from '@/components/volt/DatePicker.vue';
import InputText from '@/components/volt/InputText.vue';
import Message from '@/components/volt/Message.vue';
import MultiSelect from '@/components/volt/MultiSelect.vue';
import Select from '@/components/volt/Select.vue';
import { viewLabels } from '@/constants/viewLabels';
import dayjs from '@/plugins/dayjs';
import { User } from 'lucide-vue-next';
import { PropType, computed } from 'vue';

interface EditableFieldProps {
    label: string;
    isEditing: boolean;
    modelValue: string | Date | Array<string | number>;
    type?: 'text' | 'select' | 'picker' | 'multiselect';
    options?: string[] | { value: string; label: string }[];
    minDate?: string | Date;
    error?: string[] | null;
}

const props = defineProps({
    label: String as PropType<EditableFieldProps['label']>,
    isEditing: Boolean as PropType<EditableFieldProps['isEditing']>,
    modelValue: [String, Date, Array] as PropType<EditableFieldProps['modelValue']>,
    type: {
        type: String as PropType<EditableFieldProps['type']>,
        default: 'text',
    },
    options: {
        type: Array as PropType<EditableFieldProps['options']>,
        default: () => [] as string[],
    },
    minDate: [String, Date] as PropType<EditableFieldProps['minDate']>,
    error: {
        type: [String, Array] as PropType<string | string[]>,
        default: () => [],
    },
} as const);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const multiselectLabels = computed<string[]>(() => {
    if (props.type === 'multiselect' && Array.isArray(props.modelValue) && Array.isArray(props.options)) {
        return props.options.filter((o) => props.modelValue.includes(o.value)).map((o) => o.label);
    }
});
</script>

<template>
    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <label class="text-sm/6 font-medium text-gray-900 dark:text-gray-400">{{ label }}</label>
        <p v-if="!isEditing && type === 'text'" class="mt-1 px-2 py-1 text-sm/6 text-gray-700 dark:text-gray-50 sm:col-span-2 sm:mt-0">
            {{ label === 'Cena' ? modelValue + ' zł' : label === 'Kwota' ? modelValue + ' zł' : modelValue }}
        </p>

        <p v-if="!isEditing && type === 'select'" class="mt-1 px-2 py-1 text-sm/6 text-gray-700 dark:text-gray-50 sm:col-span-2 sm:mt-0">
            {{ viewLabels[modelValue] || modelValue }}
        </p>

        <p v-if="!isEditing && type === 'multiselect'" class="mt-1 px-2 py-1 text-sm/6 text-gray-700 dark:text-gray-50 sm:col-span-2 sm:mt-0">
            <Chip
                v-for="user in multiselectLabels"
                :key="user"
                :label="user"
                class="mr-1"
                :image="`/storage/users/avatars/${user.toLowerCase()}.webp`"
            />
        </p>

        <p v-else-if="!isEditing && type === 'picker'" class="mt-1 px-2 py-1 text-sm/6 text-gray-700 dark:text-gray-50 sm:col-span-2 sm:mt-0">
            {{ modelValue ? dayjs(modelValue).format('DD.MM.YYYY') : '-' }}
        </p>

        <div v-if="isEditing && type === 'select'" class="flex flex-col gap-2">
            <Select
                v-if="isEditing && type === 'select'"
                :options="options"
                :optionLabel="typeof options[0] === 'string' ? undefined : 'label'"
                :optionValue="typeof options[0] === 'string' ? undefined : 'value'"
                :modelValue="modelValue"
                size="small"
                @update:modelValue="(val) => emit('update:modelValue', val)"
            />
            <Message v-if="props.error.length" severity="error" size="small">{{ error }}</Message>
        </div>

        <div v-else-if="isEditing && type === 'multiselect'" class="flex flex-col gap-2">
            <MultiSelect
                v-if="isEditing && type === 'multiselect'"
                :options="options"
                optionLabel="label"
                optionValue="value"
                :modelValue="modelValue"
                display="chip"
                showClear
                filter
                size="small"
                placeholder="Przypisz opiekuna projektu"
                @update:modelValue="(val) => emit('update:modelValue', val)"
            >
                <template #dropdownicon>
                    <User />
                </template>
            </MultiSelect>
            <Message v-if="props.error.length" severity="error" size="small">{{ error }}</Message>
        </div>

        <div v-else-if="isEditing && type === 'picker'" class="flex flex-col gap-2">
            <DatePicker
                showIcon
                fluid
                iconDisplay="input"
                dateFormat="dd.mm.yy"
                :minDate="minDate"
                :modelValue="modelValue"
                size="small"
                @update:modelValue="(val) => emit('update:modelValue', val)"
            />
            <Message v-if="props.error.length" severity="error" size="small">{{ error }}</Message>
        </div>

        <div v-else-if="isEditing" class="flex flex-col gap-2">
            <InputText
                type="text"
                :value="modelValue"
                size="small"
                @input="(e) => emit('update:modelValue', e.target.value)"
                class="mt-1 px-2 py-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"
            />
            <Message v-if="props.error.length" severity="error" size="small">{{ error }}</Message>
        </div>
    </div>
</template>
