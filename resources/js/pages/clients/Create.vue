<script setup lang="ts">
import FormLayout from '@/components/FormLayout.vue';
import InputField from '@/components/InputField.vue';
import SelectField from '@/components/SelectField.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { locationOptions } from '@/constants/locationOptions';
import { sourceOptions } from '@/constants/sourceOptions';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Lead } from '@/types/models';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const props = defineProps<{
    lead: Lead | null;
}>();

const leadPhone = props.lead?.phone ?? null;

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

const form = useForm({
    name: null,
    email: props.lead?.email ?? null,
    phone: props.lead?.phone ?? null,
    nip: null,
    source: null,
    location: null,
    lead_id: props.lead?.id ?? null,
});

const submit = () => {
    form.clearErrors();

    form.post(route('clients.store'), {
        onSuccess: () => {
            form.reset();
            if (props.lead !== null) {
                toast.add({ severity: 'success', summary: 'Lead zamieniony w klienta', detail: 'Dane zostały zapisane w systemie', life: 3000 });
            } else {
                toast.add({ severity: 'success', summary: 'Klient dodany poprawnie', detail: 'Dane zostały zapisane w systemie', life: 3000 });
            }
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Klient nie został zapisany', life: 3000 });
        },
    });
};
</script>

<template>
    <Head title="Nowy klient" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FormLayout
            :title="lead === null ? 'Dodaj nowego klienta' : 'Zmień leada w klienta'"
            description="Uzupełnij wymagane pola i utwórz profil klienta"
        >
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
                            :disabled="lead !== null"
                        />

                        <InputField
                            id="phone"
                            label="Telefon"
                            :error="form.errors.phone"
                            v-model="form.phone"
                            placeholder="531380713"
                            :disabled="leadPhone !== null"
                        />

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
