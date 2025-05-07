<?php

namespace App\Services\Vendor;

use App\Models\Investor as ObjModel;
use App\Models\StockDetail;
use App\Models\VendorBranch;
use App\Services\Vendor\OperationService;
use App\Services\Vendor\StockService;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class InvestorService extends BaseService
{
    protected string $folder = 'vendor/investor';
    protected string $route = 'investors';

    public function __construct(
        ObjModel                   $objModel,
        protected BranchService    $branchService,
        protected CategoryService  $categoryService,
        protected VendorBranch     $vendorBranch,
        protected StockService     $stockService,
        protected OperationService $operationService,
        protected VendorService $vendorService,
        protected StockDetail $stockDetail
    ) {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
            $vendors = $this->vendorService->model->where('parent_id', $parentId)->get();
            $vendors[] =  $this->vendorService->model->where('id', $parentId)->first();
            $vendorIds = $vendors->pluck('id');
            $obj = $this->model->whereIn('Branch_id', $this->branchService->model->whereIn('vendor_id', $vendorIds)->pluck('id'));
            return DataTables::of($obj)
                ->editColumn('branch_id', function ($obj) {
                    return $obj->branch->name;
                })
                ->addColumn('action', function ($obj) {
                    $buttons = '';
                    if (Auth::guard('vendor')->user()->can("update_investor")) {

                        $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';
                    }
                    if (Auth::guard('vendor')->user()->can("delete_investor")) {

                        $buttons .= '

                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>

                    ';
                    }
                    if (Auth::guard('vendor')->user()->can("create_stock")) {
                        $buttons .= '

                             <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light addStock">
                            <i class="fa fa-plus"></i>
                        </button>
                    ';
                    }
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
        $auth = auth('vendor')->user();
        $branches = [];
        if ($auth->parent_id == null) {
            $branches = $this->branchService->model->apply()->whereIn('vendor_id', [$auth->parent_id, $auth->id])->get();
        } else {
            $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
            $branches = $this->branchService->model->apply()->whereIn('id', $branchIds)->get();
        }
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
        $auth = auth('vendor')->user();
        $branches = [];
        if ($auth->parent_id == null) {
            $branches = $this->branchService->model->apply()->whereIn('vendor_id', [$auth->parent_id, $auth->id])->get();
        } else {
            $branchIds = $this->vendorBranch->where('vendor_id', $auth->id)->pluck('branch_id');
            $branches = $this->branchService->model->apply()->whereIn('id', $branchIds)->get();
        }
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

        return view("{$this->folder}/parts/add_stock", [
            'storeRoute' => route("vendor.investors.storeStock"),
            'investorId' => $id,
            'categories' => $this->categoryService->model->where('vendor_id', $auth->parent_id ?? $auth->id)->get(),
//            'categories' => $this->categoryService->model->where('vendor_id', $auth->parent_id ?? $auth->id)->get(),

//            'branches' => $branches,
            'branches' => $this->branchService->model->where('id', $this->model->where('id', $id)->first()->branch_id)->get(),

        ]);
    }

    public function storeStock($data): JsonResponse
    {
        try {
            $data['vendor_id'] = auth('vendor')->user()->parent_id ?? auth('vendor')->user()->id;
            $data = $this->prepareStockData($data);
            $stockData = $data;
            unset($stockData['operation_type']);

            $obj = $this->stockService->createData($stockData);
            $this->operationService->model->create([
                'stock_id' => $obj->id,
                'type' => $data['operation_type'],
            ]);


            // check if operation type is 1
            if ($data['operation_type'] == 1) {


                // store in stock details
                for ($i = 0; $i < $data['quantity']; $i++) {
                    $this->stockDetail->create([
                        'stock_id' => $obj->id,
                        'quantity' => 1,
                        'price' => $data['price'] / $data['quantity'],
                        'vendor_commission' => $data['vendor_commission'] / $data['quantity'],
                        'investor_commission' => $data['investor_commission'] / $data['quantity'],
                        'sell_diff' => $data['sell_diff'] / $data['quantity'],


                    ]);
                }
            }else{
                $stockDetails = $this->stockDetail->whereHas('stock', function ($query) use ($data) {
                    $query->where('branch_id', $data['branch_id'])
                          ->where('category_id', $data['category_id'])
                          ->where('investor_id', $data['investor_id']);
                })
                ->orderBy('created_at', 'asc')
                ->where('is_sold', 0)
                ->OrderBy('id', 'asc')
                ->take($data['quantity'])
                ->get();

                foreach ($stockDetails as $stockDetail) {
                    $stockDetail->is_sold = 1;
                    $stockDetail->save();
                }
            }


            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => "حدث خطأ ما", "خطأ" => $e->getMessage()]);
        }
    }

    private function prepareStockData($data)
    {
        if ($data['operation'] == 1) {
            $data['price'] = ($data['total_price_add'] - ($data['vendor_commission'] + $data['investor_commission'] + $data['sell_diff']));
            $data['operation_type'] = 1;
        } else {
            $data['operation_type'] = 0;
        }
        unset($data['operation']);
        return $data;
    }

    public function getAvailableStock($request): JsonResponse
    {
        $investorId = $request->get('investor_id');
        $branchId = $request->get('branch_id');
        $categoryId = $request->get('category_id');

        // استعلام العمليات مع العلاقة
        $addOperations = $this->operationService->model->where('type', 1)
            ->with(['stock' => function ($query) use ($investorId, $branchId, $categoryId) {
                $query->where('investor_id', $investorId)
                    ->where('branch_id', $branchId)
                    ->where('category_id', $categoryId);
            }]);

        $sellOperations = $this->operationService->model->where('type', 0)
            ->with(['stock' => function ($query) use ($investorId, $branchId, $categoryId) {
                $query->where('investor_id', $investorId)
                    ->where('branch_id', $branchId)
                    ->where('category_id', $categoryId);
            }]);


        $addStock = $this->stockService->model->whereIn('id', $addOperations->pluck('stock_id'));
        $sellStock = $this->stockService->model->whereIn('id', $sellOperations->pluck('stock_id'));


        // حساب القيم المجمعة
        return response()->json([
            'status' => 200,
            'available' => (int)($addStock->sum('quantity') - $sellStock->sum('quantity')),
            'total_price' => $addStock->sum('total_price_add') - $sellStock->sum('total_price_sub'),
            'total_price_commission' => $addStock->sum('total_price_add') -
                ($addStock->sum('vendor_commission') + $addStock->sum('investor_commission') + $addStock->sum('sell_diff'))
                - $sellStock->sum('total_price_sub') - ($sellStock->sum('vendor_commission') + $sellStock->sum('investor_commission')
                    + $sellStock->sum('sell_diff')),
        ]);
    }
}
