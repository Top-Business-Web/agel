<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityObserver
{
    public function created(Model $model)
    {
        $this->logActivity($model, 'إضافة');
    }

    public function updated(Model $model)
    {
        $this->logActivity($model, 'تحديث');
    }

    public function deleted(Model $model)
    {
        $this->logActivity($model, 'حذف');
    }

    private function logActivity(Model $model, $action)
    {
        $user = Auth::guard('admin')->user() ?? Auth::guard('vendor')->user();
        if (!$user) return;

        $tableName = $this->getTableNameInArabic($model->getTable());
        $entityName = method_exists($model, 'name') ;

        $message = "قام {$user->name} بـ{$action} {$entityName} في جدول {$tableName}.";

        ActivityLog::create([
            'userable_id'   => $user->id,
            'userable_type' => get_class($user),
            'action'        => $message,
            'ip_address'    => request()->ip(),
        ]);
    }



    private function getTableNameInArabic($tableName)
    {
        $tables = [
            'admins' => 'المشرفين',
            'vendors' => 'البائعين',
            'users' => 'المستخدمين',
            'products' => 'المنتجات',
            'orders' => 'الطلبات',
            'categories' => 'التصنيفات',
            'cities' => 'المدن',
            'branches' => 'الفروع',
            'clients' => 'العملاء',
            'stocks' => 'المخزون',
            'regions' => 'المناطق',
            'countries' => 'الدول',
            'plans' => 'الخطط',
            'settings' => 'الإعدادات',
        ];

        return $tables[$tableName] ?? $tableName; // إذا لم يكن موجودًا، استخدم الاسم الأصلي
    }
}
