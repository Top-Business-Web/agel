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

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Plan');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        try {
            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => trns('Data updated successfully.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => trns('Something went wrong.'), trns('error') => $e->getMessage()]);
        }
    }
}
