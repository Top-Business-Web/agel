<?php

namespace App\Enums;

enum AdminModuleEnum: string
{
    //----------------------
    // System Admin Modules
    //----------------------

    case ADMIN_MANAGEMENT = 'admin_management';
    case COUNTRY_MANAGEMENT = 'country_management';
    case CITY_MANAGEMENT = 'city_management';
    case PLAN_MANAGEMENT = 'plan_management';
    case PLAN_SUBSCRIPTION_MANAGEMENT = 'plan_subscription_management';
    case INVESTOR_MANAGEMENT = 'investor_management';
    case VENDOR_MANAGEMENT = 'vendor_management';
    case SETTING_MANAGEMENT= 'setting_management';
    case ACTIVITY_LOG_MANAGEMENT = 'activity_log_management';
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

    public function langPermissions()
    {
        return [
            'create_' . $this->value => "أنشاء " . $this->value,
            'read_' . $this->value => "اظهار " . $this->value,
            'update_' . $this->value => " تحديث" . $this->value,
            'delete_' . $this->value => " حذف" . $this->value
        ];

    }
}
