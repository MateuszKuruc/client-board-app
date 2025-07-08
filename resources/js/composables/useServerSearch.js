import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { ref, watch } from 'vue';

export function useServerSearch(initialSearch = '', routeName) {
    const globalSearch = ref(initialSearch);

    const debouncedSearch = debounce((value) => {
        router.get(
            route(routeName),
            {
                search: value,
                page: 1,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    }, 300);

    watch(globalSearch, (value) => {
        debouncedSearch(value);
    });

    return {
        globalSearch,
    };
}
