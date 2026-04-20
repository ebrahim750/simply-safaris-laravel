<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_tagline',
        'primary_color',
        'secondary_color',
        'accent_color',
        'logo_path',
        'admin_logo_path_light',
        'admin_logo_path_dark',
        'favicon_path',
    ];
}
