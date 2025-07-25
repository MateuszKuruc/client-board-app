<script setup lang="ts">
import DataTableToolbar from '@/components/DataTableToolbar.vue';
import Paginator from '@/components/Paginator.vue';
import SortableHeader from '@/components/SortableHeader.vue';
import StyledLink from '@/components/StyledLink.vue';
import Avatar from '@/components/volt/Avatar.vue';
import AvatarGroup from '@/components/volt/AvatarGroup.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Tag from '@/components/volt/Tag.vue';
import { useExpandableRows } from '@/composables/useExpandableRows';
import { useExportParams } from '@/composables/useExportParams';
import { useServerSearch } from '@/composables/useServerSearch';
import { useServerSorting } from '@/composables/useServerSorting';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import { Filters, Paginated, Payment, Project } from '@/types/models';
import { Head } from '@inertiajs/vue3';
import { FolderOpenDot } from 'lucide-vue-next';
import Column from 'primevue/column';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Projekty',
        href: '/projekty',
    },
];

const props = defineProps<{
    projects: Paginated<Project>;
    filters: Filters;
}>();

const { globalSearch } = useServerSearch(props.filters.search || '', 'projects.index');

const { expandedRows, expandAll, collapseAll } = useExpandableRows(props.projects.data);

function getSortedPayments(project) {
    return [...project.payments].sort((a, b) => {
        if (a.status === 'cancelled' && b.status !== 'cancelled') return 1;
        if (a.status !== 'cancelled' && b.status === 'cancelled') return -1;

        if (!a.payment_date && b.payment_date) return -1;
        if (a.payment_date && !b.payment_date) return 1;
        if (!a.payment_date && !b.payment_date) return 0;

        return new Date(b.payment_date) - new Date(a.payment_date); // descending
    });
}

const { sortBy, sortDir, setSort } = useServerSorting(
    'projects.index',
    props.filters.sort_by || 'created_at',
    (props.filters.sort_dir as 'asc' | 'desc') || 'desc',
);

const exportParams = useExportParams(globalSearch, sortBy, sortDir);
</script>

<template>
    <Head title="Projekty" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DataTable v-model:expandedRows="expandedRows" :value="projects.data" dataKey="id" ref="dt">
                <template #header>
                    <DataTableToolbar
                        v-model="globalSearch"
                        :onExpandAll="expandAll"
                        :onCollapseAll="collapseAll"
                        :exportUrl="'projects.export'"
                        :exportParams="exportParams"
                        :filters="{ ...filters, sort_by: sortBy, sort_dir: sortDir }"
                    />
                </template>
                <Column expander style="width: 5rem" />
                <Column field="name" header="Projekt" />
                <Column header="Opiekun">
                    <template #body="{ data: project }: { data: project }">
                        <template v-if="project.users?.length">
                            <AvatarGroup>
                                <Avatar
                                    v-for="user in project.users"
                                    :key="user.id"
                                    :image="user.avatar_url || undefined"
                                    :label="!user.avatar_url ? user.name.charAt(0).toUpperCase() : undefined"
                                    shape="circle"
                                />
                            </AvatarGroup>
                        </template>

                        <template v-else> - </template>
                    </template>
                </Column>
                <Column field="client.name" header="Klient" />
                <Column field="price">
                    <template #header>
                        <SortableHeader field="price" :active="sortBy === 'price'" :direction="sortDir" @sort="setSort"> Cena </SortableHeader>
                    </template>
                    <template #body="{ data: project }: { data: Project }"> {{ project.price }} zł </template>
                </Column>
                <Column field="start_date">
                    <template #header>
                        <SortableHeader field="start_date" :active="sortBy === 'start_date'" :direction="sortDir" @sort="setSort">
                            Data startu
                        </SortableHeader>
                    </template>
                    <template #body="{ data: project }: { data: Project }">
                        {{ project.start_date ? dayjs(project.start_date).format('DD.MM.YYYY') : '-' }}
                    </template>
                </Column>
                <Column field="end_date">
                    <template #header>
                        <SortableHeader field="end_date" :active="sortBy === 'end_date'" :direction="sortDir" @sort="setSort">
                            Data zakończenia
                        </SortableHeader>
                    </template>
                    <template #body="{ data: project }: { data: Project }">
                        {{ project.end_date ? dayjs(project.end_date).format('DD.MM.YYYY') : '-' }}
                    </template>
                </Column>
                <Column header="Szczegóły">
                    <template #body="{ data: project }: { data: Project }">
                        <StyledLink :href="route('projects.show', { client: project.client?.slug, project: project.id })">
                            <FolderOpenDot />
                        </StyledLink>
                    </template>
                </Column>

                <template #expansion="{ data: project }">
                    <div class="space-y-4 bg-gray-50 p-4">
                        <h5 class="font-semibold">Historia płatności</h5>
                        <div v-if="project.payments.length > 0">
                            <DataTable :value="getSortedPayments(project)" dataKey="id" scrollable scrollHeight="200px" removableSort>
                                <Column field="amount" header="Kwota" sortable />
                                <Column field="status" header="Status" sortable>
                                    <template #body="{ data: payment }: { data: Payment }">
                                        <Tag
                                            :value="
                                                payment.status === 'paid' ? 'Opłacona' : payment.status === 'pending' ? 'Oczekująca' : 'Anulowana'
                                            "
                                            :severity="payment.status === 'paid' ? 'success' : payment.status === 'pending' ? 'info' : 'danger'"
                                        />
                                    </template>
                                </Column>
                                <Column field="payment_date" header="Data płatności" sortable>
                                    <template #body="{ data: payment }: { data: Payment }">
                                        {{ payment.payment_date ? dayjs(payment.payment_date).format('DD.MM.YYYY') : '-' }}
                                    </template>
                                </Column>

                                <Column header="Akcja">
                                    <template #body="{ data: payment }: { data: Payment }">
                                        <StyledLink
                                            variant="text"
                                            :href="route('payments.show', { client: project.client.slug, project: project.id, payment: payment.id })"
                                        >
                                            Zarządzaj płatnością
                                        </StyledLink>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                        <div v-else>
                            <p>Żadne projekty nie zostały zrealizowane.</p>
                        </div>
                    </div>
                </template>
            </DataTable>
            <Paginator :data="projects" />
        </div>
    </AppLayout>
</template>
