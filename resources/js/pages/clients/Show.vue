<script setup lang="ts">
import ActionButtons from '@/components/ActionButtons.vue';
import BarChart from '@/components/BarChart.vue';
import EditableField from '@/components/EditableField.vue';
import NotesSection from '@/components/NotesSection.vue';
import PageHeadingClient from '@/components/PageHeadingClient.vue';
import ProjectsTable from '@/components/ProjectsTable.vue';
import ReusableCard from '@/components/ReusableCard.vue';
import SectionHeading from '@/components/SectionHeading.vue';
import TagSection from '@/components/TagSection.vue';
import { locationOptions } from '@/constants/locationOptions';
import { sourceOptions } from '@/constants/sourceOptions';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import type { BreadcrumbItem } from '@/types';
import { Client, Note, Tag } from '@/types/models';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { computed, ref, Ref } from 'vue';

const toast = useToast();

const props = defineProps<{
    client: Client;
    tags: Tag[];
    notes: Note[];
}>();

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: 'Klienci',
        href: route('clients.index'),
    },
    {
        title: props.client.name,
        href: `/klienci/${props.client.slug}`,
    },
]);

type EditableField =
    | { key: string; label: string; type?: 'text' }
    | {
          key: string;
          label: string;
          type: 'select';
          options: typeof sourceOptions | typeof locationOptions;
      };

const editableFields: EditableField = [
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
    {
        key: 'location',
        label: 'Lokalizacja',
        type: 'select',
        options: locationOptions,
    },
] as const;

const form = useForm<Client>({
    id: props.client.id,
    name: props.client.name,
    slug: props.client.slug,
    email: props.client.email,
    phone: props.client.phone,
    nip: props.client.nip,
    source: props.client.source,
    location: props.client.location,
    created_at: props.client.created_at,
    updated_at: props.client.updated_at,
});

const isEditing: Ref<boolean> = ref(false);

function submitEdit() {
    form.put(route('clients.update', props.client.slug), {
        preserveScroll: true,
        onSuccess: () => {
            isEditing.value = false;
            toast.add({ severity: 'success', summary: 'Profil klienta zaktualizowany', detail: 'Zmiany zostały pomyślnie zapisane', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Wystąpił błąd', detail: 'Zmiany nie zostały zapisane', life: 3000 });
        },
    });
}

function startEdit() {
    isEditing.value = !isEditing.value;
}

function cancelEdit() {
    form.reset();
    form.clearErrors();
    isEditing.value = !isEditing.value;
}

const expectedActivePaymentsTotal = Number(
    props.client.projects
        .filter((p) => p.active)
        .reduce((total, project) => total + Number(project.price), 0)
        .toFixed(2),
);

const activeProjects = props.client.projects.filter((p) => p.active);

const activePaidProjects = Number(
    activeProjects
        .flatMap((p) => p.payments)
        .filter((p) => p.status === 'paid')
        .reduce((total, payment) => total + Number(payment.amount), 0)
        .toFixed(2),
);

const remainingPaymentsSum = Number((expectedActivePaymentsTotal - activePaidProjects).toFixed(2));

const lifetimeValue = Number(
    props.client.projects
        .flatMap((p) => p.payments)
        .filter((p) => p.status === 'paid')
        .reduce((total, project) => total + Number(project.amount), 0)
        .toFixed(2),
);

const monthlyTotals = computed<Record<string, number>>(() => {
    const totals: Record<string, number> = {};

    if (!props.client?.projects?.length) return totals;

    props.client.projects
        .flatMap((project) => project.payments || [])
        .filter((payment) => payment.status === 'paid')
        .forEach((payment) => {
            const month = payment.payment_date?.slice(0, 7); // "YYYY-MM"
            const amount = Number(payment.amount);
            if (!month || isNaN(amount)) return;

            if (!totals[month]) totals[month] = 0;
            totals[month] += amount;
        });

    return totals;
});

const lastMonthPaidTotal = computed<string>(() => {
    const now = new Date();
    const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);

    const targetMonth = lastMonth.toISOString().slice(0, 7);

    return Number(
        props.client.projects
            .flatMap((p) => p.payments || null)
            .filter((p) => p.status === 'paid' && p.payment_date.startsWith(targetMonth))
            .reduce((total, p) => total + Number(p.amount), 0)
            .toFixed(2),
    );
});

const sortedMonths = computed(() => Object.keys(monthlyTotals.value).sort());
const chartLabels = computed(() => sortedMonths.value.map((month) => dayjs(month).format('MMM YYYY')));
const chartValues = computed(() => sortedMonths.value.map((month) => monthlyTotals.value[month]));
</script>

<template>
    <Head :title="props.client.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
            <PageHeadingClient :title="form.name" :client="client" />
            <TagSection :tags="tags" :client="client" />

            <div class="grid xl:grid-cols-3 gap-4 py-8">
                <ReusableCard :value="lifetimeValue" heading="Łączna wartość klienta" subheading="Lifetime value" />
                <ReusableCard :value="lastMonthPaidTotal" heading="Ostatni miesiąc" subheading="Opłacone usługi" />
                <ReusableCard
                    :value="expectedActivePaymentsTotal"
                    heading="Spodziewane płatności"
                    subheading="Dotyczy aktywnych projektów"
                    :secondValue="remainingPaymentsSum"
                />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <SectionHeading heading="Informacje o kliencie" subheading="Pełny profil klienta" />
                    <ActionButtons :isEditing="isEditing" @cancel="cancelEdit" @save="submitEdit" @edit="startEdit" buttonLabel="Edytuj profil" />
                </div>
                <div class="mt-6 border-t border-gray-100">
                    <ul class="divide-y divide-gray-100">
                        <li v-for="field in editableFields" :key="field.key">
                            <EditableField
                                v-model="form[field.key]"
                                :isEditing="isEditing"
                                :label="field.label"
                                :type="field.type || 'text'"
                                :options="field.options || []"
                                :error="form.errors[field.key]"
                            />
                        </li>
                    </ul>

                    <NotesSection :noteable="client" :notes="notes" />

                    <BarChart v-if="lifetimeValue" class="h-100 max-w-[1920px] my-12" :labels="chartLabels" :values="chartValues" />

                    <div class="my-6 flex flex-col gap-4">
                        <div>
                            <ProjectsTable
                                :projects="client.projects.filter((p) => p.active)"
                                heading="Lista aktywnych projektów"
                                subheading="Śledź projekty, które są aktualnie w toku"
                                button
                                :href="route('projects.create', client.slug)"
                                :client="client"
                            />
                        </div>

                        <div>
                            <ProjectsTable
                                :projects="client.projects.filter((p) => !p.active)"
                                heading="Lista nieaktywnych projektów"
                                subheading="Sprawdź zakończone lub wciąż nieopłacone projekty"
                                :client="client"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
