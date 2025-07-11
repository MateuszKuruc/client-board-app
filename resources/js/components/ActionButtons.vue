<!-- components/EditActions.vue -->
<script setup lang="ts">
import SecondaryButton from '@/components/volt/SecondaryButton.vue';
import Button from '@/components/volt/Button.vue';

defineProps<{
    isEditing: boolean;
}>();

const emit = defineEmits(['cancel', 'edit', 'save']);

function onCancel() {
    emit('cancel');
}

function onEdit() {
    emit('edit');
}

function onSave() {
    emit('save');
}
</script>

<template>
    <div class="mt-5 flex gap-1 lg:mt-0 lg:ml-4">
            <span>
                <SecondaryButton
                    v-if="!isEditing"
                    @click="onEdit"
                    class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50"
                >
                    <svg class="mr-1.5 -ml-0.5 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path
                            d="m2.695 14.762-1.262 3.155a.5.5 0 0 0 .65.65l3.155-1.262a4 4 0 0 0 1.343-.886L17.5 5.501a2.121 2.121 0 0 0-3-3L3.58 13.419a4 4 0 0 0-.885 1.343Z"
                        />
                    </svg>
                    Edytuj
                </SecondaryButton>

                <SecondaryButton
                    v-if="isEditing"
                    @click="onCancel"
                    class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50"
                >
                    <svg class="mr-1.5 -ml-0.5 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path
                            d="m2.695 14.762-1.262 3.155a.5.5 0 0 0 .65.65l3.155-1.262a4 4 0 0 0 1.343-.886L17.5 5.501a2.121 2.121 0 0 0-3-3L3.58 13.419a4 4 0 0 0-.885 1.343Z"
                        />
                    </svg>
                    Anuluj
                </SecondaryButton>
            </span>

        <span class="sm:ml-3">
                <Button
                    v-if="isEditing"
                    @click="onSave"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    <svg class="mr-1.5 -ml-0.5 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path
                            fill-rule="evenodd"
                            d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    Zapisz
                </Button>
            </span>
    </div>
</template>
