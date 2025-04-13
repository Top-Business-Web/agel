<?php

namespace App\Services\Admin;

//use App\Models\Module;

namespace App\Services\Admin;

use App\Models\vendor;
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

    public function __construct(ObjModel $objModel, protected CityService $cityService, protected Region $region,protected Vendor $vendor)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {

        $query = $this->model->query();

        // Apply filters
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->office_id) {
            $query->where('branch_id', $request->office_id);
        }
        if ($request->ajax()) {
            return DataTables::of($query)
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
                'branches' => Branch::all(),
                'offices' => $this->vendor->where('parent_id', null)->get(),
                'route' => $this->route,
            ]);
        }
    }


}
