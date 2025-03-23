<?php

namespace App\Services\Vendor;

use App\Models\Admin;
use App\Services\BaseService;
use Spatie\Activitylog\Models\Activity as ObjModel;
use Yajra\DataTables\DataTables;
use App\Models\Vendor as VendorObj;

class ActivityLogService extends BaseService
{
    protected string $folder = 'vendor/activity_log';
    protected string $route = 'vendor.activity_logs';

    public function __construct(protected ObjModel $objModel,protected VendorObj $vendorObj,protected Admin $adminObj)
    {
        parent::__construct($objModel);
    }


    public function index($request)
    {
        if ($request->ajax()) {

            $obj = $this->getDataTable()->where('causer_type','App\Models\Vendor');
            return DataTables::of($obj)
                ->editColumn('description', function ($obj) {
                    return $obj->description;
                })
                ->editColumn('subject_type', function ($obj) {
                    return class_basename($obj->subject_type);
                })
                ->editColumn('subject_id', function ($obj) {
                    return $obj->subject_id;
                })

                ->editColumn('causer_id', function ($obj) {

                    return $this->vendorObj->where('id', $obj->causer_id)->first()->name??"";
                })
                ->addColumn('action', function ($obj) {
                    $buttons = '
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    return $buttons;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'bladeName' => "المكاتب",

                'route' => $this->route,
            ]);
        }
    }
}
