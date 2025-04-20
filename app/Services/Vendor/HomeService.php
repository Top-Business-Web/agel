<?php

namespace App\Services\Vendor;

use App\Models\Vendor as ObjModel;
use App\Models\Branch;
use App\Models\Plan;
use App\Models\PlanSubscription;
use App\Models\Region;
use App\Models\Stock;
use App\Models\Vendor;
use App\Services\BaseService;

class HomeService extends BaseService
{

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index()
    {


        return view(view: 'vendor/index');
    }

}
