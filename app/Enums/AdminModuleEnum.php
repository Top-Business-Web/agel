<?php

namespace App\Enums;

enum AdminModuleEnum: string
{
    //----------------------
    // System Admin Modules
    //----------------------

    case ADMIN = 'admin';
    case PLAN = 'plan';
    case PLAN_SUBSCRIPTION = 'plan_subscription';
    case INVESTOR = 'investor';
    case VENDOR = 'vendor';
    case SETTING= 'setting';
    case ACTIVITY_LOG = 'activity_log';
    public function lang(): string
    {
        return match ($this) {
            self::ADMIN => ' المشرفين',
            self::PLAN => ' الخطط',
            self::PLAN_SUBSCRIPTION => ' الاشتراكات',
            self::INVESTOR => ' المستثمرين',
            self::VENDOR => ' المكاتب',
            self::SETTING => 'إعدادات النظام',
            self::ACTIVITY_LOG => 'سجل النظام',
        };
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
