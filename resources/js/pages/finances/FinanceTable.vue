<script setup lang="ts">
import Paginator from '@/components/Paginator.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import { Payment, Project } from '@/types/models';
import { SquarePen } from 'lucide-vue-next';
import Column from 'primevue/column';
import { computed } from 'vue';

const props = defineProps<{
    data: Project[] | Payment[];
}>();

const hasNameData = computed(() => {
    return props.data.data.some((item) => item.name);
});

const hasProjectData = computed(() => {
    return props.data.data.some((item) => item.project);
});
</script>

<template>
    <DataTable :value="data.data" dataKey="id">
        <Column field="id" header="Nr płatności" />
        <Column v-if="hasNameData" field="name" header="Wydatek" />
        <Column field="amount" header="Kwota" />
        <Column v-if="hasProjectData" field="project.name" header="Projekt" />
        <Column v-if="hasProjectData" field="project.client.name" header="Klient" />
        <Column field="payment_date" header="Data płatności" />
        <Column>
            <template #body="{ data }">
                <StyledLink
                    variant="default"
                    :href="
                        hasProjectData
                            ? route('payments.show', { client: data.project.client.slug, project: data.project.id, payment: data.id })
                            : route('expenses.show', { expense: data.id })
                    "
                >
                    <SquarePen />
                </StyledLink>
            </template>
        </Column>
    </DataTable>
    <Paginator :data="data" />
</template>
