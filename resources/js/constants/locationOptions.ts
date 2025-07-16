export const rawLocationOptions = ['local', 'remote', 'international'] as const;

export type Status = (typeof rawLocationOptions)[number];

export const locationOptions: ReadonlyArray<{ value: Status; label: string }> = rawLocationOptions.map((value) => {
    const labels: Record<Status, string> = {
        local: 'Lokalny',
        remote: 'Krajowy',
        international: 'Zagraniczny',
    };
    return { value, label: labels[value] };
});
