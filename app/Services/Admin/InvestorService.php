<?php

namespace App\Services\Admin;

//use App\Models\Module;

namespace App\Services\Admin;

use App\Models\Investor;
use App\Models\vendor;
use App\Models\Branch;
use App\Models\Region;

use App\Models\Investor as ObjModel;

use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

use Yajra\DataTables\DataTables;

class InvestorService extends BaseService
{
    protected string $folder = 'admin/investor';
    protected string $route = 'admin.investors';

    public function __construct(ObjModel $objModel, protected Vendor $vendor,protected Branch $branch)
    {
        parent::__construct($objModel);
    }

    public function index(Request $request)
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
//                    return $obj->branch ? $obj->branch()->name : "غير مرتبط بفرع";
                })
                ->addColumn('office', function ($obj) {
//                    return $obj->office() ? $obj->office()->name : "غير مرتبط بمكتب";
                })
                ->addColumn('name', function ($obj) {
                    return $obj->name;

//                    return $obj->branch?($obj->branch->vendor->parent_id==null? $obj->branch->vendor->name:$obj->branch->vendor->parent->name):"غير مرتبط بمكتب";
                })

                ->addColumn('vendor', function ($obj) {
                    return $obj->vendor() ? $obj->vendor()->name : "غير مرتبط بموظف";
                })
                ->addColumn('national_id', function ($obj) {
                    return $obj->national_id;
                })
                ->addColumn('phone', function ($obj) {
                    return $obj->phone;

                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        }

        return view($this->folder . '/index', [
            'bladeName' => "المستثمرين",
            'branches' => Branch::all(),
            'offices' => $this->vendor->where('parent_id', null)->get(),
            'route' => $this->route,
        ]);
    }

}
