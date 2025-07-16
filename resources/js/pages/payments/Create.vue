<script setup lang="ts">
import FormLayout from '@/components/FormLayout.vue';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import Button from '@/components/volt/Button.vue';
import DatePicker from '@/components/volt/DatePicker.vue';
import InputText from '@/components/volt/InputText.vue';
import Select from '@/components/volt/Select.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import { Project } from '@/types/models';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Płatności',
        href: '/platnosci',
    },
    {
        title: 'Nowa płatność',
        href: '/platnosci',
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
    project_id: null,
    amount: null,
    status: null,
    payment_date: null,
});

const submit = () => {
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
            toast.add({ severity: 'success', summary: 'Płatność dodana poprawnie', detail: 'Dane zostały zapisane w systmie', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Płatność nie została zapisana', life: 3000 });
        },
    });
};
</script>

<template>
    <Head title="Płatności" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FormLayout title="Dodaj nową płatność" description="Uzupełnij wymagane pola i zapisz płatność">
            <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="project">Projekt</Label>
                            <Select
                                v-model="form.project_id"
                                :options="projects"
                                optionLabel="name"
                                optionValue="id"
                                editable
                                placeholder="Wybierz projekt"
                                required
                            />
                            <InputError :message="form.errors.project_id" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="amount">Kwota płatności</Label>
                            <InputText id="amount" v-model="form.amount" placeholder="1000" />
                            <InputError :message="form.errors.amount" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="status">Status</Label>
                            <Select
                                v-model="form.status"
                                :options="statusOptions"
                                optionLabel="name"
                                optionValue="value"
                                editable
                                placeholder="Wybierz status"
                            />
                            <InputError :message="form.errors.status" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="payment_date">Data płatności</Label>
                            <DatePicker v-model="form.payment_date" showIcon iconDisplay="input" placeholder="Data lub puste pole" />
                            <InputError :message="form.errors.payment_date" />
                        </div>

                        <Button
                            type="submit"
                            class="mt-2 w-full"
                            tabindex="5"
                            :disabled="form.processing || !form.project_id || !form.amount || !form.status"
                        >
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            {{ form.processing ? 'Zapisywanie...' : 'Utwórz płatność' }}
                        </Button>
                    </div>
                </form>
            </div>
        </FormLayout>
    </AppLayout>
</template>
