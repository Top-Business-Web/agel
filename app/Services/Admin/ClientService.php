<?php


namespace App\Services\Admin;

use App\Models\vendor;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Client as ObjModel;

use App\Services\BaseService;

use Yajra\DataTables\DataTables;

class ClientService extends BaseService
{
    protected string $folder = 'admin/client';
    protected string $route = 'admin.clients';

    public function __construct(ObjModel $objModel, protected CityService $cityService, protected Branch $branch, protected Region $region, protected Vendor $vendor)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        $query = $this->model->query();

// Apply filters
        if ($request->office_id) {
            // First get the branch IDs for this office
            if ($request->office_id == 'all') {
                $branchIds = $this->branch->pluck('id');
            } else {
                $branchIds = $this->branch->where('vendor_id', $request->office_id)->pluck('id');
            }

            if ($request->branch_id!='all') {
                $query->where('branch_id', $request->branch_id)
                    ->whereIn('branch_id', $branchIds);
            } else {
                // Otherwise, just filter by all branches of this office
                $query->whereIn('branch_id', $branchIds);
            }
        } elseif ($request->branch_id) {
            // If only branch_id is specified (no office_id)
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('branch', function ($obj) {
                    return $obj->branch ? $obj->branch->name : "غير مرتبط بفرع";
                })
                ->addColumn('office', function ($obj) {
                    return $obj->branch->vendor->parent_id != null
                        ? $obj->branch->vendor->parent->name
                        : $obj->branch->vendor->name;
                })
                ->addColumn('name', function ($obj) {
                    return $obj->name;
                })
                ->addColumn('vendor', function ($obj) {
                    return $obj->vendor() ? $obj->vendor()->name : "غير مرتبط بموظف";
                })
                ->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'bladeName' => "العملاء",
                'branches' => $this->branch->whereIn('id', $this->model->pluck('branch_id')->unique())->get(),
                'offices' => $this->vendor->where('parent_id', null)->get(),
                'route' => $this->route,
            ]);
        }
    }
}
