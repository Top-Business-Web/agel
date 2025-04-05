<?php

namespace App\Services\Admin;

use App\Models\PlanSubscription as ObjModel;
use App\Services\BaseService;
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
                ->addColumn('action', function ($obj) {
                    $buttons = '';
                    if (auth('admin')->user()->can('update_plan_subscription')) {
                        $buttons = '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                    }
                    if (auth('admin')->user()->can('delete_plan_subscription')) {
                        $buttons = '
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    }
                    return $buttons;
                })->editColumn('status', function ($obj) {
                    return $this->statusDatatable($obj);
                })->editColumn('vendor_id', function ($obj) {
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


}
