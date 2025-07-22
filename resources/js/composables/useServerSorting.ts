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

    function setSort(column: string) {
        if (sortBy.value === column) {
            // toggle direction
            sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
        } else {
            sortBy.value = column;
            sortDir.value = 'asc';
        }
    }

    return { sortBy, sortDir, setSort };
}
