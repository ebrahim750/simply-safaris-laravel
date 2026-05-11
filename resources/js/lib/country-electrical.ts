import electricalData from '@/data/country-electrical-data.json';

const data: Record<string, string> = electricalData;

export function getElectricalStandardByCountryCode(countryCode: string): string | null {
    const code = countryCode.toLowerCase();

    return data[code] ?? null;
}

export function getElectricalStandardByCountryName(
    countryName: string,
    countryOptions: Array<{ code: string; name: string }>,
): string | null {
    const match = countryOptions.find(
        (option) => option.name.toLowerCase() === countryName.toLowerCase(),
    );

    if (!match) {
        return null;
    }

    return getElectricalStandardByCountryCode(match.code);
}
