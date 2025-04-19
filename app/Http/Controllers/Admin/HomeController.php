<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VendorRequest as ObjRequest;
use App\Models\Vendor as ObjModel;
use App\Services\Admin\VendorService as ObjService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(protected ObjService $objService)
    {
    }

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

}
