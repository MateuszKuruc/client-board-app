import { usePage } from '@inertiajs/vue3';

export function useNavigation() {
    const page = usePage();

    const isActive = (item) => {
        const currentComponent = page.component;

        const componentMapping = {
            'clients/Index': '/klienci',
            'clients/Show': '/klienci',
            'clients/Create': '/klienci/dodaj',
            'projects/Index': '/projekty',
            'projects/Show': '/projekty',
            'projects/Create': '/projekty/dodaj',
            'payments/Index': '/platnosci',
            'payments/Show': '/platnosci',
            'payments/Create': '/platnosci/dodaj',
            'expenses/Index': '/koszty',
            'expenses/Show': '/koszty',
            'expenses/Create': '/koszty/dodaj',
            'finances/Index': '/finanse',
            'leads/Index': '/leady',
            'leads/Create': '/leady/dodaj',
        };

        const activePattern = componentMapping[currentComponent];

        if (item.pattern === '/pulpit') {
            return page.url === '/pulpit';
        }

        if (item.pattern === '/projekty/moje') {
            return page.url === '/projekty/moje';
        }

        return activePattern === item.pattern;
    };

    return {
        isActive,
    };
}
