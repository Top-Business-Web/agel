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
                    return $this->statusDatatable($obj);
                })->editColumn('description', function ($obj) {
                    return strlen($obj->description) > 50 ? substr($obj->description, 0, 50) . '...' : $obj->description;
                })->editColumn('discount', function ($obj) {
                    return $obj->discount . '%';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => trns($this->route),
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
        $data = $request->all();


        if ($request->hasFile('image')) {
            $data['image'] = $this->handleFile($request->file('image'), 'Plan');
        }

        try {

            $plan = Plan::create([
                'name' => $data['name'],
                'price' => $data['price'],
                'period' => $data['period'],
                'discount' => $data['discount'] ?? null,
                'description' => $data['description'] ?? null,
                'image' => $data['image'] ?? null,
            ]);


            if (isset($data['plans']) && is_array($data['plans'])) {
                foreach ($data['plans'] as $planDetail) {
                    PlanDetail::create([
                        'plan_id' => $plan->id,
                        'key' => $planDetail['key'] ?? null,
                        'value' => isset($planDetail['is_unlimited']) && $planDetail['is_unlimited'] == 1 ? null : ($planDetail['value'] ?? null),
                        'is_unlimited' => isset($planDetail['is_unlimited']) ? 1 : 0,
                    ]);
                }
            }

            return response()->json(['status' => 200, 'message' => trns('Data created successfully.')]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => trns('Something went wrong.'),
                'error' => $e->getMessage()
            ]);
        }
    }


    public function edit($obj)
    {
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
        ]);
    }

    public function update($request, $id)
    {
        $data = $request->all();
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json([
                'status' => 404,
                'message' => trns('Plan not found.')
            ]);
        }

        // Handle file upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($plan->image) {
                $this->deleteFile($plan->image);  // Define the deleteFile method to handle image deletion
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
                'message' => trns('Data updated successfully.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => trns('Something went wrong.'),
                'error' => $e->getMessage()
            ]);
        }
    }
}
