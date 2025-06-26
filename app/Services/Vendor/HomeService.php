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

    public function __construct(ObjModel $objModel,protected PlanSubscription $planSubscription)
    {
        parent::__construct($objModel);
    }

    public function index()
    {

      $vendorPlanSubscription = $this->planSubscription
            ->where('vendor_id', VendorParentAuthData('id'))
            ->where('status', 1)
            ->first();
        return view( 'vendor/index',[
            'vendorPlanSubscription'=> $vendorPlanSubscription,
        ]);
    }

}
