<?php

namespace App\Services\Admin;

//use App\Models\Module;

namespace App\Services\Admin;


use App\Models\Admin as ObjModel;


use App\Models\Branch;
use App\Models\Plan;
use App\Models\PlanSubscription;
use App\Models\Region;
use App\Models\Stock;
use App\Models\Vendor;
use App\Services\BaseService;

class HomeService extends BaseService
{
//    protected string $folder = 'admin/home';
//    protected string $route = 'admin.vendors';

    public function __construct(ObjModel $objModel, protected Region $region , protected Branch $branch,protected Vendor $vendor,protected Stock $stock,protected Plan $plan,protected planSubscription $planSubscription)
    {
        parent::__construct($objModel);
    }


    public function index()
    {
        $admins=$this->model->get();
        $offices = $this->vendor->where('parent_id', null)->get();
        $vendors = $this->vendor->get();
        $branches = $this->branch->get();
        $regions = $this->region->get();
        $stock = $this->stock->get();
        $plans = $this->plan->get();
        $activeSubscriptions = $this->planSubscription->where('status', 1)->get();
        $requestedSubscriptions= $this->planSubscription->where('status', 0)->get();
        $rejectedSubscriptions= $this->planSubscription->where('status', 2)->get();


        return view('admin/index', [
            'admins' => $admins->count(),
            'offices' => $offices->count(),
            'vendors' => $vendors->count(),
            'branches' => $branches->count(),
            'regions' => $regions->count(),
            'stock' => $stock->count(),
            'plans' => $plans->count(),
            'activeSubscriptions' => $activeSubscriptions->count(),
            'requestedSubscriptions' => $requestedSubscriptions->count(),
            'rejectedSubscriptions' => $rejectedSubscriptions->count(),

        ]);
    }

}
