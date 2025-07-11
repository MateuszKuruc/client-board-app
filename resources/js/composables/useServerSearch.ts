import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { ref, Ref, watch } from 'vue';

export function useServerSearch(initialSearch: string = '', routeName: string): { globalSearch: Ref<string> } {
    const globalSearch: Ref<string> = ref(initialSearch);

    const debouncedSearch = debounce((value: string) => {
        router.get(route(routeName), { search: value, page: 1 }, { preserveState: true, replace: true });
    }, 300);

    watch(globalSearch, (value) => {
        debouncedSearch(value);
    });

    return {
        globalSearch,
    } as const;
}
