<?php

namespace App\Services\Vendor;

use App\Models\Investor as ObjModel;
use App\Models\VendorBranch;
use App\Services\Admin\StockService;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class InvestorService extends BaseService
{
    protected string $folder = 'vendor/investor';
    protected string $route = 'investors';

    public function __construct(ObjModel                  $objModel,
                                protected BranchService   $branchService,
                                protected CategoryService $categoryService,
                                protected VendorBranch    $vendorBranch,
                                protected StockService    $stockService,
    )
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

                             <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light addStock">
                            <i class="fa fa-plus"></i>
                        </button>
                    ';
                    return $buttons;
                })->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "المستثمرين",

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


        try {
            $data['phone'] = '+966' . $data['phone'];
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

    public function update($data, $id): JsonResponse
    {


        try {
            $data['phone'] = '+966' . $data['phone'];
            $this->updateData($id, $data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }


    public function addStockForm($id)
    {
        $auth = auth('vendor')->user();
        $branches = [];
        if ($auth->parent_id == null) {
            $branches = $this->branchService->model->apply()->whereIn('vendor_id', [$auth->parent_id, $auth->id])->where('name', "!=", 'الفرع الرئيسي')->get();
        } else {
            $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
            $branches = $this->branchService->model->apply()->whereIn('id', $branchIds)->where('name', "!=", 'الفرع الرئيسي')->get();
        }
        return view("{$this->folder}/parts/add_stock", [
            'storeRoute' => route("vendor.investors.storeStock"),
            'investorId' => $id,
            'categories' => $this->categoryService->model->apply()->get(),
            'branches' => $branches,
        ]);

    }

    public function storeStock($data): JsonResponse
    {
        try {
            $data['vendor_id'] = auth('vendor')->user()->parent_id ?? auth('vendor')->user()->id;
            $data = $this->prepareStockData($data);

            $obj = $this->stockService->createData($data);
            $obj->operation()->create([
                'stock_id' => $obj->id,
                'type' => $data['operation_type'],
            ]);

            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }

    private function prepareStockData($data)
    {
        if ($data['operation'] == 1) {
            $data['price'] = ($data['total_price_add'] - ($data['vendor_commission'] + $data['investor_commission'] + $data['sell_diff'])) / $data['quantity'];
            $data['operation_type'] = 1;
        } else {
            $data['operation_type'] = 0;
        }
        unset($data['operation']);
        return $data;
    }

}
