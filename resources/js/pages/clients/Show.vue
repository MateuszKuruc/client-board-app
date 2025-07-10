<script setup lang="ts">
import EditableField from '@/components/EditableField.vue';
import PageHeading from '@/components/PageHeading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SectionHeading from '@/pages/clients/SectionHeading.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import DataTable from '@volt/DataTable.vue';
import Column from 'primevue/column';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Klienci',
        href: '/klienci',
    },
];

const props = defineProps({
    client: Object,
});

const form = useForm({
    id: props.client.id,
    name: props.client.name,
    slug: props.client.slug,
    email: props.client.email,
    phone: props.client.phone,
    nip: props.client.nip,
    source: props.client.source,
    created_at: props.client.created_at,
    updated_at: props.client.updated_at,
});

const isEditing = ref(false);

const sourceOptions = ['Strona internetowa', 'Social media', 'Polecenie', 'Ads', 'Grupki', 'Useme', 'Inne'];

const editableFields = [
    { key: 'name', label: 'Nazwa' },
    { key: 'email', label: 'Email' },
    { key: 'phone', label: 'Telefon' },
    { key: 'nip', label: 'NIP' },
    {
        key: 'source',
        label: 'Źródło',
        type: 'select',
        options: sourceOptions,
    },
];

function submitEdit() {
    form.put(route('clients.update', props.client.slug), {
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
</script>

<template>
    <Head title="Klienci" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!--            <PageHeading />-->
            <PageHeading :title="form.name" :isEditing="isEditing" @cancel="cancelEdit" @save="submitEdit" @edit="startEdit" />
            <!--PROFILE -->

            <div>
                <SectionHeading heading="Informacje o kliencie" subheading="Pełny profil klienta" />
                <div class="mt-6 border-t border-gray-100">
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

                    <!--                        Aktywne projekty -->

                    <div class="flex flex-col gap-12">


                    <div class="mt-6">
                        <SectionHeading heading="Lista aktywnych projektów" subheading="Śledź projekty, które są aktualnie w realizacji" />
                        <DataTable class="mt-6" :value="client.projects" dataKey="id">
                            <Column field="name" header="Projekt" />
                            <Column field="service.name" header="Usługa" />
                            <Column field="start_date" header="Data startu" />
                            <Column field="end_date" header="Data zakończenia" />
                            <Column field="price" header="Cena" />
                            <Column field="active" header="Aktywny">
                                <template #body="{ data }">
                                    <span class='text-green-600'>
                                        Tak
                                    </span>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                    <!--                        Nieaktywne projekty -->
                    <div class="mt-6">
                        <SectionHeading heading="Lista nieaktywnych projektów" subheading="Sprawdź zakończone oraz anulowane projekty" />
                        <DataTable class="mt-6" :value="client.projects" dataKey="id">
                            <Column field="name" header="Projekt" />
                            <Column field="service.name" header="Usługa" />
                            <Column field="start_date" header="Data startu" />
                            <Column field="end_date" header="Data zakończenia" />
                            <Column field="price" header="Cena" />
                            <Column field="active" header="Aktywny">
                                <template #body="{ data }">
                                    <span v-if="!data.active" class="text-red-600">
                                        Nie
                                    </span>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                    </div>
                    <!--                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">-->
                    <!--                            <dt class="text-sm/6 font-medium text-gray-900">Attachments</dt>-->
                    <!--                            <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">-->
                    <!--                                <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">-->
                    <!--                                    <li class="flex items-center justify-between py-4 pr-5 pl-4 text-sm/6">-->
                    <!--                                        <div class="flex w-0 flex-1 items-center">-->
                    <!--                                            <svg-->
                    <!--                                                class="size-5 shrink-0 text-gray-400"-->
                    <!--                                                viewBox="0 0 20 20"-->
                    <!--                                                fill="currentColor"-->
                    <!--                                                aria-hidden="true"-->
                    <!--                                                data-slot="icon"-->
                    <!--                                            >-->
                    <!--                                                <path-->
                    <!--                                                    fill-rule="evenodd"-->
                    <!--                                                    d="M15.621 4.379a3 3 0 0 0-4.242 0l-7 7a3 3 0 0 0 4.241 4.243h.001l.497-.5a.75.75 0 0 1 1.064 1.057l-.498.501-.002.002a4.5 4.5 0 0 1-6.364-6.364l7-7a4.5 4.5 0 0 1 6.368 6.36l-3.455 3.553A2.625 2.625 0 1 1 9.52 9.52l3.45-3.451a.75.75 0 1 1 1.061 1.06l-3.45 3.451a1.125 1.125 0 0 0 1.587 1.595l3.454-3.553a3 3 0 0 0 0-4.242Z"-->
                    <!--                                                    clip-rule="evenodd"-->
                    <!--                                                />-->
                    <!--                                            </svg>-->
                    <!--                                            <div class="ml-4 flex min-w-0 flex-1 gap-2">-->
                    <!--                                                <span class="truncate font-medium">resume_back_end_developer.pdf</span>-->
                    <!--                                                <span class="shrink-0 text-gray-400">2.4mb</span>-->
                    <!--                                            </div>-->
                    <!--                                        </div>-->
                    <!--                                        <div class="ml-4 shrink-0">-->
                    <!--                                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>-->
                    <!--                                        </div>-->
                    <!--                                    </li>-->
                    <!--                                    <li class="flex items-center justify-between py-4 pr-5 pl-4 text-sm/6">-->
                    <!--                                        <div class="flex w-0 flex-1 items-center">-->
                    <!--                                            <svg-->
                    <!--                                                class="size-5 shrink-0 text-gray-400"-->
                    <!--                                                viewBox="0 0 20 20"-->
                    <!--                                                fill="currentColor"-->
                    <!--                                                aria-hidden="true"-->
                    <!--                                                data-slot="icon"-->
                    <!--                                            >-->
                    <!--                                                <path-->
                    <!--                                                    fill-rule="evenodd"-->
                    <!--                                                    d="M15.621 4.379a3 3 0 0 0-4.242 0l-7 7a3 3 0 0 0 4.241 4.243h.001l.497-.5a.75.75 0 0 1 1.064 1.057l-.498.501-.002.002a4.5 4.5 0 0 1-6.364-6.364l7-7a4.5 4.5 0 0 1 6.368 6.36l-3.455 3.553A2.625 2.625 0 1 1 9.52 9.52l3.45-3.451a.75.75 0 1 1 1.061 1.06l-3.45 3.451a1.125 1.125 0 0 0 1.587 1.595l3.454-3.553a3 3 0 0 0 0-4.242Z"-->
                    <!--                                                    clip-rule="evenodd"-->
                    <!--                                                />-->
                    <!--                                            </svg>-->
                    <!--                                            <div class="ml-4 flex min-w-0 flex-1 gap-2">-->
                    <!--                                                <span class="truncate font-medium">coverletter_back_end_developer.pdf</span>-->
                    <!--                                                <span class="shrink-0 text-gray-400">4.5mb</span>-->
                    <!--                                            </div>-->
                    <!--                                        </div>-->
                    <!--                                        <div class="ml-4 shrink-0">-->
                    <!--                                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>-->
                    <!--                                        </div>-->
                    <!--                                    </li>-->
                    <!--                                </ul>-->
                    <!--                            </dd>-->
                    <!--                        </div>-->
                </div>
            </div>
        </div>
    </AppLayout>
</template>
