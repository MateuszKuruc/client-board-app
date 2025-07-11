export interface Lead {
    id: number;
    email: string;
    phone: string | null;
    client_id: number | null;
    converted_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Paginated<T> {
    current_page: number;
    data: T[];
    first_page_url: string | null;
    from: number;
    last_page: number;
    last_page_url: string | null;
    links: {
        active: boolean;
        label: string;
        url: string | null;
    }[],
    next_page_url: string |null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

export interface Filters {
    search: string | null;
}
