<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CityRequest as ObjRequest;
use App\Models\City as ObjModel;
use App\Services\Vendor\HomeService as ObjService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index()
    {
        return $this->objService->index();
    }

}
