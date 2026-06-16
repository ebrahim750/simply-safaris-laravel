<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    /**
     * @var array<string, string>
     */
    private const AFRICAN_COUNTRIES = [
        'Algeria' => 'DZ',
        'Angola' => 'AO',
        'Benin' => 'BJ',
        'Botswana' => 'BW',
        'Burkina Faso' => 'BF',
        'Burundi' => 'BI',
        'Cameroon' => 'CM',
        'Cape Verde' => 'CV',
        'Central African Republic' => 'CF',
        'Chad' => 'TD',
        'Comoros' => 'KM',
        'Democratic Republic of the Congo' => 'CD',
        'Republic of the Congo' => 'CG',
        'Djibouti' => 'DJ',
        'Egypt' => 'EG',
        'Equatorial Guinea' => 'GQ',
        'Eritrea' => 'ER',
        'Eswatini' => 'SZ',
        'Ethiopia' => 'ET',
        'Gabon' => 'GA',
        'Gambia' => 'GM',
        'Ghana' => 'GH',
        'Guinea' => 'GN',
        'Guinea-Bissau' => 'GW',
        'Ivory Coast' => 'CI',
        'Kenya' => 'KE',
        'Lesotho' => 'LS',
        'Liberia' => 'LR',
        'Libya' => 'LY',
        'Madagascar' => 'MG',
        'Malawi' => 'MW',
        'Mali' => 'ML',
        'Mauritania' => 'MR',
        'Mauritius' => 'MU',
        'Morocco' => 'MA',
        'Mozambique' => 'MZ',
        'Namibia' => 'NA',
        'Niger' => 'NE',
        'Nigeria' => 'NG',
        'Rwanda' => 'RW',
        'São Tomé and Príncipe' => 'ST',
        'Senegal' => 'SN',
        'Seychelles' => 'SC',
        'Sierra Leone' => 'SL',
        'Somalia' => 'SO',
        'South Africa' => 'ZA',
        'South Sudan' => 'SS',
        'Sudan' => 'SD',
        'Tanzania' => 'TZ',
        'Togo' => 'TG',
        'Tunisia' => 'TN',
        'Uganda' => 'UG',
        'Zambia' => 'ZM',
        'Zimbabwe' => 'ZW',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(array_keys(self::AFRICAN_COUNTRIES));

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'country_code' => self::AFRICAN_COUNTRIES[$name],
            'overview' => null,
            'main_image' => null,
            'best_time_to_go' => null,
            'high_season' => null,
            'tourism_link' => null,
        ];
    }
}
