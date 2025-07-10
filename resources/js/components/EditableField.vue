<script setup lang="ts">
import InputText from '@volt/InputText.vue';


defineProps({
    label: String,
    isEditing: Boolean,
    modelValue: String,
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <label class="text-sm/6 font-medium text-gray-900">{{ label }}</label>
        <p v-if="!isEditing" class="mt-1 px-2 py-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ modelValue }}</p>
        <InputText
            v-if="isEditing"
            type="text"
            :value="modelValue"
            @input="(event) => emit('update:modelValue', event.target.value)"
            class="mt-1 px-2 py-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"
        />

    </div>
</template>
