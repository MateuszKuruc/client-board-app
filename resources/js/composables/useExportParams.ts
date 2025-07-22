// src/composables/useExportParams.ts
import { computed } from 'vue'
import { Ref } from 'vue'

interface ExportParams {
    search: string
    sort_by: string
    sort_dir: string
}

export function useExportParams(
    search: Ref<string>,
    sortBy: Ref<string>,
    sortDir: Ref<string>
) {
    return computed<ExportParams>(() => ({
        search:   search.value,
        sort_by:  sortBy.value,
        sort_dir: sortDir.value,
    }))
}
