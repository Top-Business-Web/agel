<?php

namespace App\Enums;

enum VendorModuleEnum: string
{
    //----------------------
    // System Admin Modules
    //----------------------

    case VENDOR_MANAGEMENT = 'vendor_management';
    case SETTING_MANAGEMENT= 'setting_management';
    case PLANS_MANAGEMENT= 'plans_management';
    case BRANCHES_MANAGEMENT= 'branches_management';
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
