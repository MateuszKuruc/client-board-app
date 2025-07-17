<script setup lang="ts">
import DateField from '@/components/DateField.vue';
import FormLayout from '@/components/FormLayout.vue';
import InputField from '@/components/InputField.vue';
import SelectField from '@/components/SelectField.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { booleanPaidOptions } from '@/constants/booleanPaidOptions';
import { expenseTypeOptions } from '@/constants/expenseTypeOptions';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Koszty',
        href: '/koszty',
    },
    {
        title: 'Nowy koszt',
        href: '/koszty/dodaj',
    },
];

const form = useForm<Payment>({
    name: null,
    amount: null,
    is_paid: null,
    type: null,
    payment_date: null,
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
    }).post(route('expenses.store'), {
        onSuccess: () => {
            form.reset();
            toast.add({ severity: 'success', summary: 'Koszt dodany poprawnie', detail: 'Dane zostały zapisane w systemie', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Koszt nie został zapisany', life: 3000 });
        },
    });
};
</script>

<template>
    <Head title="Nowy koszt" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FormLayout title="Dodaj nową płatność" description="Uzupełnij wymagane pola i zapisz płatność">
            <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-6">
                        <InputField
                            id="name"
                            label="Nazwa wydatku"
                            :error="form.errors.name"
                            v-model="form.name"
                            placeholder="Subskrypcja Trello"
                            required
                        />

                        <InputField
                            id="amount"
                            label="Koszt produktu"
                            :error="form.errors.amount"
                            v-model="form.amount"
                            placeholder="1000"
                            required
                        />

                        <SelectField
                            id="is_paid"
                            label="Status płatności"
                            :error="form.errors.is_paid"
                            v-model="form.is_paid"
                            :options="booleanPaidOptions"
                            optionValue="value"
                            optionLabel="label"
                            placeholder="Wybierz status"
                            required
                        />

                        <SelectField
                            id="type"
                            label="Rodzaj płatności"
                            :error="form.errors.type"
                            v-model="form.type"
                            :options="expenseTypeOptions"
                            placeholder="Wybierz status"
                            required
                        />

                        <DateField
                            id="payment_date"
                            label="Data płatności"
                            v-model="form.payment_date"
                            placeholder="Data lub puste pole"
                            :required="form.is_paid"
                        />

                        <SubmitButton
                            :processing="form.processing"
                            buttonLabel="Utwórz koszt"
                            loadingLabel="Zapisywanie..."
                            :disabled="form.processing || !form.name || !form.amount || !form.is_paid || !form.type"
                        />
                    </div>
                </form>
            </div>
        </FormLayout>
    </AppLayout>
</template>
