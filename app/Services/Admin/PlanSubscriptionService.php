<?php

namespace App\Services\Admin;

use App\Models\PlanSubscription as ObjModel;
use App\Services\BaseService;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class PlanSubscriptionService extends BaseService
{
    protected string $folder = 'admin/planSubscription';
    protected string $route = 'planSubscription';

    public function __construct(ObjModel $objModel, protected PlanService $planService, protected VendorService $vendorService)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('start_date', function ($obj) {
                    return $obj->start_date;
                })->editColumn('end_date', function ($obj) {
                    return $obj->end_date;
                })
                ->addColumn('action', function ($obj) {
                    $buttons = '';

                    if ($obj->plan_id != 1 && $obj->status == 0) {
                        $buttons .= '
                        <button id="activateBtn" type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-success-light activateBtn" data-bs-toggle="modal" data-bs-target="#acceptActivateModal" data-id="' . $obj->id . '" data-title="' . $obj->name . '" data-vendor-name="' . $obj->getVendorNameAttribute() . '">
                            <i class="fa fa-check"></i> تفعيل
                        </button>';
                        $buttons .= '
                        <button id="rejectBtn" type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-danger-light deactivateBtn" data-bs-toggle="modal" data-bs-target="#rejectActivateModal" data-id="' . $obj->id . '" data-title="' . $obj->name . '" data-vendor-name="' . $obj->getVendorNameAttribute() . '">
                            <i class="fa fa-times"></i> رفض
                        </button>';
                    } else {
                        $buttonClass = 'btn btn-lg btn-pill text-center w-70';

                        if ($obj->status == 2) {
                            return '<button class="' . $buttonClass . ' btn-danger" disabled>مرفوض</button>';
                        }
                        $remainingDays = Carbon::now()->diffInDays($obj->to)+1;

                        if ($remainingDays <= 0) {
                            return '<button class="' . $buttonClass . ' btn-danger px-4 py-2" disabled>
                                <span class="fw-bold">منتهي</span>
                            </button>';
                        }
                        return '<button class="' . $buttonClass . ' btn-success px-1 py-2 d-flex align-items-center gap-3" disabled>
                    <span class="fw-bold mr-9">مفعل</span>
                    <span class="badge bg-white text-danger border border-danger" style="
                        font-size: 0.9rem;
                        padding: 8px 12px;
                        border-radius: 50px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                        font-weight: 600;
                        letter-spacing: 0.5px;
                    ">
                        <i class="fas fa-clock me-2"></i>
                        ' . $remainingDays . ' يوم متبقي
                    </span>
                </button>';

                    }

                    return $buttons;
                })
                ->editColumn('vendor_id', function ($obj) {
                    return $obj->vendor->name;
                })->editColumn('plan_id', function ($obj) {
                    return $obj->plan->name;
                })->editColumn('payment_receipt', function ($obj) {
                    return $this->imageDataTable($obj->payment_receipt);
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "الإشتراكات",
                'planSubscription' => $this->model->all(),
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'plans' => $this->planService->model->where('id', '!=', 1)->get(),
            'vendors' => $this->vendorService->model->where('parent_id', null)->get(),
        ]);

    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['payment_receipt'])) {
            $data['payment_receipt'] = $this->handleFile($data['payment_receipt'], 'PlanSubscription');
        }

        $existingSubscription = $this->model->where('vendor_id', $data['vendor_id'])->where('plan_id', $data['plan_id'])->first();  // Check if a subscription already exists for the same vendor and plan

        if ($existingSubscription) {
            return response()->json(['status' => 250, 'message' => "هذا الاشتراك موجود بالفعل"]);
        }
//        هذا المستخدم قدم طلب على خطه معينه الرجاء تحديث حالتها أولا

        try {
            $data['status'] = 1;
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }

    public function edit($obj)
    {
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'plans' => $this->planService->model->where('id', '!=', 1)->get(),
            'vendors' => $this->vendorService->model->where('parent_id', null)->get(),
            'updateRoute' => route("{$this->route}.update", $obj->id),
        ]);
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'PlanSubscription');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        try {
            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }

    public function rejectSubscription($id): \Illuminate\Http\JsonResponse
    {
        try {

            $this->model->where('id', $id)->update(['status' => 2]);
            return response()->json(['status' => 200, 'message' => "تم رفض الإشتراك"]);

        } catch (\Exception $e) {
            return $this->responseMsgError();
        }
    }

    public function activateSubscription($id): \Illuminate\Http\JsonResponse
    {

        try {
            $planSubscription = $this->model->where('id', $id)->first();
            if (!$this->model->where('vendor_id', $planSubscription->vendor_id)->where('status', 1)->exists()) {
                $planSubscription->update([
                    'status' => 1,
                    'from' => now(),
                    'to' => now()->addDays($planSubscription->plan->period),
                ]);
                $this->vendorService->model->where('id', $planSubscription->vendor_id)->update(['plan_id' => $planSubscription->plan_id]);
                return response()->json(['status' => 200, 'message' => ""]);
            } else {
                return response()->json(['status' => 405, 'message' => "هذا المستخدم مشترك بالفعل في خطه"]);
            }
        } catch (\Exception $e) {
            return $this->responseMsgError();
        }
    }


}
