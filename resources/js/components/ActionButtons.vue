<!-- components/EditActions.vue -->
<script setup lang="ts">
import Button from '@/components/volt/Button.vue';
import SecondaryButton from '@/components/volt/SecondaryButton.vue';
import { Pencil, X } from 'lucide-vue-next';

const props = defineProps<{
    isEditing: boolean;
    buttonLabel?: string;
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
    <div class="mt-5 flex items-center lg:mt-0">
        <span>
            <SecondaryButton v-if="!props.isEditing" @click="onEdit" >
                <Pencil class="w-5 text-gray-400" />
                {{ props.buttonLabel }}
            </SecondaryButton>

            <SecondaryButton v-if="props.isEditing" @click="onCancel">
                <X class="w-5 text-gray-400" />
                Anuluj
            </SecondaryButton>
        </span>

        <span>
            <Button v-if="props.isEditing" @click="onSave" class="ml-2">
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
