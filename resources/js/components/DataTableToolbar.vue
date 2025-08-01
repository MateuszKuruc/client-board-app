<script setup lang="ts">
import StyledLink from '@/components/StyledLink.vue';
import InputText from '@/components/volt/InputText.vue';
import SecondaryButton from '@/components/volt/SecondaryButton.vue';
import { ChevronsDown, ChevronsUp, FileDown, Plus, Search } from 'lucide-vue-next';

interface ExportParams {
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
        addButtonLabel?: string;
        addButtonRoute?: string;
        displayButton: boolean;
    }>(),
    {
        searchable: true,
        displayButton: true,
        expandLabel: 'Rozwiń wszystkie',
        collapseLabel: 'Zwiń wszystkie',
        exportLabel: 'Eksportuj dane',
    },
);

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="flex flex-col-reverse justify-between gap-4 py-4 xl:flex-row xl:py-8">
        <div class="flex gap-2">
            <StyledLink v-if="displayButton" :href="addButtonRoute" class="flex flex-row gap-2">
                <Plus class="w-5" />
                {{ addButtonLabel }}
            </StyledLink>
            <div class="flex gap-2" :class="{ invisible: !onExpandAll && !onCollapseAll }">
                <SecondaryButton @click="onExpandAll?.()">
                    <ChevronsDown class="w-5" />
                    <span class="hidden xl:inline">
                        {{ expandLabel }}
                    </span>
                </SecondaryButton>
                <SecondaryButton @click="onCollapseAll?.()">
                    <ChevronsUp class="w-5" />
                    <span class="hidden xl:inline">
                        {{ collapseLabel }}
                    </span>
                </SecondaryButton>
            </div>
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
                <FileDown class="w-5" />
                <span class="hidden xl:inline">
                    {{ exportLabel }}
                </span>
            </SecondaryButton>
        </div>
    </div>
</template>
