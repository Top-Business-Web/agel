<?php
//
namespace App\Services\Vendor;

use App\Http\Middleware\Custom\vendor;
use App\Models\Plan;
use App\Models\PlanSubscription as ObjModel;
use App\Models\PlanDetail;
use App\Models\Setting;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PlanService extends BaseService
{
    protected string $folder = 'vendor/plans';
    protected string $route = 'vendor.plans';

    public function __construct(ObjModel $objModel, protected PlanDetail $planDetail, protected Plan $plan,protected Setting $setting)
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
            'planSubscription' => $this->model->where('status',1)->where('vendor_id', auth('vendor')->user()->parent_id == null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id)->where('plan_id','!=',1)->first(),
            'phones'=>$this->setting->where('key', 'like', 'phone%')->get(),
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
        $vendor = auth('vendor')->user();
        $data['vendor_id'] = $vendor->parent_id == null ? $vendor->id : $vendor->parent_id;
        if ($vendor->plan_id != null) {
            $oldPlan = $this->model->where('vendor_id', $data['vendor_id'])->where('plan_id','!=',1)->where('status', 1)->first();
            $oldSubcriptionApplication= $this->model->where('vendor_id', $data['vendor_id'])->where('plan_id',$data['plan_id'])->where('status', 0)->first();
            if ($oldPlan) {
//                $oldPlan->update(['status' => 0]);
                return $this->responseMsg(['status' => 500, 'message' => "لا يمكن الإشتراك في خطة جديدة قبل إنهاء الخطة السابقة"]);
            }
            if ($oldSubcriptionApplication) {
                return $this->responseMsg(['status' => 500, 'message' => "لقذ قمت بالفعل بتقديم طلب اشتراك في هذه الخطه يرجى الانتظار حتى يتم قبول الطلب من قبل الإدارة"]);
            }
        }
//        dd($data->all());
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

            // $vendor['plan_id'] = $data['plan_id'];
            // $vendor->save();   it will be pending until accept or reject
            return response()->json(['status' => 200, 'message' => "تم تقديم الطلب بنجاح"]);
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
