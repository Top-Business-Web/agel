<?php

namespace App\Services\Vendor;

use App\Services\BaseService;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity as ObjModel;
use Yajra\DataTables\DataTables;
use App\Models\Vendor as VendorObj;

class ActivityLogService extends BaseService
{
    protected string $folder = 'vendor/activity_log';
    protected string $route = 'vendor.activity_logs';
    protected VendorObj $vendorObj;

    public function __construct(protected ObjModel $objModel,VendorObj $vendorObj)
    {
        $this->$vendorObj=$vendorObj;
        parent::__construct($objModel);
    }


    public function index($request)
    {
        if ($request->ajax()) {

            $obj = $this->getDataTable()->where('causer_type','App\Models\Vendor');
//            dd($obj->first());
//            dd($this->adminObj->first()->name);
//            dd($this->adminObj->name);
//            dd($this->adminObj->where('name',$obj->causer_id)->first());
            return DataTables::of($obj)
                ->editColumn('description', function ($obj) {
                    return $obj->description;
                })
                ->editColumn('subject_type', function ($obj) {
                    return class_basename($obj->subject_type);
//                    return $obj->subject_type;
                })
                ->editColumn('subject_id', function ($obj) {
                    return $obj->subject_id;
                })
//                ->editColumn('causer_type', function ($obj) {
//                    return class_basename($obj->causer_type);
////                    return Str::match('*',$obj->causer_type);
////                    return $obj->causer_type;
//                })
                ->editColumn('causer_id', function ($obj) {
//                    return $this->adminObj->first()->name ;
//                    return class_basename($obj->subject_type);
                    return $this->adminObj->where('id', $obj->causer_id)->first()->name??"";
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
                // 'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
                'route' => $this->route,
            ]);
        }
    }
}
