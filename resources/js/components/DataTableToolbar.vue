<script setup>
import Button from '@/components/volt/Button.vue';
import InputText from '@/components/volt/InputText.vue';
import SecondaryButton from '@/components/volt/SecondaryButton.vue';
import { ChevronsDown, ChevronsUp, Search, FileDown } from 'lucide-vue-next';

defineProps({
    modelValue: String,
    onExpandAll: Function,
    onCollapseAll: Function,
    exportUrl: String,
    filters: Object,
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
        <div class="flex gap-2" :class="{ 'invisible': !onExpandAll && !onCollapseAll }">
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
            <div class="relative">

            <Search v-if="searchable" class="absolute top-1/2 -mt-3 text-surface-400 leading-none start-3 z-1" />
            <InputText v-if="searchable" :modelValue="modelValue" @update:modelValue="emit('update:modelValue', $event)" placeholder="Search" pt:root="ps-10" />
            </div>
            <Button as="a" :href="route(exportUrl, { search: filters.search })">
                <FileDown />
                {{ exportLabel }}
            </Button>
        </div>
    </div>
</template>
