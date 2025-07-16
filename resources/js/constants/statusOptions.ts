export const rawStatusOptions = ['paid', 'pending', 'cancelled'] as const;

export type Status = (typeof rawStatusOptions)[number];

export const statusOptions: ReadonlyArray<{ value: Status; label: string }> = rawStatusOptions.map((value) => {
    const labels: Record<Status, string> = {
        paid: 'Opłacona',
        pending: 'Oczekująca',
        cancelled: 'Anulowana',
    };
    return { value, label: labels[value] };
});
