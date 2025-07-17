<script setup lang="ts">
import FormLayout from '@/components/FormLayout.vue';
import InputField from '@/components/InputField.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Leady',
        href: '/leady',
    },
    {
        title: 'Nowy lead',
        href: '/leady',
    },
];

const form = useForm<Payment>({
    email: null,
    phone: null,
});

const submit = () => {
    form.clearErrors();

    form.post(route('leads.store'), {
        onSuccess: () => {
            form.reset();
            toast.add({ severity: 'success', summary: 'Lead dodany poprawnie', detail: 'Dane zostały zapisane w systemie', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Lead nie został zapisany', life: 3000 });
        },
    });
};
</script>

<template>
    <Head title="Nowy lead" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FormLayout title="Dodaj nowego leada" description="Uzupełnij wymagane pola i zapisz leada">
            <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-6">
                        <InputField
                            id="email"
                            label="Email"
                            :error="form.errors.email"
                            v-model="form.email"
                            placeholder="example@gmail.com"
                            required
                        />
                        <InputField id="phone" label="Telefon" :error="form.errors.phone" v-model="form.phone" placeholder="531380713" />

                        <SubmitButton
                            :processing="form.processing"
                            buttonLabel="Utwórz leada"
                            loadingLabel="Zapisywanie..."
                            :disabled="form.processing || !form.email"
                        />
                    </div>
                </form>
            </div>
        </FormLayout>
    </AppLayout>
</template>
