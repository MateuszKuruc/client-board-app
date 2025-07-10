<script setup lang="ts">
import InputText from '@/components/volt/InputText.vue';
import Select from '@/components/volt/Select.vue';


defineProps({
    label: String,
    isEditing: Boolean,
    modelValue: String,
    type: {
        type: String,
        default: 'text',
    },
    options: {
        type: Array,
        default: () => [],
    },
});



const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <label class="text-sm/6 font-medium text-gray-900">{{ label }}</label>
        <p v-if="!isEditing" class="mt-1 px-2 py-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ modelValue }}</p>
        <Select
            v-if="isEditing && type === 'select'"
            :options="options"
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
