<script setup lang="ts">
import DataTableToolbar from '@/components/DataTableToolbar.vue';
import Paginator from '@/components/Paginator.vue';
import SortableHeader from '@/components/SortableHeader.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Tag from '@/components/volt/Tag.vue';
import { useExportParams } from '@/composables/useExportParams';
import { useServerSearch } from '@/composables/useServerSearch';
import { useServerSorting } from '@/composables/useServerSorting';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import type { Filters, Lead, Paginated } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { SquarePen, User } from 'lucide-vue-next';
import Column from 'primevue/column';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Leady',
        href: '/leady',
    },
];

const props = defineProps<{
    leads: Paginated<Lead>;
    filters: Filters;
}>();

const { globalSearch } = useServerSearch(props.filters.search || '', 'leads.index');

const { sortBy, sortDir, setSort } = useServerSorting(
    'leads.index',
    props.filters.sort_by || 'created_at',
    (props.filters.sort_dir as 'asc' | 'desc') || 'desc',
);

const exportParams = useExportParams(globalSearch, sortBy, sortDir);
</script>

<template>
    <Head title="Leady" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable :value="leads.data" dataKey="id">
                <template #header>
                    <DataTableToolbar v-model="globalSearch" :exportUrl="'leads.export'" :filters="filters" :exportParams="exportParams" />
                </template>

                <Column field="email" header="Email"> </Column>
                <Column field="phone" header="Telefon">
                    <template #body="{ data: lead }: { data: Lead }">
                        {{ lead.phone ? lead.phone : '-' }}
                    </template>
                </Column>
                <Column field="converted_at">
                    <template #header>
                        <SortableHeader field="converted_at" :active="sortBy === 'converted_at'" :direction="sortDir" @sort="setSort">
                            Status
                        </SortableHeader>
                    </template>
                    <template #body="{ data: lead }: { data: Lead }">
                        <div class="flex flex-col gap-1">
                            <Tag :value="lead.converted_at ? 'Przekonwertowany' : 'Lead'" :severity="lead.converted_at ? 'success' : 'warn'" />
                            <Tag
                                v-if="lead.converted_at"
                                :value="lead.converted_at ? dayjs(lead.converted_at).format('DD.MM.YYYY') : null"
                                severity="info"
                            />
                        </div>
                    </template>
                </Column>

                <Column header="Aktualizacja">
                    <template #body="{ data: lead }: { data: Lead }">
                        <StyledLink v-if="lead.converted_at === null" variant="text" :href="route('clients.create', lead.id)">
                            <SquarePen />
                            Zamień w klienta
                        </StyledLink>

                        <StyledLink v-if="lead.converted_at" variant="text" :href="route('clients.show', lead.client.slug)">
                            <User />
                            Profil klienta
                        </StyledLink>
                    </template>
                </Column>
            </DataTable>
            <Paginator :data="leads" />
        </div>
    </AppLayout>
</template>
