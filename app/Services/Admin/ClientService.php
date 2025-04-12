<?php

namespace App\Services\Admin;

//use App\Models\Module;

namespace App\Services\Admin;

use App\Http\Middleware\Custom\vendor;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Client as ObjModel;

//use App\Models\VendorModule;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class ClientService extends BaseService
{
    protected string $folder = 'admin/client';
    protected string $route = 'admin.clients';

    public function __construct(ObjModel $objModel, protected CityService $cityService, protected Region $region)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->all();
            return DataTables::of($obj)
                ->addColumn('branch', function ($obj) {

                    return  $obj->branch?$obj->branch->name:"غير مرتبط بفرع";
                })
                ->addColumn('office', function ($obj) {
                    if ($obj->vendor()==null) {
                        return "غير مرتبط بمكتب";
                    }
                    return $obj->office()?$obj->office()->name:"غير مرتبط بمكتب";
                })
                ->addColumn('name', function ($obj) {
                    return  $obj->name;
                })
                ->addColumn('vendor', function ($obj) {
                    return  $obj->vendor()?$obj->vendor()->name:"غير مرتبط بموظف";
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'bladeName' => "العملاء",
                'route' => $this->route,
            ]);
        }
    }


}
