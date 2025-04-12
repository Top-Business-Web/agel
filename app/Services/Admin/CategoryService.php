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

    public function __construct(ObjModel $objModel,protected Vendor $vendor)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->where('parent_id', null);
            return DataTables::of($obj)

                ->editcolumn('status', function ($obj) {

                    return $obj->status;
                })
                ->addColumn('name', function ($obj) {
                    return $obj->name;
                })
                ->addColumn('office', function ($obj) {
                    return $this->vendor->where('id',$obj->office())->first()->name ?? "غير مرتبط بمكتب";
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
//                'createRoute' => route($this->route . '.create'),
                'bladeName' => "الأصناف",
                'route' => $this->route,
            ]);
        }
    }




}
