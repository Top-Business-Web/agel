<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\VendorRequest as ObjRequest;
use App\Models\Vendor as ObjModel;
use App\Services\Vendor\VendorService as ObjService;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct(protected ObjService $objService)
    {
    }

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

    public function show($id)
    {
        return 'hello world';
    }

    public function edit(ObjModel $vendor)
    {
        return $this->objService->edit($vendor);
    }

    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($data, $id);
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
