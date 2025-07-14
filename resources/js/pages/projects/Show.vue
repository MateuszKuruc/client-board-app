<script setup lang="ts">
import ActionButtons from '@/components/ActionButtons.vue';
import EditableField from '@/components/EditableField.vue';
import PageHeadingProject from '@/components/PageHeadingProject.vue';
import SectionHeading from '@/components/SectionHeading.vue';
import TagSection from '@/components/TagSection.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Project, Services } from '@/types/models';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, Ref } from 'vue';

const { project } = defineProps<{
    project: Project;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: `Projekt: ${project.name}`,
        href: `/klienci/${project.client.slug}/projekty/${project.id}`,
    },
];

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
    options: Services[];
};

const serviceOptions: Services[] = ['Strona internetowa', 'Social media', 'Polecenie', 'Ads', 'Grupki', 'Useme', 'Inne'];


const isEditing: Ref<boolean> = ref(false);

function submitEdit() {
    form.put(route('projects.update', project.id), {
        preserveScroll: true,
        onSuccess: () => {
            isEditing.value = false;
        },
    });
}

function startEdit() {
    isEditing.value = !isEditing.value;
}

function cancelEdit() {
    form.reset();
    isEditing.value = !isEditing.value;
}

const form = useForm<Project>({
    id: project.id,
    name: project.name,
    client: project.client,
    service: project.service,
    active: project.active,
    price: project.price,
    type: project.type,
    start_date: project.start_date,
    end_date: project.end_date,
    created_at: project.created_at,
    updated_at: project.updated_at,
});



const editableFields: editableField[] = [
    { key: 'name', label: 'Nazwa' },
    { key: 'price', label: 'Cena' },
    { key: 'active', label: 'Status' },
    { key: 'type', label: 'Rodzaj' },
    { key: 'start_date', label: 'Data startu' },
    { key: 'end_date', label: 'Data zakończenia' },
    {
        key: 'service',
        label: 'Usługa',
        type: 'select',
        options: serviceOptions,
    },
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
