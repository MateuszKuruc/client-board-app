import { router } from '@inertiajs/vue3';
import { ref, Ref, watch } from 'vue';

type SortDirection = 'asc' | 'desc';

export function useServerSorting(
    routeName: string,
    initialSortBy: string = 'created_at',
    initialSortDir: SortDirection = 'desc',
): {
    sortBy: Ref<string>;
    sortDir: Ref<SortDirection>;
    setSort: (column: string) => void;
} {
    const sortBy = ref(initialSortBy);
    const sortDir = ref<SortDirection>(initialSortDir);

    watch([sortBy, sortDir], ([by, dir]) => {
        router.get(route(routeName), { sort_by: by, sort_dir: dir, page: 1 }, { preserveState: true, replace: true });
    });

    // function setSort(column: string) {
    //     if (sortBy.value === column) {
    //         // toggle direction
    //         sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
    //     } else {
    //         sortBy.value = column;
    //         sortDir.value = 'asc';
    //     }

        function setSort(column: string) {
            if (sortBy.value !== column) {
                // 1) New column → asc
                sortBy.value  = column
                sortDir.value = 'asc'
            } else if (sortDir.value === 'asc') {
                // 2) asc → desc
                sortDir.value = 'desc'
            } else if (sortDir.value === 'desc') {
                // 3) desc → clear
                sortBy.value  = ''
                sortDir.value = ''
            } else {
                // fallback: start fresh
                sortBy.value  = column
                sortDir.value = 'asc'
            }
    }

    return { sortBy, sortDir, setSort };
}
