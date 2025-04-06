<?php
//
namespace App\Services\Vendor;

use App\Models\Plan as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PlanService extends BaseService
{
    protected string $folder = 'vendor/client';
    protected string $route = 'plans';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }
//
   public function index($request)
   {

    return view('vendor.plans.index');
       return 2222;
   }
//
//    public function create()
//    {
//        $auth = auth('vendor')->user();
//        $branches = [];
//        if ($auth->parent_id == null) {
//            $branches = $this->branchService->model->whereIn('vendor_id', [$auth->parent_id, $auth->id])
//                ->where('name', '!=', 'الفرع الرئيسي')
//                ->where('is_main', '!=', 1)
//                ->get();
//        } else {
//            $branches = $this->branchService->model->whereIn('id', $branchIds)
//                ->where('name', '!=', 'الفرع الرئيسي')
//                ->where('is_main', '!=', 1)
//                ->get();
//        }
//        return view("{$this->folder}/parts/create", [
//            'storeRoute' => route("{$this->route}.store"),
//            'branches' => $branches,
//        ]);
//    }
//
//    public function store($data): \Illuminate\Http\JsonResponse
//    {
//
//        try {
//            $data['phone'] = '+966' . $data['phone'];
//            $this->createData($data);
//            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
//        } catch (\Exception $e) {
//            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
//        }
//    }
//
//    public function edit($obj)
//    {
//
//        $auth = auth('vendor')->user();
//        $branches = [];
//        if ($auth->parent_id == null) {
//            $branches = $this->branchService->model->whereIn('vendor_id', [$auth->parent_id, $auth->id])
//                ->where('name', '!=', 'الفرع الرئيسي')
//                ->where('is_main', '!=', 1)
//                ->get();
//        } else {
//            $branches = $this->branchService->model->whereIn('id', $branchIds)
//                ->where('name', '!=', 'الفرع الرئيسي')
//                ->where('is_main', '!=', 1)
//                ->get();
//        }
//        return view("{$this->folder}/parts/edit", [
//            'obj' => $obj,
//            'updateRoute' => route("{$this->route}.update", $obj->id),
//            'branches' => $branches,
//        ]);
//    }
//
//    public function update($data, $id)
//    {
//        $oldObj = $this->getById($id);
//
//        if (isset($data['image'])) {
//            $data['image'] = $this->handleFile($data['image'], 'Client');
//
//            if ($oldObj->image) {
//                $this->deleteFile($oldObj->image);
//            }
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
