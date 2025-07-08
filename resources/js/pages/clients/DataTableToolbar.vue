<script setup>
import Button from '@volt/Button.vue';
import InputText from '@volt/InputText.vue';
import SecondaryButton from '@volt/SecondaryButton.vue';
import { ChevronsDown, ChevronsUp, Search, FileDown } from 'lucide-vue-next';

defineProps({
    modelValue: String,
    onExpandAll: Function,
    onCollapseAll: Function,
    onExportCSV: Function,
    searchable: {
        type: Boolean,
        default: true,
    },
    expandLabel: {
        type: String,
        default: 'Rozwiń wszystkie',
    },
    collapseLabel: {
        type: String,
        default: 'Zwiń wszystkie',
    },
    exportLabel: {
        type: String,
        default: 'Eksportuj do CSV',
    },
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="flex flex-col-reverse justify-between gap-4 py-4 xl:flex-row xl:py-8">
        <div class="flex gap-2">
            <SecondaryButton @click="onExpandAll?.()">
                <ChevronsDown />
                {{ expandLabel }}
            </SecondaryButton>
            <SecondaryButton @click="onCollapseAll?.()">
                <ChevronsUp />
                {{ collapseLabel }}
            </SecondaryButton>
            <!--                            <Button @click="expandAll"><ChevronsDown /> Rozwiń wszystkie </Button>-->
            <!--                            <Button @click="collapseAll"><ChevronsUp /> Zwiń wszystkie</Button>-->
        </div>
        <div class="relative flex items-center gap-2">
            <Search v-if="searchable" />
            <InputText v-if="searchable" :modelValue="modelValue" @update:modelValue="emit('update:modelValue', $event)" placeholder="Search" />
            <Button @click="onExportCSV">
                <FileDown />
                {{ exportLabel }}
            </Button>
        </div>
    </div>
</template>
