<?php

namespace App\Services\Vendor;


namespace App\Services\Vendor;

use App\Models\Region;
use App\Models\Vendor as ObjModel;

use App\Models\VendorBranch;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class VendorService extends BaseService
{
    protected string $folder = 'vendor/vendor';
    protected string $route = 'vendor.vendors';

    public function __construct(ObjModel $objModel, protected Region $region, protected BranchService $branchService, protected VendorBranch $vendorBranch)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getVendorDateTable();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    if ($obj->parent_id == null) {
                        $buttons = '';
                    } else {

                        $buttons = '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>';
                        $buttons .= '
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>';


                    }


                    return $buttons;
                })->editcolumn('status', function ($obj) {

                    return $this->statusDatatable($obj);
                })->editcolumn('image', function ($obj) {

                    return $this->imageDataTable($obj->image);
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => 'الموظفين',
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        $auth = auth('vendor')->user();
        $branches = [];
        if ($auth->parent_id == null) {
            $branches = $this->branchService->model->whereIn('vendor_id', [$auth->parent_id, $auth->id])->get();
        } else {
            $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
            $branches = $this->branchService->model->whereIn('id', $branchIds)->get();
        }

        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'regions' => $this->region->get(),
            'branches' => $branches,
            'permissions' => Permission::where('guard_name', 'vendor')
                ->get(),
        ]);
    }

    public function store($data): JsonResponse
    {
        $allData = $data;
        unset($data['permissions'], $data['branch_ids']);
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Vendor');
        }

        $data['username'] = $this->generateUsername($data['name']);
        $data['phone'] = '+966' . $data['phone'];
        if (isset(auth()->user()->parent_id)) {
            $data['parent_id'] = auth()->user()->parent_id;
        } else {
            $data['parent_id'] = auth()->user()->id;
        }

        $data['password'] = Hash::make($data['password']);


        try {
        $permissions = Permission::whereIn('id', $allData['permissions'])->pluck('name')->toArray();
        $obj = $this->model->create($data);
        $obj->syncPermissions($permissions);

        //create vendor branch
        foreach ($allData['branch_ids'] as $branch_id) {
            $this->vendorBranch->create([
                'vendor_id' => $obj->id,
                'branch_id' => $branch_id,
            ]);

        }


            return $this->responseMsg();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "حدث خطأ",
                'error' => $e->getMessage()
            ]);

        }
    }


    public function edit($id)
    {
        $obj = $this->getById($id);
        $auth = auth('vendor')->user();
        $branches = [];
        if ($auth->parent_id == null) {
            $branches = $this->branchService->model->whereIn('vendor_id', [$auth->parent_id, $auth->id])->get();
        } else {
            $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
            $branches = $this->branchService->model->whereIn('id', $branchIds)->get();
        }
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $id),
            'regions' => $this->region->get(),
            'branches' => $branches,

            'permissions' => Permission::where('guard_name', 'vendor')
                ->get(),
        ]);
    }

public function update($data): JsonResponse
{
    try {
        $allData = $data;
        unset($data['permissions'], $data['branch_ids']);
        $oldObj = $this->getById($data['id']);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Vendor');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        $data['phone'] = '+966' . $data['phone'];
        if (isset(auth()->user()->parent_id)) {
            $data['parent_id'] = auth()->user()->parent_id;
        } else {
            $data['parent_id'] = auth()->user()->id;
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }else{
            unset($data['password']);
        }

        // Update model and get the instance
        $obj = $oldObj;
        $obj->update($data);

        // Sync permissions if provided
        if (isset($allData['permissions'])) {
            $permissions = Permission::whereIn('id', $allData['permissions'])->pluck('name')->toArray();
            $obj->syncPermissions($permissions);
        }

        // Delete existing branch relations and create new ones
        $this->vendorBranch->where('vendor_id', $obj->id)->delete();
        foreach ($allData['branch_ids'] as $branch_id) {
            $this->vendorBranch->create([
                'vendor_id' => $obj->id,
                'branch_id' => $branch_id,
            ]);
        }

        return $this->responseMsg();
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => "حدث خطأ",
            'error' => $e->getMessage()
        ]);
    }
}

    public function getVendorsByModule($moduleId)
    {
        return \App\Models\Vendor::whereHas('vendor_modules', function ($query) use ($moduleId) {
            $query->where('module_id', $moduleId);
        })->get();
    }
}
