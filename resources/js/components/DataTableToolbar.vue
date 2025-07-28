<script setup lang="ts">
import Button from '@/components/volt/Button.vue';
import InputText from '@/components/volt/InputText.vue';
import SecondaryButton from '@/components/volt/SecondaryButton.vue';
import { ChevronsDown, ChevronsUp, FileDown, Search } from 'lucide-vue-next';

interface exportParams {
    search: string;
    sort_by: string;
    sort_dir: string;
}

const props = withDefaults(
    defineProps<{
        modelValue: string;
        onExpandAll?: () => void;
        onCollapseAll?: () => void;
        exportUrl: string;
        filters: Record<string, any>;
        exportParams?: ExportParams;
        searchable?: boolean;
        expandLabel?: string;
        collapseLabel?: string;
        exportLabel?: string;
    }>(),
    {
        searchable: true,
        expandLabel: 'Rozwiń wszystkie',
        collapseLabel: 'Zwiń wszystkie',
        exportLabel: 'Eksportuj dane',
    },
);

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="flex flex-col-reverse justify-between gap-4 py-4 xl:flex-row xl:py-8">
        <div class="flex gap-2" :class="{ invisible: !onExpandAll && !onCollapseAll }">
            <SecondaryButton @click="onExpandAll?.()">
                <ChevronsDown />
                {{ expandLabel }}
            </SecondaryButton>
            <SecondaryButton @click="onCollapseAll?.()">
                <ChevronsUp />
                {{ collapseLabel }}
            </SecondaryButton>
        </div>
        <div class="relative flex items-center gap-2">
            <div class="relative">
                <Search v-if="searchable" class="absolute start-3 top-1/2 z-1 -mt-3 leading-none text-surface-400" />
                <InputText
                    v-if="searchable"
                    :modelValue="modelValue"
                    @update:modelValue="emit('update:modelValue', $event)"
                    placeholder="Szukaj"
                    pt:root="ps-10"
                />
            </div>
            <SecondaryButton as="a" :href="route(exportUrl, exportParams)">
                <FileDown />
                {{ exportLabel }}
            </SecondaryButton>
        </div>
    </div>
</template>
