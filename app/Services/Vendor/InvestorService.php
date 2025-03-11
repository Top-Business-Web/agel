<?php

namespace App\Services\Vendor;

use App\Models\Investor as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;

class InvestorService extends BaseService
{
    protected string $folder = 'vendor/investor';
    protected string $route = 'investors';

    public function __construct(ObjModel $objModel,protected BranchService $branchService)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->editColumn('branch_id', function ($obj) {
                    return $obj->branch->name;
                })
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
                'bladeName' => " ",

                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {
        $branches = $this->branchService->getAll();
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'branches' => $branches,

        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Investor');
        }

        try {
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }

    public function edit($obj)
    {
        $branches = $this->branchService->getAll();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
            'branches' => $branches,
        ]);
    }

    public function update($data, $investor): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Investor');

            if ($investor->image) {
                $this->deleteFile($investor->image);
            }
        }

        try {
            $this->updateData($data, $investor->id);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }

}
