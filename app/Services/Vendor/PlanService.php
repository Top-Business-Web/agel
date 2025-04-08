<?php
//
namespace App\Services\Vendor;

use App\Http\Middleware\Custom\vendor;
use App\Models\Plan;
use App\Models\PlanSubscription as ObjModel;
use App\Models\PlanDetail;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PlanService extends BaseService
{
    protected string $folder = 'vendor/plans';
    protected string $route = 'vendor.plans';

    public function __construct(ObjModel $objModel, protected PlanDetail $planDetail, protected Plan $plan)
    {
        parent::__construct($objModel);
    }

    public function index()
    {
        return view($this->folder . '/index', [
            'createRoute' => route($this->route . '.create'),
            'route' => $this->route,
            'plans' => $this->plan->all(),
            'planDetails' => $this->planDetail,
        ]);
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
    public function store($data): \Illuminate\Http\JsonResponse
    {
//        dd($data->all());
        $vendor = auth('vendor')->user();
        $data['vendor_id'] = $vendor->parent_id == null ? $vendor->id : $vendor->parent_id;
        $data['status'] = 0;

        $data['from'] = date('Y-m-d');
        $data['to'] = \Carbon\Carbon::now()->addDays($this->plan->where('id', $data['plan_id'])->value('period'))->format('Y-m-d');
        try {
            if (isset($data['payment_receipt'])) {
                $data['payment_receipt'] = $this->handleFile($data['payment_receipt'], 'Plan_subscriptions');
            }else{
                return response()->json(['status' => 500, 'message' => "يرجى إرفاق إيصال الدفع"]);
            }
            $this->model->create([
                'vendor_id' => $data['vendor_id'],
                'plan_id' => $data['plan_id'],
                'status' => $data['status'],
                'from' => $data['from'],
                'to' => $data['to'],
                'payment_receipt' => $data['payment_receipt'] ,
            ]);
            return response()->json(['status' => 200, 'message' => "تم الإشتراك في الخطة بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }
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
