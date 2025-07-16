<script setup lang="ts">
import FormLayout from '@/components/FormLayout.vue';
import InputField from '@/components/InputField.vue';
import SelectField from '@/components/SelectField.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { locationOptions } from '@/constants/locationOptions';
import { sourceOptions } from '@/constants/sourceOptions';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import { Project } from '@/types/models';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Klienci',
        href: '/klienci',
    },
    {
        title: 'Nowy klient',
        href: '/klienci',
    },
];

const { projects } = defineProps<{
    projects: Project[];
}>();

const statusOptions = [
    {
        value: 'paid',
        name: 'Opłacona',
    },
    {
        value: 'pending',
        name: 'Oczekująca',
    },
    {
        value: 'cancelled',
        name: 'Anulowana',
    },
];

const form = useForm<Payment>({
    name: null,
    email: null,
    phone: null,
    nip: null,
    source: null,
    location: null,
});

const submit = () => {
    form.clearErrors();

    form.transform((data) => {
        if (data.payment_date) {
            data.payment_date = dayjs(data.payment_date).format('YYYY-MM-DD');
        } else {
            data.payment_date = null;
        }

        return data;
    }).post(route('payments.store'), {
        onSuccess: () => {
            form.reset();
            toast.add({ severity: 'success', summary: 'Płatność dodana poprawnie', detail: 'Dane zostały zapisane w systemie', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Płatność nie została zapisana', life: 3000 });
        },
    });
};
</script>

<template>
    <Head title="Nowa płatność" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FormLayout title="Dodaj nowego klienta" description="Uzupełnij wymagane pola i utwórz profil klienta">
            <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-6">
                        <InputField id="name" label="Nazwa" :error="form.errors.name" v-model="form.name" placeholder="Jan Kowalski" required />

                        <InputField
                            id="email"
                            label="Email"
                            :error="form.errors.email"
                            v-model="form.email"
                            placeholder="example@gmail.com"
                            required
                        />

                        <InputField id="phone" label="Telefon" :error="form.errors.phone" v-model="form.phone" placeholder="531380713" />

                        <InputField id="nip" label="NIP" :error="form.errors.nip" v-model="form.nip" placeholder="7941840960" required />

                        <SelectField
                            id="source"
                            label="Źródło"
                            :error="form.errors.source"
                            v-model="form.source"
                            :options="sourceOptions"
                            placeholder="Wybierz źródło"
                            required
                        />

                        <SelectField
                            id="location"
                            label="Lokalizacja"
                            :error="form.errors.location"
                            v-model="form.location"
                            :options="locationOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Wybierz lokalizację"
                            required
                        />

                        <SubmitButton
                            :processing="form.processing"
                            buttonLabel="Utwórz profil"
                            loadingLabel="Zapisywanie..."
                            :disabled="form.processing || !form.name || !form.email || !form.nip || !form.source || !form.location"
                        />
                    </div>
                </form>
            </div>
        </FormLayout>
    </AppLayout>
</template>
