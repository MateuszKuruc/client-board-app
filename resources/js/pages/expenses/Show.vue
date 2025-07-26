<script setup lang="ts">
import ActionButtons from '@/components/ActionButtons.vue';
import EditableField from '@/components/EditableField.vue';
import ExpensesTable from '@/components/ExpensesTable.vue';
import PageHeadingBasic from '@/components/PageHeadingBasic.vue';
import SectionHeading from '@/components/SectionHeading.vue';
import { expenseTypeOptions } from '@/constants/expenseTypeOptions';
import AppLayout from '@/layouts/AppLayout.vue';
import NotesBlock from '@/components/NotesBlock.vue';
import dayjs from '@/plugins/dayjs';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { ref, Ref } from 'vue';

const toast = useToast();

const props = defineProps<{
    expense: Expense;
    latestExpenses: Expense[];
    biggestExpenses: Expense[];
}>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Koszty',
        href: route('expenses.index'),
    },

    {
        title: `${props.expense.name} (${props.expense.payment_date})`,
        href: '/koszty',
    },
];

const statusOptions: string[] = ['Opłacona', 'Nieopłacona'];

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

const paidToString = (is_paid: boolean): string => (is_paid ? 'Opłacona' : 'Nieopłacona');
const stringToPaid = (label: string): boolean => label === 'Opłacona';

const isEditing: Ref<boolean> = ref(false);

function submitEdit() {
    form.transform((data) => {
        data.is_paid = stringToPaid(data.is_paid_status); // <-- map back to boolean
        delete data.is_paid_status;

        data.payment_date = data.payment_date ? dayjs(data.payment_date).format('YYYY-MM-DD') : null;

        return data;
    }).put(route('expenses.update', { expense: props.expense.id }), {
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

const form = useForm<Expense>({
    name: props.expense.name,
    amount: props.expense.amount,
    is_paid: props.expense.is_paid,
    is_paid_status: paidToString(props.expense.is_paid),
    type: props.expense.type,
    payment_date: props.expense.payment_date,
});

const editableFields: EditableField[] = [
    { key: 'name', label: 'Nazwa' },
    { key: 'amount', label: 'Kwota' },
    { key: 'is_paid_status', label: 'Status płatności', type: 'select', options: statusOptions },
    {
        key: 'type',
        label: 'Rodzaj płatności',
        type: 'select',
        options: expenseTypeOptions,
    },
    { key: 'payment_date', label: 'Data płatności', type: 'picker' },
] as const;
</script>

<template>
    <Head :title="breadcrumbs[1].title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
            <div class="flex flex-col gap-4">
                <PageHeadingBasic :title="'Koszt nr ' + expense.id" subtitle="test" />
                <!--                <TagSection />-->
            </div>

            <div class="flex items-center justify-between">
                <SectionHeading heading="Informacje o koszcie" subheading="Szczegółowe dane płatności" />
                <ActionButtons :isEditing="isEditing" @cancel="cancelEdit" @save="submitEdit" @edit="startEdit" buttonLabel="Edytuj koszt" />
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
                    <ExpensesTable
                        :expenses="latestExpenses"
                        heading="Ostatnie koszty"
                        subheading="Sprawdź 3 ostatnio dodane koszty"
                        button
                        :href="route('expenses.create')"
                    />
                </div>

                <div>
                    <ExpensesTable :expenses="biggestExpenses" heading="Największe wydatki" subheading="Lista najwyższych wydatków w historii" />
                </div>
            </div>

            <NotesBlock subheading="" href="#" />
        </div>
    </AppLayout>
</template>

<style scoped></style>
