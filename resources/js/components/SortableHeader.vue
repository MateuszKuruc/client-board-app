<script setup lang="ts">
interface SortableHeaderProps {
    field: string;
    active?: boolean;
    direction?: 'asc' | 'desc' | '';
}

const props = withDefaults(defineProps<SortableHeaderProps>(), {
    active: false,
    direction: '',
});

const emit = defineEmits<{
    (e: 'sort', field: string): void;
}>();

function onClick() {
    emit('sort', props.field);
}
</script>

<template>
    <button @click="onClick" class="flex items-center gap-1 font-bold focus:outline-none" :aria-sort="active ? direction : 'none'">
        <slot />
        <span v-if="active">
            {{ direction === 'asc' ? '▲' : '▼' }}
        </span>
        <span v-else class="text-surface-400" aria-hidden="true">▲▼</span>
    </button>
</template>
