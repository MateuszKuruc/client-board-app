<script setup lang="ts">
import DataTable from '@/components/volt/DataTable.vue';
import SectionHeading from '@/components/SectionHeading.vue';
import dayjs from '@/plugins/dayjs';
import { Project } from '@/types/models';
import Column from 'primevue/column';

const { projects, heading, subheading } = defineProps<{
    projects: Project[];
    heading: string;
    subheading?: string;
}>();
</script>

<template>
    <SectionHeading :heading="heading" :subheading="subheading" />
    <span v-if="projects.length < 1" class="mt-4 block text-xl font-semibold">Brak</span>
    <DataTable v-if="projects.length >= 1" class="mt-6" :value="projects" dataKey="id">
        <Column field="name" header="Projekt" />
        <Column field="service.name" header="Usługa" />
        <Column field="start_date" header="Data startu">
            <template #body="{ data }: { data: Project }">
                {{ dayjs(data.start_date).format('DD.MM.YYYY') }}
            </template>
        </Column>
        <Column field="end_date" header="Data zakończenia">
            <template #body="{ data }: { data: Project }">
                {{ dayjs(data.end_date).format('DD.MM.YYYY') }}
            </template>
        </Column>
        <Column field="price" header="Cena" />
        <Column field="active" header="Aktywny">
            <template #body="{ data }: { data: Project }">
                <span :class="data.active ? 'text-green-600' : 'text-red-600'">
                    {{ data.active ? 'Tak' : 'Nie' }}
                </span>
            </template>
        </Column>
    </DataTable>
</template>
