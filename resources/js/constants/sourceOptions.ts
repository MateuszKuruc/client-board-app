export type Source =
    | 'Strona internetowa'
    | 'Social media'
    | 'Polecenie'
    | 'Ads'
    | 'Grupki'
    | 'Useme'
    | 'Inne'

export const sourceOptions: Source[] = [
    'Strona internetowa',
    'Social media',
    'Polecenie',
    'Ads',
    'Grupki',
    'Useme',
    'Inne',
] as const;
