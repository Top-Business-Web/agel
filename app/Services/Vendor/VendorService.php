<?php

namespace App\Services\Vendor;


namespace App\Services\Vendor;

use App\Models\Region;
use App\Models\Vendor as ObjModel;

use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class VendorService extends BaseService
{
    protected string $folder = 'vendor/vendor';
    protected string $route = 'vendor.vendors';

    public function __construct(ObjModel $objModel, protected Region $region)
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
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'regions' => $this->region->get(),
            'permissions' => Permission::where('guard_name', 'vendor')
                ->get(),
        ]);
    }

    public function store($data): JsonResponse
    {
        $allData = $data;
        unset($data['permissions']);
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


            return $this->responseMsg();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "حدث خطأ",
                'error' => $e->getMessage()
            ]);

        }
    }


    public function edit($obj)
    {
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'regions' => $this->region->get(),
            'permissions' => Permission::where('guard_name', 'vendor')
                ->get(),
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Vendor');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        if (isset($data['password']) && $data['password'] != null) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Remove module_id from data to avoid updating non-existent column
//        $moduleIds = $data['module_id'];
//        unset($data['module_id']);

        // Update vendor_modules
//        $oldObj->vendor_modules()->delete();
//        foreach ($moduleIds as $module_id) {
//            $oldObj->vendor_modules()->create([
//                'vendor_id' => $oldObj->id,
////                'module_id' => $module_id,
//            ]);
//        }

        try {
            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }

    public function getVendorsByModule($moduleId)
    {
        return \App\Models\Vendor::whereHas('vendor_modules', function ($query) use ($moduleId) {
            $query->where('module_id', $moduleId);
        })->get();
    }
}
