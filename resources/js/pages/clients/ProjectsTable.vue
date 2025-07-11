<script setup lang="ts">
import SectionHeading from '@/pages/clients/SectionHeading.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Column from 'primevue/column';
import dayjs from '@/plugins/dayjs'

defineProps({
    projects: Array,
    heading: String,
    subheading: String,
});
</script>

<template>
    <SectionHeading :heading="heading" :subheading="subheading" />
    <span v-if="projects.length < 1" class="mt-4 block text-xl font-semibold">Brak</span>
    <DataTable v-if="projects.length >= 1" class="mt-6" :value="projects" dataKey="id">
        <Column field="name" header="Projekt" />
        <Column field="service.name" header="Usługa" />
        <Column field="start_date" header="Data startu">
            <template #body="{ data }">
                {{ dayjs(data.start_date).format('DD.MM.YYYY') }}
            </template>
        </Column>
        <Column field="end_date" header="Data zakończenia">
            <template #body="{ data }">
                {{ dayjs(data.end_date).format('DD.MM.YYYY') }}
            </template>
        </Column>
        <Column field="price" header="Cena" />
        <Column field="active" header="Aktywny">
            <template #body="{ data }">
                <span :class="data.active ? 'text-green-600' : 'text-red-600'">
                    {{ data.active ? 'Tak' : 'Nie' }}
                </span>
            </template>
        </Column>
    </DataTable>
</template>
