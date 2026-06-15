<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected function casts(): array
    {
        return [
            'form_emails_enabled' => 'boolean',
        ];
    }

    protected $fillable = [
        'site_name',
        'site_tagline',
        'currency',
        'primary_color',
        'secondary_color',
        'accent_color',
        'logo_path',
        'admin_logo_path_light',
        'admin_logo_path_dark',
        'favicon_path',
        'form_emails',
        'form_emails_enabled',
    ];
}
