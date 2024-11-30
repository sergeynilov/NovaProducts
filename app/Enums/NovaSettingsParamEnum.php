<?php

namespace App\Enums;

enum NovaSettingsParamEnum: string
{
    // All parameters in NovaSettings
    case USER_ACTIVE_ON_REGISTER = 'user_active_on_register';
    case CONTACT_US_EMAIL = 'contact_us_email';
    case INVOICE_DAYS_BEFORE_EXPIRE = 'invoice_days_before_expire';
    case APP_LANGS = 'app_langs';
    case METRIX_CACHING_IN_MINUTES = 'metrix_caching_in_minutes';
}
