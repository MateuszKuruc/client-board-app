<script setup lang="ts">
import DateField from '@/components/DateField.vue';
import FormLayout from '@/components/FormLayout.vue';
import InputField from '@/components/InputField.vue';
import SelectField from '@/components/SelectField.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import Message from '@/components/volt/Message.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import { Project } from '@/types/models';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { computed } from 'vue';

const props = defineProps<{
    projects: Project[];
    project: Project | null;
}>();

const projectParam = Number(props.project?.id) ?? null;

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

const form = useForm({
    project_id: projectParam,
    amount: null,
    status: null,
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

const projectOptions = computed(() =>
    props.projects.map((project) => ({
        value: project.id,
        label: `${project.name} [${project.client.name}]`,
    })),
);
</script>

<template>
    <Head title="Nowa płatność" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FormLayout title="Dodaj nową płatność" description="Uzupełnij wymagane pola i zapisz płatność">
            <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-6">
                        <SelectField
                            id="project"
                            label="Projekt"
                            :error="form.errors.project_id"
                            v-model="form.project_id"
                            :options="projectOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Wybierz projekt"
                            required
                            :disabled="project !== null"
                        />

                        <Message v-if="project !== null" size="small" severity="warn" class="mb-2"
                            >Projekt: {{ project.name }} <span class="block">Klient: {{ project.client.name }}</span></Message
                        >

                        <InputField
                            id="amount"
                            label="Kwota płatności"
                            :error="form.errors.amount"
                            v-model="form.amount"
                            placeholder="1000"
                            required
                        />

                        <SelectField
                            id="status"
                            label="Status"
                            :error="form.errors.status"
                            v-model="form.status"
                            :options="statusOptions"
                            optionLabel="name"
                            optionValue="value"
                            placeholder="Wybierz status"
                            required
                        />

                        <DateField id="payment_date" label="Data płatności" v-model="form.payment_date" placeholder="Data lub puste pole" />

                        <SubmitButton
                            :processing="form.processing"
                            buttonLabel="Utwórz płatność"
                            loadingLabel="Zapisywanie..."
                            :disabled="form.processing || !form.project_id || !form.amount || !form.status"
                        />
                    </div>
                </form>
            </div>
        </FormLayout>
    </AppLayout>
</template>
