<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\InvestorService as ObjService;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function __construct(protected ObjService $objService)
    {
    }

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }


}
