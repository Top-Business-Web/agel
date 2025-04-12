<?php

namespace App\Services\Vendor;

use App\Models\Investor;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;
use App\Models\Stock as ObjModel;
use Illuminate\Http\JsonResponse;
use App\Services\Vendor\BranchService;
use App\Services\Admin\OperationService;
use App\Services\Vendor\CategoryService;
use App\Services\Vendor\InvestorService;

class StockService extends BaseService
{
    protected string $folder = 'vendor/stock';
    protected string $route = 'stocks';

    public function __construct(
        ObjModel                  $objModel,
        protected CategoryService $categoryService,
        protected BranchService   $branchService,
        protected VendorBranch    $vendorBranch,
        protected Investor        $investor,
        protected OperationService $operationService

    ) {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $user = auth('vendor')->user();
            $parentId = $user->parent_id ?? $user->id;
            $obj = $this->categoryService->model->where('vendor_id', $parentId)->whereHas('stocks')->get();

            return DataTables::of($obj)
                ->editColumn('stocks', function ($obj) {


                    $add= $obj->stocks->flatMap(function ($stock) {
                        return $stock->operations->where('type', 1);
                    })->sum('stock.quantity');

                    $remove= $obj->stocks->flatMap(function ($stock) {
                        return $stock->operations->where('type', 0);
                    })->sum('stock.quantity');
                    $total = $add - $remove;
                    return $total;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'route' => $this->route,
            ]);
        }
    }

    public function create()
    {

        $auth = auth('vendor')->user();
        $branches = [];
        if ($auth->parent_id == null) {
            $branches = $this->branchService->model->apply()->whereIn('vendor_id', [$auth->parent_id, $auth->id])->where('name', "!=", 'الفرع الرئيسي')->get();
        } else {
            $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
            $branches = $this->branchService->model->apply()->whereIn('id', $branchIds)->where('name', "!=", 'الفرع الرئيسي')->get();
        }
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
            'investors' => $this->investor->whereIn('branch_id', $branches->pluck('id'))->get(),
            'categories' => $this->categoryService->model->where('vendor_id', $auth->parent_id ?? $auth->id)->get(),
            'branches' => $branches,
        ]);
    }

    public function store($data): JsonResponse
    {
        try {
            $data['vendor_id'] = auth('vendor')->user()->parent_id ?? auth('vendor')->user()->id;
            $data = $this->prepareStockData($data);
            $stockData = $data;
            unset($stockData['operation_type']);

            $obj = $this->createData($stockData);
            $this->operationService->model->create([
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
            $data['image'] = $this->handleFile($data['image'], 'Stock');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        try {
            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
        }
    }
}
