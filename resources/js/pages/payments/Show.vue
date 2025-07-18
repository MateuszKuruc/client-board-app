<script setup lang="ts">
import ActionButtons from '@/components/ActionButtons.vue';
import EditableField from '@/components/EditableField.vue';
import PageHeadingBasic from '@/components/PageHeadingBasic.vue';
import SectionHeading from '@/components/SectionHeading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import dayjs from '@/plugins/dayjs';
import { Payment } from '@/types/models';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { computed, ref, Ref } from 'vue';

const toast = useToast();

const { payment } = defineProps<{
    payment: Payment;
}>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Klienci',
        href: route('clients.index'),
    },
    {
        title: payment.project.client.name,
        href: route('clients.show', payment.project.client.slug),
    },
    {
        title: 'Projekty',
        href: route('projects.index'),
    },
    {
        title: payment.project.name,
        href: route('projects.show', { client: payment.project.client.slug, project: payment.project.id }),
    },
    {
        title: 'Płatności',
        href: route('payments.index'),
    },
    {
        title: `Płatność ${payment.id}`,
        href: `/klienci/${payment.project.client.slug}/projekty/${payment.project.id}/platnosci/${payment.id}`,
    },
];

const rawStatusOptions = ['paid', 'pending', 'cancelled'] as const;

const statusOptions = computed(() => {
    return rawStatusOptions.map((value) => {
        let label = value;
        if (value === 'paid') label = 'Opłacona';
        else if (value === 'pending') label = 'Oczekująca';
        else if (value === 'cancelled') label = 'Anulowana';
        return { value, label };
    });
});

type editableField =
    | {
          key: string;
          label: string;
          type?: 'text';
      }
    | {
          key: string;
          label: string;
          type: 'select';
          options: string[];
      }
    | {
          key: string;
          label: string;
          type: 'picker';
      };

const isEditing: Ref<boolean> = ref(false);

function submitEdit() {
    form.transform((data) => {
        data.payment_date = dayjs(data.payment_date).format('YYYY-MM-DD');

        return data;
    }).put(route('payments.update', { client: payment.project.client.slug, project: payment.project.id, payment: payment.id }), {
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
    isEditing.value = !isEditing.value;
}

const form = useForm<Payment>({
    amount: payment.amount,
    status: payment.status,
    payment_date: payment.payment_date,
});

const editableFields: editableField[] = [
    { key: 'amount', label: 'Kwota' },
    {
        key: 'status',
        label: 'Status',
        type: 'select',
        options: statusOptions.value,
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
                        />
                    </li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
