<script setup lang="ts">
import ActionButtons from '@/components/ActionButtons.vue';
import EditableField from '@/components/EditableField.vue';
import PageHeadingProject from '@/components/PageHeadingProject.vue';
import SectionHeading from '@/components/SectionHeading.vue';
import TagSection from '@/components/TagSection.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Project, Service } from '@/types/models';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, Ref } from 'vue';

const { project, services } = defineProps<{
    project: Project;
    services: Service[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: `Projekt: ${project.name}`,
        href: `/klienci/${project.client.slug}/projekty/${project.id}`,
    },
];

const serviceOptions: string[] = services.map((service) => service.name);
const statusOptions: string[] = ['Aktywny', 'Nieaktywny'];
const typeOptions: string[] = ['Subskrypcja', 'Standard'];

type editableField =
    | {
          key: string;
          label: string;
          type?: 'text';
      }
    | {
          key: string;
          label: string;
          type: 'select';
          options: string[];
      };

const serviceIdToName = (serviceId: number): string => {
    const service = services.find((s) => s.id === serviceId);
    return service ? service.name : '';
};

const serviceNameToId = (serviceName: string): number => {
    const service = services.find((s) => s.name === serviceName);
    return service ? service.id : 0;
};

const activeToString = (active: boolean): string => {
    return active ? 'Aktywny' : 'Nieaktywny';
};

const stringToActive = (status: string): boolean => {
    return status === 'Aktywny';
};

const isEditing: Ref<boolean> = ref(false);

function submitEdit() {
    form.transform((data) => {
        data.service_id = serviceNameToId(data.service_name);
        data.active = stringToActive(data.active_status);

        delete data.service_name;
        delete data.active_status;

        return data;
    }).put(
        route('projects.update', {
            client: project.client.slug,
            project: project.id,
        }),
        {
            preserveScroll: true,
            onBefore({ data }) {
                console.log('🚀 payload about to go out:', data);
            },
            onSuccess: (page) => {
                isEditing.value = false;
                form.price = page.props.project.price;
            },
        },
    );
}

function startEdit() {
    isEditing.value = !isEditing.value;
}

function cancelEdit() {
    form.reset();
    isEditing.value = !isEditing.value;
}

const form = useForm<Project>({
    name: project.name,
    service_id: project.service_id,
    service_name: serviceIdToName(project.service_id),
    active: project.active,
    active_status: activeToString(project.active),
    price: project.price,
    type: project.type,
    start_date: project.start_date,
    end_date: project.end_date,
});

const editableFields: editableField[] = [
    { key: 'name', label: 'Nazwa' },
    {
        key: 'service_name',
        label: 'Usługa',
        type: 'select',
        options: serviceOptions,
    },
    { key: 'price', label: 'Cena' },
    {
        key: 'active_status',
        label: 'Status',
        type: 'select',
        options: statusOptions,
    },
    { key: 'type', label: 'Rodzaj płatności', type: 'select', options: typeOptions },
    { key: 'start_date', label: 'Data startu' },

    { key: 'end_date', label: 'Data zakończenia' },
] as const;
</script>

<template>
    <Head :title="breadcrumbs[0].title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div>
                <PageHeadingProject :title="form.name" :project="project" />
                <TagSection />
            </div>

            <!--        test -->
            <div class="flex items-center justify-between">
                <SectionHeading heading="Informacje o projekcie" subheading="Szczegółowe dane projektu" />
                <ActionButtons :isEditing="isEditing" @cancel="cancelEdit" @save="submitEdit" @edit="startEdit" />
            </div>

            <div class="border-t border-gray-100">
                <ul class="divide-y divide-gray-100">
                    <li v-for="field in editableFields" :key="field.key">
                        <EditableField
                            v-model="form[field.key]"
                            :isEditing="isEditing"
                            :label="field.label"
                            :type="field.type || 'text'"
                            :options="field.options || []"
                        />
                    </li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>
