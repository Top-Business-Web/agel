<?php

namespace App\Enums;

enum ModuleEnum: string
{
    //----------------------
    // System Admin Modules
    //----------------------

    case ADMIN_MANAGEMENT = 'subscription_management';
    case COUNTRY_MANAGEMENT = 'country_management';
    case CITY_MANAGEMENT = 'city_management';
    case INVESTOR_MANAGEMENT = 'investor_management';
    case VENDOR_MANAGEMENT = 'vendor_management';
    case SETTING_MANAGEMENT= 'setting_management';
    public function lang(): string
{
    return trns($this->value);
}

    public function permissions(): array
    {
        return [
            'create_' . $this->value,
            'read_' . $this->value,
            'update_' . $this->value,
            'delete_' . $this->value
        ];
    }
}
