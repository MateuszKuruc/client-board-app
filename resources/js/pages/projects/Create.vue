<script setup lang="ts">
import DateField from '@/components/DateField.vue';
import FormLayout from '@/components/FormLayout.vue';
import InputField from '@/components/InputField.vue';
import SelectField from '@/components/SelectField.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { booleanActiveOptions } from '@/constants/booleanActiveOptions';
import { projectTypeOptions } from '@/constants/projectTypeOptions';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

interface Option {
    value: number;
    label: string;
}

const { clients, services } = defineProps<{
    clients: Option[];
    services: Option[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Projekty',
        href: '/projekty',
    },
    {
        title: 'Nowy projekt',
        href: '/projekty',
    },
];

const form = useForm<Payment>({
    name: null,
    client_id: null,
    service_id: null,
    active: null,
    price: null,
    type: null,
    start_date: null,
    end_date: null,
});

const submit = () => {
    form.clearErrors();

    form.transform((data) => {
        if (data.start_date) {
            data.start_date = dayjs(data.start_date).format('YYYY-MM-DD');
        } else {
            data.start_date = null;
        }
        if (data.end_date) {
            data.end_date = dayjs(data.end_date).format('YYYY-MM-DD');
        } else {
            data.end_date = null;
        }

        return data;
    }).post(route('projects.store'), {
        onSuccess: () => {
            form.reset();
            toast.add({ severity: 'success', summary: 'Projekt dodany poprawnie', detail: 'Dane zostały zapisane w systemie', life: 3000 });
        },
        onError: (errors) => {
            if (errors.end_date) {
                toast.add({
                    severity: 'error',
                    summary: 'Błąd daty',
                    detail: 'Data zakończenia musi być późniejsza niż data startu projektu',
                    life: 3000,
                });
            } else {
                toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Projekt nie został zapisany', life: 3000 });
            }
        },
    });
};
</script>

<template>
    <Head title="Nowy projekt" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FormLayout title="Dodaj nowy projekt" description="Uzupełnij wymagane pola i utwórz projekt">
            <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-6">
                        <InputField id="name" label="Nazwa" :error="form.errors.name" v-model="form.name" placeholder="Kampania reklamowa" required />

                        <SelectField
                            id="client_id"
                            label="Klient"
                            :error="form.errors.client_id"
                            v-model="form.client_id"
                            :options="clients"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Wybierz klienta"
                            required
                        />

                        <SelectField
                            id="service_id"
                            label="Usługa"
                            :error="form.errors.service_id"
                            v-model="form.service_id"
                            :options="services"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Wybierz usługę"
                            required
                        />

                        <SelectField
                            id="active"
                            label="Status"
                            :error="form.errors.active"
                            v-model="form.active"
                            :options="booleanActiveOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Wybierz status projektu"
                            required
                        />

                        <InputField id="price" label="Cena" :error="form.errors.price" v-model="form.price" placeholder="1000" required />

                        <SelectField
                            id="type"
                            label="Rodzaj"
                            :error="form.errors.type"
                            v-model="form.type"
                            :options="projectTypeOptions"
                            placeholder="Rodzaj projektu"
                            required
                        />

                        <DateField id="start_date" label="Data startu" v-model="form.start_date" placeholder="Data rozpoczęcia projektu" />
                        <DateField id="end_date" label="Data zakończenia" v-model="form.end_date" placeholder="Data zakończenia projektu" />

                        <SubmitButton
                            :processing="form.processing"
                            buttonLabel="Utwórz projekt"
                            loadingLabel="Zapisywanie..."
                            :disabled="
                                form.processing ||
                                !form.name ||
                                !form.client_id ||
                                !form.service_id ||
                                form.active === null ||
                                !form.price ||
                                !form.type
                            "
                        />
                    </div>
                </form>
            </div>
        </FormLayout>
    </AppLayout>
</template>
