<?php

namespace App\Services\Admin;

//use App\Models\Module;

namespace App\Services\Admin;

use App\Http\Middleware\Custom\vendor;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Category as ObjModel;

//use App\Models\VendorModule;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class CategoryService extends BaseService
{
    protected string $folder = 'admin/category';
    protected string $route = 'admin.categories';

    public function __construct(ObjModel $objModel, protected CityService $cityService, protected Region $region)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->where('parent_id', null);
            return DataTables::of($obj)
//                ->addColumn('action', function ($obj) {
//                    $buttons = '';
//                    if (Auth::guard('admin')->user()->can('update_vendor')) {
//                        $buttons .= '
//                            <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
//                            <i class="fa fa-edit"></i>
//                            </button>
//                       ';
//                    }
//                    if (Auth::guard('admin')->user()->can('delete_vendor')) {
//                        $buttons .= '
//
//                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
//                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
//                            <i class="fas fa-trash"></i>
//                        </button>
//
//
//                    ';
//                    }
//                    return $buttons;
//                })
                ->editcolumn('status', function ($obj) {

                    return $this->statusDatatable($obj);
                })->editColumn('image', function ($obj) {
                    return $this->imageDataTable($obj->image);
                })->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
//                'createRoute' => route($this->route . '.create'),
                'bladeName' => "المكاتب",
                'route' => $this->route,
            ]);
        }
    }




}
