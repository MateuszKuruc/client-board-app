import { ref } from 'vue';

export function useExpandableRows(items) {
    const expandedRows = ref({});

    const expandAll = () => {
        expandedRows.value = Object.fromEntries(items.map((c) => [c.id, true]));
    };

    const collapseAll = () => {
        expandedRows.value = null;
    };

    return {
        expandedRows,
        expandAll,
        collapseAll
    }
}
