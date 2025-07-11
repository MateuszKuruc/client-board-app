import { ref, Ref } from 'vue';

export function useExpandableRows<T extends { id: number }>(
    items: T[],
): {
    readonly expandedRows: Ref<Record<number, boolean> | null>;
    readonly expandAll: () => void;
    readonly collapseAll: () => void;
} {
    const expandedRows: Ref<Record<number, boolean> | null> = ref({});

    const expandAll = () => {
        expandedRows.value = Object.fromEntries(items.map((c) => [c.id, true]));
    };

    const collapseAll = () => {
        expandedRows.value = null;
    };

    return {
        expandedRows,
        expandAll,
        collapseAll,
    } as const;
}
