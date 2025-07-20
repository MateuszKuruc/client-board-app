<script setup lang="ts">
import ActionButtons from '@/components/ActionButtons.vue';
import EditableField from '@/components/EditableField.vue';
import PageHeadingBasic from '@/components/PageHeadingBasic.vue';
import PaymentsTable from '@/components/PaymentsTable.vue';
import SectionHeading from '@/components/SectionHeading.vue';
import { statusOptions } from '@/constants/statusOptions';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import { Payment } from '@/types/models';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { ref, Ref } from 'vue';
import NotesBlock from '@/pages/clients/NotesBlock.vue';

const toast = useToast();

const props = defineProps<{
    payment: Payment;
    latestPayments: Payment[];
}>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Klienci',
        href: route('clients.index'),
    },
    {
        title: props.payment.project.client.name,
        href: route('clients.show', props.payment.project.client.slug),
    },
    {
        title: 'Projekty',
        href: route('projects.index'),
    },
    {
        title: props.payment.project.name,
        href: route('projects.show', { client: props.payment.project.client.slug, project: props.payment.project.id }),
    },
    {
        title: 'Płatności',
        href: route('payments.index'),
    },
    {
        title: `Płatność ${props.payment.id}`,
        href: `/klienci/${props.payment.project.client.slug}/projekty/${props.payment.project.id}/platnosci/${props.payment.id}`,
    },
];

type EditableField =
    | {
          key: string;
          label: string;
          type?: 'text';
      }
    | {
          key: string;
          label: string;
          type: 'select';
          options: string[] | { value: string; label: string }[];
      }
    | {
          key: string;
          label: string;
          type: 'picker';
      };

const isEditing: Ref<boolean> = ref(false);

function submitEdit() {
    form.transform((data) => {
        if (data.payment_date) {
            data.payment_date = dayjs(data.payment_date).format('YYYY-MM-DD');
        } else {
            data.payment_date = null;
        }

        return data;
    }).put(route('payments.update', { client: props.payment.project.client.slug, project: props.payment.project.id, payment: props.payment.id }), {
        preserveScroll: true,
        onSuccess: () => {
            isEditing.value = false;
            toast.add({ severity: 'success', summary: 'Płatność zaktualizowana', detail: 'Zmiany zostały pomyślnie zapisane', life: 3000 });
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

const form = useForm<Payment>({
    amount: props.payment.amount,
    status: props.payment.status,
    payment_date: props.payment.payment_date,
});

const editableFields: EditableField[] = [
    { key: 'amount', label: 'Kwota' },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: statusOptions,
    },
    { key: 'payment_date', label: 'Data płatności', type: 'picker' },
] as const;
</script>

<template>
    <Head :title="breadcrumbs[0].title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
            <div class="flex flex-col gap-4">
                <PageHeadingBasic :title="'Płatność nr ' + payment.id" :subtitle="'Powiązany projekt: ' + payment.project.name" />
                <!--                <TagSection />-->
            </div>

            <div class="flex items-center justify-between">
                <SectionHeading heading="Informacje o płatności" subheading="Szczegółowe dane płatności" />
                <ActionButtons :isEditing="isEditing" @cancel="cancelEdit" @save="submitEdit" @edit="startEdit" buttonLabel="Edytuj płatność" />
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
                            :minDate="field.key === 'end_date' ? new Date(form.start_date) : undefined"
                            :error="form.errors[field.key]"
                        />
                    </li>
                </ul>
            </div>

            <div class="mt-6 flex flex-col gap-4">
                <div>
                    <PaymentsTable
                        :payments="
                            payment.project.payments
                                .filter((p) => p.id !== payment.id)
                                .sort((a, b) => new Date(b.payment_date) - new Date(a.payment_date))
                                .slice(0, 3)
                        "
                        :client="payment.project.client"
                        :project="payment.project"
                        heading="Powiązane płatności"
                        subheading="Lista innych płatności przypisanych do tego samego projektu"
                        button
                        :href="route('payments.create', payment.project.id)"
                    />
                </div>

                <div>
                    <PaymentsTable
                        :payments="latestPayments"
                        :client="payment.project.client"
                        :project="payment.project"
                        heading="Najnowsze płatności"
                        subheading="Sprawdź najświeższe potwierdzone płatności"
                    />
                </div>

                <NotesBlock href="#">

                </NotesBlock>

            </div>
        </div>
    </AppLayout>
</template>
