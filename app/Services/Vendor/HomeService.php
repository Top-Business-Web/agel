<?php

namespace App\Services\Vendor;

use App\Http\Controllers\Controller;
use App\Services\BaseService;

class HomeService extends Controller
{


    public function index(){
        return view('vendor/index');

    }

}
