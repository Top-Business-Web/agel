<?php

namespace App\Services\Vendor;

use App\Models\Client;
use App\Models\Investor;
use App\Models\Order as ObjModel;
use App\Models\OrderInstallment;
use App\Models\StockDetail;
use App\Models\Vendor;
use App\Models\VendorBranch;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

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
        private Client $client,
        protected OrderInstallment $orderInstallment
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

                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    return $buttons;
                })->addColumn('client_national_id', function ($obj) {
                    return $obj->client_id ? $obj->client->national_id : "";
                })->editColumn('client_id', function ($obj) {
                    return $obj->client_id ? $obj->client->name : "";
                })->editColumn('investor_id', function ($obj) {
                    return $obj->investor_id ? $obj->investor->name : "";
                })->editColumn('status', function ($obj) {
                    return $obj->status == 1 ? $status = "مكتمل" : $status = "جديد";

                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'bladeName' => "الطلبات",
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
            'profit_ratio' => auth('vendor')->user()->parent_id == null ? auth('vendor')->user()->profit_ratio
                : Vendor::where('id', auth('vendor')->user()->parent_id)->first()->profit_ratio,

            'is_profit_ratio_static' => auth('vendor')->user()->parent_id == null ? auth('vendor')->user()->is_profit_ratio_static
                : Vendor::where('id', auth('vendor')->user()->parent_id)->first()->is_profit_ratio_static


        ]);
    }


    public function calculatePrices($request)
    {

        $stock = $this->stockService->model
            ->where('investor_id', $request->investor_id)
            ->where('branch_id', $request->branch_id)
            ->where('category_id', $request->category_id)
            ->whereHas('operations', function ($query) {
                $query->where('type', 1);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $stockDetails = $this->stockDetail
            ->whereIn('stock_id', $stock->pluck('id'))
            ->where('is_sold', 0)
            ->orderBy('created_at', direction: 'asc')
            ->get();

        $quantity = $request->quantity;
        $expected_price = 0;
        $Total_expected_commission = 0;
        $sell_diff = 0;
        foreach ($stockDetails as $stockDetail) {
            if ($quantity <= 0) {
                break;
            }

            $used_quantity = min($stockDetail->quantity, $quantity);
            $quantity -= $used_quantity;

            $expected_price += $stockDetail->price;
            $Total_expected_commission += $stockDetail->vendor_commission + $stockDetail->investor_commission;
            $sell_diff += $stockDetail->sell_diff;
        }

        $available_quantity = $request->quantity - $quantity; // الكمية الحقيقية المتوفرة اللي تم استخدامها

        return response()->json([
            'expected_price' => $expected_price,
            'Total_expected_commission' => $Total_expected_commission,
            'sell_diff' => $sell_diff,
            'available_quantity' => $available_quantity,
        ]);
    }

    public function store($data): JsonResponse
    {
        try {
            // check if client exists
            $data['client_id'] = $this->checkClient($data);
            $this->setDefaultInstallmentValues($data);


            $stockDetails = $this->getAvailableStockDetails($data);
            $this->markStockAsSold($stockDetails, $data['quantity']);

            $order = $this->storeOrder($data);

            if (isset($data['is_installment']) && $data['is_installment'] === 'on' && $data['installment_number'] > 0) {
                $this->createInstallments($order, $data);
            }
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);
        }
    }



    public function checkClient($data)
    {
        $client = $this->client->firstOrCreate(
            ['national_id' => $data['national_id']],
            [
                'name' => $data['name'],
                'phone' => '+966' . $data['phone'],
                'branch_id' => $data['branch_id'],
            ]
        );

        return $client->id;
    }


    private function setDefaultInstallmentValues(&$data): void
    {
        $data['is_installment'] = $data['is_installment'] ?? 'off';
        $data['installment_number'] = $data['installment_number'] ?? 0;
    }





    private function getAvailableStockDetails($data)
    {
        $stock = $this->stockService->model
            ->where('investor_id', $data['investor_id'])
            ->where('branch_id', $data['branch_id'])
            ->where('category_id', $data['category_id'])
            ->whereHas('operations', fn($q) => $q->where('type', 1))
            ->orderBy('created_at', 'asc')
            ->get();

        return $this->stockDetail
            ->whereIn('stock_id', $stock->pluck('id'))
            ->where('is_sold', 0)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }


    private function markStockAsSold($stockDetails, $quantity): void
    {
        foreach ($stockDetails as $stockDetail) {
            if ($quantity <= 0) break;

            $stockDetail->update(['is_sold' => 1]);
            $quantity -= $stockDetail->quantity;
        }
    }


    private function storeOrder(&$data)
    {
        unset(
            $data['name'],
            $data['phone'],
            $data['national_id'],
            $data['is_installment'],
            $data['installment_number']
        );

        $data['date'] = Carbon::parse($data['date'])->addDays(3);
        $data['order_number'] = random_int(1000000, 9999999);
        $data['vendor_id'] = auth('vendor')->user()->parent_id ?? auth('vendor')->user()->id;

        return $this->createData($data);
    }

    private function createInstallments($order, $data): void
    {
        $installmentNumber = $data['installment_number'];
        $installmentAmount = $data['required_to_pay'] / $installmentNumber;

        $startDate = Carbon::now();
        $endDate = Carbon::parse($data['date']);
        $totalDays = $startDate->diffInDays($endDate);
        $period = intval($totalDays / $installmentNumber);

        for ($i = 1; $i <= $installmentNumber; $i++) {
            $this->orderInstallment->create([
                'order_id' => $order->id,
                'installment' => $installmentAmount,
                'installment_date' => $startDate->copy()->addDays($i * $period),
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
