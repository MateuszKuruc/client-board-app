<script setup lang="ts">
import ActionButtons from '@/components/ActionButtons.vue';
import EditableField from '@/components/EditableField.vue';
import PageHeadingProject from '@/components/PageHeadingProject.vue';
import SectionHeading from '@/components/SectionHeading.vue';
import TagSection from '@/components/TagSection.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import { Project, Service } from '@/types/models';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, Ref } from 'vue';
import PaymentsTable from '@/components/PaymentsTable.vue';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const { project, services } = defineProps<{
    project: Project;
    services: Service[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Klienci',
        href: route('clients.index'),
    },
    {
        title: project.client.name,
        href: route('clients.show', project.client.slug),
    },
    {
        title: 'Projekty',
        href: route('projects.index'),
    },
    {
        title: project.name,
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
      }
    | {
          key: string;
          label: string;
          type: 'picker';
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

        data.start_date = dayjs(data.start_date).format('YYYY-MM-DD');

        data.end_date = dayjs(data.end_date).format('YYYY-MM-DD');

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
            onSuccess: (page) => {
                isEditing.value = false;
                form.price = page.props.project.price;
                form.start_date = page.props.project.start_date;
                form.end_date = page.props.project.end_date;
                toast.add({ severity: 'success', summary: 'Projekt zaktualizowany', detail: 'Zmiany zostały pomyślnie zapisane', life: 3000 });
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Zmiany nie zostały zapisane', life: 3000 });
            }
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
    { key: 'start_date', label: 'Data startu', type: 'picker' },
    { key: 'end_date', label: 'Data zakończenia', type: 'picker', minDate: new Date(form.start_date) },
] as const;
</script>

<template>
    <Head :title="breadcrumbs[0].title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
            <div class="flex flex-col gap-4">
                <PageHeadingProject :title="form.name" :project="project" />
                <TagSection />
            </div>

            <div class="flex items-center justify-between">
                <SectionHeading heading="Informacje o projekcie" subheading="Szczegółowe dane projektu" />
                <ActionButtons :isEditing="isEditing" @cancel="cancelEdit" @save="submitEdit" @edit="startEdit" buttonLabel="Edytuj projekt" />
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
                            :minDate="field.key === 'end_date' ? new Date(form.start_date) : undefined"
                        />
                    </li>
                </ul>
            </div>

            <div class="flex flex-col mt-6 gap-4">
                <div>
                    <PaymentsTable
                        :payments="project.payments.filter((p) => p.status === 'paid')"
                        heading="Lista zaksięgowanych płatności"
                        subheading="Śledź potwierdzone płatności związane z projektem"
                        button
                    />
                </div>

                <div>
                    <PaymentsTable
                        :payments="project.payments.filter((p) => p.status === 'pending')"
                        heading="Lista oczekujących płatności"
                        subheading="Sprawdź płatności, które wciąż nie zostały zaksięgowane"
                    />
                </div>

                <div>
                    <PaymentsTable
                        :payments="project.payments.filter((p) => p.status === 'cancelled')"
                        heading="Lista anulowanych płatności"
                        subheading="Płatności, które nie będą opłacone"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
