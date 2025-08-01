<script setup lang="ts">
import SectionHeading from '@/components/SectionHeading.vue';
import StyledLink from '@/components/StyledLink.vue';
import DataTable from '@/components/volt/DataTable.vue';
import Divider from '@/components/volt/Divider.vue';
import dayjs from '@/plugins/dayjs';
import { Client, Project } from '@/types/models';
import { Plus } from 'lucide-vue-next';
import Column from 'primevue/column';

const props = withDefaults(
    defineProps<{
        projects: Project[];
        heading: string;
        subheading?: string;
        button?: boolean;
        href?: string;
        client: Client;
    }>(),
    {
        button: false,
    },
);
</script>

<template>
    <div class="flex items-center justify-between">
        <SectionHeading :heading="heading" :subheading="subheading" />
        <StyledLink v-if="button" :href="href" variant="outline">
            <Plus class="w-5 text-gray-400" />
            Dodaj projekt</StyledLink
        >
    </div>
    <div v-if="projects.length < 1" class="mt-4 flex flex-col gap-4 text-xl font-semibold text-gray-500">
        Brak
        <Divider />
    </div>
    <DataTable v-if="projects.length >= 1" :value="projects" dataKey="id">
        <Column field="name" header="Projekt">
            <template #body="{ data }">
                <StyledLink :href="route('projects.show', { client: client.slug, project: data.id })" variant="text">
                    <span class="capitalize-first-letter">
                        {{ data.name }}
                    </span>
                </StyledLink>
            </template>
        </Column>
        <Column field="service.name" header="Usługa" />
        <Column field="start_date" header="Data startu">
            <template #body="{ data: project }: { data: Project }">
                {{ dayjs(project.start_date).format('DD.MM.YYYY') }}
            </template>
        </Column>
        <Column field="end_date" header="Data zakończenia">
            <template #body="{ data }: { data: Project }">
                {{ dayjs(data.end_date).format('DD.MM.YYYY') }}
            </template>
        </Column>
        <Column field="price" header="Cena">
            <template #body="{ data: project }: { data: Project }">
                {{ project.price }} zł
            </template>
        </Column>
        <Column field="active" header="Aktywny">
            <template #body="{ data: project }: { data: Project }">
                <span :class="project.active ? 'text-green-600' : 'text-red-600'">
                    {{ project.active ? 'Tak' : 'Nie' }}
                </span>
            </template>
        </Column>
    </DataTable>
</template>
