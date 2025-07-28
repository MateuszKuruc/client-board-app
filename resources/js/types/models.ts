export interface Lead {
    id: number;
    email: string;
    phone: string | null;
    client_id: number | null;
    converted_at: string | null;
    created_at: string;
    updated_at: string;
}

export type Source = 'Strona internetowa' | 'Social media' | 'Polecenie' | 'Ads' | 'Grupki' | 'Useme' | 'Inne';

export type Services =
    | 'Web Development'
    | 'Meta Ads'
    | 'Google Ads'
    | 'TikTok Ads'
    | 'Mailing'
    | 'CRM'
    | 'Pełna automatyzacja'
    | 'Webinar'
    | 'Lejek VSL'
    | 'Prowadzenie social media';

export type Location = 'local' | 'remote' | 'international';
export interface Client {
    id: number;
    name: string;
    slug: string;
    email: string;
    phone: string | null;
    nip: string;
    source: Source | null;
    location: Location | null;
    created_at: string;
    updated_at: string;

    projects?: Project[];
}

export interface Payment {
    id: number;
    project_id: number;
    amount: number;
    status: string;
    payment_date: string;
    created_at: string;
    updated_at: string;

    project?: Project;
}

export interface Project {
    id: number;
    name: string;
    client_id: number;
    service_id: number;
    active: boolean;
    price: number;
    type: string;
    start_date: string | null;
    end_date: string | null;
    created_at: string;
    updated_at: string;

    payments?: Payment[];
    service?: Service;
    client?: Client;
    users?: User[];
}

export interface Service {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
}

export interface Expense {
    id: number;
    name: string;
    amount: number;
    type: string;
    payment_date: Date | null;
    is_paid: boolean;
    created_at: string;
    updated_at: string;
}

export interface User {
    id: number;
    name: string;
    email?: string;
}

export interface Tag {
    id: number;
    name: string;
    severity: string;
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
    }[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

export interface Filters {
    search: string | null;
    sort_by: string | null;
    sort_dir: string | null;
}
