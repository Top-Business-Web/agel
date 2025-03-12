<?php

namespace App\Services\Admin;

//use App\Models\Module;

namespace App\Services\Admin;

use App\Http\Middleware\Custom\vendor;
use App\Models\Vendor as ObjModel;

//use App\Models\VendorModule;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class VendorService extends BaseService
{
    protected string $folder = 'admin/vendor';
    protected string $route = 'admin.vendors';

    public function __construct(ObjModel $objModel, protected CityService $cityService)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
//                ->addColumn('action', function ($obj) {
//                    $buttons = '
//
//                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
//                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
//                            <i class="fas fa-trash"></i>
//                        </button>
//
//
//                    ';
//                    return $buttons;
//                })
        ->editcolumn('status', function ($obj) {

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
                'bladeName' => " ",

                'route' => $this->route,
            ]);
        }
    }

//    public function create()
//    {
//        return view("{$this->folder}/parts/create", [
//            'storeRoute' => route("{$this->route}.store"),
//            'cities' => $this->cityService->getAll(),
//            'vendors' => $this->model->all(),
//
//
//        ]);
//    }
//
//    public function store($data): \Illuminate\Http\JsonResponse
//    {
//        if (isset($data['image'])) {
//            $data['image'] = $this->handleFile($data['image'], 'Vendor');
//        }
//
//        $data['username'] = $this->generateUsername($data['name']);
//        if ($data['has_parent'] == 0) {
//            $data['parent_id'] = null;
//        }
//        $data['password'] = Hash::make($data['password']);
//        try {
//            $vendor = $this->model->create($data->except('has_parent'));
//
//            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
//        } catch (\Exception $e) {
//            return response()->json([
//                'status' => 500,
//                'message' =>"حدث خطأ",
//                'error' => $e->getMessage()
//            ]);
//
//        }
//    }
//
//    public function show($id)
//    {
//
//    }
//
//    public function edit($obj)
//    {
//        return view("{$this->folder}/parts/edit", [
//            'obj' => $obj,
//            'updateRoute' => route("{$this->route}.update", $obj->id),
//            'cities' => $this->cityService->getAll(),
//            'vendors' => $this->model->all(),
//
////            'vendorModules' => $obj->vendor_modules->pluck('module_id')->toArray(),
////            'moduleService' => $this->moduleService->getAll(),
//        ]);
//    }
//
//    public function update($data, $id)
//    {
//        $oldObj = $this->getById($id);
//
//        if (isset($data['image'])) {
//            $data['image'] = $this->handleFile($data['image'], 'Vendor');
//
//            if ($oldObj->image) {
//                $this->deleteFile($oldObj->image);
//            }
//        }
//
//        if (isset($data['password']) && $data['password'] != null) {
//            $data['password'] = Hash::make($data['password']);
//        } else {
//            unset($data['password']);
//        }
//
//        // Remove module_id from data to avoid updating non-existent column
//        $moduleIds = $data['module_id'];
//        unset($data['module_id']);
//
//        // Update vendor_modules
//        $oldObj->vendor_modules()->delete();
//        foreach ($moduleIds as $module_id) {
//            $oldObj->vendor_modules()->create([
//                'vendor_id' => $oldObj->id,
//                'module_id' => $module_id,
//            ]);
//        }
//
//        try {
//            $oldObj->update($data);
//            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
//
//        } catch (\Exception $e) {
//            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
//        }
//    }


}
