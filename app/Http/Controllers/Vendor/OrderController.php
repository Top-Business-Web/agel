<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest as ObjRequest;
use App\Models\Order as ObjModel;
use App\Models\StockDetail;
use App\Services\Vendor\OrderService as ObjService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(ObjRequest $data)
    {
        $data = $data->validated();
        return $this->objService->store($data);
    }

    public function edit(ObjModel $model)
    {
        return $this->objService->edit($model);
    }

    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($data, $id);
    }

    public function destroy($id)
    {
        $this->objService->reverseStockDetails($id);
        return $this->objService->delete($id);
    }


    public function calculatePrices(Request $request){
        return $this->objService->calculatePrices($request);
    }
}
