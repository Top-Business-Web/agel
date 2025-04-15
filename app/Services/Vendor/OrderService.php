<?php

namespace App\Services\Vendor;

use App\Models\Investor;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;
use App\Models\Order as ObjModel;
use App\Models\StockDetail;
use App\Models\Vendor;

class OrderService extends BaseService
{
    protected string $folder = 'vendor/order';
    protected string $route = 'orders';

    public function __construct(
        ObjModel $objModel,
        protected CategoryService $categoryService,
        protected BranchService $branchService,
        protected VendorBranch $vendorBranch,
        protected Investor $investor,
        protected StockService $stockService,
        protected StockDetail $stockDetail,
    ) {
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
                'bladeName' => "",
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
            'categories' => $this->categoryService->model->where('vendor_id', $auth->parent_id ?? $auth->id)->get(),
            'investors' => $this->investor->whereIn('branch_id', $branches->pluck('id'))->get(),
            'categories' => $this->categoryService->model->where('vendor_id', $auth->parent_id ?? $auth->id)->get(),
            'branches' => $branches,
            'profit_ratio'=> auth('vendor')->user()->parent_id == null ? auth('vendor')->user()->profit_ratio
             :Vendor::where('id', auth('vendor')->user()->parent_id)->first()->profit_ratio,

             'is_profit_ratio_static'=> auth('vendor')->user()->parent_id == null ? auth('vendor')->user()->is_profit_ratio_static
             :Vendor::where('id', auth('vendor')->user()->parent_id)->first()->is_profit_ratio_static


        ]);
    }


    public function calculatePrices($request){

        $stock=$this->stockService->model
        ->where('investor_id', $request->investor_id)
        ->where('branch_id', $request->branch_id)
        ->where('category_id', $request->category_id)
        ->whereHas('operations', function ($query) {
            $query->where('type', 1);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        $stockDetails=$this->stockDetail
        ->whereIn('stock_id', $stock->pluck('id'))
        ->where('is_sold',0)
        ->orderBy('created_at', 'desc')
        ->get();

        // sum stock details quantity to be == request quantity
        $quantity=$request->quantity;
        $expected_price=0;
        $Total_expected_commission=0;
        $sell_diff=0;
        foreach ($stockDetails as $stockDetail){
            $quantity-=$stockDetail->quantity;
            $expected_price+=$stockDetail->price;
            $Total_expected_commission+=$stockDetail->vendor_commission+$stockDetail->investor_commission;
            $sell_diff+=$stockDetail->sell_diff;
            // save stock detail in array
            if ($quantity==0){
                break;
            }
        }

        return response()->json(['status' => 200,[
            'expected_price'=>$expected_price,
            'Total_expected_commission'=>$Total_expected_commission,
            'sell_diff'=>$sell_diff,

            ]]);






    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Order');
        }

        try {
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
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
            $data['image'] = $this->handleFile($data['image'], 'Order');

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
