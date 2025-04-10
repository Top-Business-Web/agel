<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Plan as ObjModel;
use App\Services\Vendor\PlanService as ObjService;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function __construct(protected ObjService $objService)
    {
    }

    public function index()
    {
        return $this->objService->index();
    }

    public function create()
    {
        return $this->objService->create();
    }

//    public function store(ObjRequest $data)
    public function store(Request $data)
    {
//        $data = $data->validated();
        return $this->objService->store($data);
    }

    public function edit(ObjModel $client)
    {
        return $this->objService->edit($client);
    }

//    public function update(ObjRequest $request, $id)
    public function update($id)
    {
//        $data = $request->validated();
//        return $this->objService->update($data, $id);
    }

    public function destroy($id)
    {
        return $this->objService->delete($id);
    }

    public function updateColumnSelected(Request $request)
    {
        return $this->objService->updateColumnSelected($request, 'status');
    }

    public function deleteSelected(Request $request)
    {
        return $this->objService->deleteSelected($request);
    }
}
