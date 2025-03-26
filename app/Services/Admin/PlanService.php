<?php

namespace App\Services\Admin;

use App\Models\Plan as ObjModel;
use App\Models\Plan;
use App\Models\PlanDetail;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class PlanService extends BaseService
{
    protected string $folder = 'admin/plan';
    protected string $route = 'Plans';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    if ($obj->id == 1) {
                        return 'لا يمكن اتخاذ اي أجراء ';}
                    $buttons = '

                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    return $buttons;
                })->editColumn('image', function ($obj) {
                    return $this->imageDataTable($obj->image);
                })->editColumn('status', function ($obj) {
                    return$obj->id == 1 ? 'غير متاح' : $this->statusDatatable($obj);
                })->editColumn('description', function ($obj) {
                    return strlen($obj->description) > 50 ? substr($obj->description, 0, 50) . '...' : $obj->description;
                })->editColumn('discount', function ($obj) {
                    return $obj->discount . '%';
                })->editColumn('period', function ($obj) {
                    if ($obj->period <= 10) {
                        return $obj->period . ' ايام';
                    } else {
                        return $obj->period . ' يوم';
                    }
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
        ]);
    }

    public function store($request)
    {
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->handleFile($request->file('image'), 'Plan');
        }
    
        try {
            // Save main plan
            $plan = Plan::create([
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'period' => $validatedData['period'],
                'discount' => $validatedData['discount'] ?? null,
                'description' => $validatedData['description'] ?? null,
                'image' => $validatedData['image'] ?? null,
            ]);
    
            // Save plan details
            foreach ($validatedData['plans'] as $planDetail) {
                PlanDetail::create([
                    'plan_id' => $plan->id,
                    'key' => $planDetail['key'],
                    'value' => isset($planDetail['is_unlimited']) && $planDetail['is_unlimited'] == 1 ? null : $planDetail['value'],
                    'is_unlimited' => isset($planDetail['is_unlimited']) ? 1 : 0,
                ]);
            }
    
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => "حدث خطأ أثناء الحفظ",
                'error' => $e->getMessage()
            ]);
        }
    }
    


    public function edit($id)
    {
        return view("{$this->folder}/parts/edit", [
            'plan' => $this->getById($id),
            'updateRoute' => route("{$this->route}.update", $id),
        ]);
    }

    public function update($request, $id)
    {
        $data = $request->all();
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json([
                'status' => 404,
                'message' =>"البيانات غير موجودة"
            ]);
        }

        // Handle file upload
        if ($request->hasFile('image')) {
            if ($plan->image) {
                $this->deleteFile($plan->image);
            }
            $data['image'] = $this->handleFile($request->file('image'), 'Plan');
        }

        try {
            // Update Plan
            $plan->update([
                'name' => $data['name'],
                'price' => $data['price'],
                'period' => $data['period'],
                'discount' => $data['discount'] ?? null,
                'description' => $data['description'] ?? null,
                'image' => $data['image'] ?? $plan->image,
            ]);

            // Update Plan Details
            if (isset($data['plans']) && is_array($data['plans'])) {
                // Remove all old plan details first (optional, depends on your requirement)
                $plan->details()->delete();  // This deletes the associated plan details

                foreach ($data['plans'] as $planDetailId => $planDetail) {
                    PlanDetail::create([
                        'plan_id' => $plan->id,
                        'key' => $planDetail['key'] ?? null,
                        'value' => isset($planDetail['is_unlimited']) && $planDetail['is_unlimited'] == 1 ? null : ($planDetail['value'] ?? null),
                        'is_unlimited' => isset($planDetail['is_unlimited']) ? 1 : 0,
                    ]);
                }
            }

            return response()->json([
                'status' => 200,
                'message' => "تمت العمليه بنجاح"

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' =>"حدث خطأ",
                'error' => $e->getMessage()
            ]);

        }
    }
}
