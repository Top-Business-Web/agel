<?php

namespace App\Services\Admin;

//use App\Models\Module;

namespace App\Services\Admin;


use App\Models\Admin as ObjModel;


use App\Models\Branch;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanSubscription;
use App\Models\Region;
use App\Models\Stock;
use App\Models\Unsurpassed;
use App\Models\Vendor;
use App\Services\BaseService;

class HomeService extends BaseService
{
//    protected string $folder = 'admin/home';
//    protected string $route = 'admin.vendors';

    public function __construct(ObjModel $objModel, protected Region $region , protected Branch $branch,protected Vendor $vendor,protected Stock $stock,protected Plan $plan,protected planSubscription $planSubscription , protected Order $order,protected Unsurpassed $unsurpassed)
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
        $years = range(2025, date('Y'));
        $months = ['1' => 'يناير', '2' => 'فبراير', '3' => 'مارس', '4' => 'أبريل', '5' => 'مايو', '6' => 'يونيو', '7' => 'يوليو', '8' => 'أغسطس', '9' => 'سبتمبر', '10' => 'أكتوبر', '11' => 'نوفمبر', '12' => 'ديسمبر'];
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
            'years' => $years,
            'months' => $months,
        ]);
    }

    public function homeFilter($request)
    {
        $admins = $this->model;
        $offices = $this->vendor->where('parent_id', null);
        $vendors = $this->vendor;
        $branches = $this->branch;
        $regions = $this->region;
        $stock = $this->stock;
        $plans = $this->plan;
        $activeSubscriptions = $this->planSubscription->where('status', 1);
        $requestedSubscriptions = $this->planSubscription->where('status', 0);
        $rejectedSubscriptions = $this->planSubscription->where('status', 2);
        $selectedYear = $request->year ?? 'all';
        $selectedMonth = $request->month ?? 'all';
        $totalOrdersAmounts = $this->order->sum('required_to_pay');
        $totalOfficesAmounts = $this->vendor->sum('balance');
        $totalPayedUnsurpassedMoneyAmounts = $this->order->whereHas('order_status', function ($q) {
            $q->where(function ($query) {
                $query->where('is_graced', 1)
                    ->whereRaw('NOW() > grace_date');
            })->orWhere(function ($query) {
                $query->where('is_graced', 0)
                    ->whereNotNull('date');
            });
        });
        $totalUnSurpassedMoneyAmounts = $this->unsurpassed->first();//إجمالي المبالغ المتعثره

        if ($request->year && $request->year != 'all') {
            $year = $request->year;
            $admins = $admins->whereYear('created_at', $year);
            $offices = $offices->whereYear('created_at', $year);
            $vendors = $vendors->whereYear('created_at', $year);
            $branches = $branches->whereYear('created_at', $year);
            $regions = $regions->whereYear('created_at', $year);
            $stock = $stock->whereYear('created_at', $year);
            $plans = $plans->whereYear('created_at', $year);
            $activeSubscriptions = $activeSubscriptions->whereYear('created_at', $year);
            $requestedSubscriptions = $requestedSubscriptions->whereYear('created_at', $year);
            $rejectedSubscriptions = $rejectedSubscriptions->whereYear('created_at', $year);
            $totalOrdersAmounts = $this->order->whereYear('created_at', $year)->first();
            $totalOfficesAmounts = $this->vendor->whereYear('created_at', $year)->sum('balance');
            $totalPayedUnsurpassedMoneyAmounts = $totalPayedUnsurpassedMoneyAmounts->whereYear('created_at', $year);
            $totalUnSurpassedMoneyAmounts = $this->unsurpassed->whereYear('created_at', $year)->first();
        }

        if ($request->month && $request->month != 'all') {
            $month = $request->month;
            $admins = $admins->whereMonth('created_at', $month);
            $offices = $offices->whereMonth('created_at', $month);
            $vendors = $vendors->whereMonth('created_at', $month);
            $branches = $branches->whereMonth('created_at', $month);
            $regions = $regions->whereMonth('created_at', $month);
            $stock = $stock->whereMonth('created_at', $month);
            $plans = $plans->whereMonth('created_at', $month);
            $activeSubscriptions = $activeSubscriptions->whereMonth('created_at', $month);
            $requestedSubscriptions = $requestedSubscriptions->whereMonth('created_at', $month);
            $rejectedSubscriptions = $rejectedSubscriptions->whereMonth('created_at', $month);
            $totalOrdersAmounts = $this->order->whereYear('created_at', $year)->first();
            $totalOfficesAmounts = $this->vendor->whereYear('created_at', $year)->sum('balance');
            $totalPayedUnsurpassedMoneyAmounts = $totalPayedUnsurpassedMoneyAmounts->whereYear('created_at', $year);
            $totalUnSurpassedMoneyAmounts = $this->unsurpassed->whereYear('created_at', $year)->first();
        }

        $years = range(2025, date('Y'));
        $months = ['1' => 'يناير', '2' => 'فبراير', '3' => 'مارس', '4' => 'أبريل', '5' => 'مايو', '6' => 'يونيو', '7' => 'يوليو', '8' => 'أغسطس', '9' => 'سبتمبر', '10' => 'أكتوبر', '11' => 'نوفمبر', '12' => 'ديسمبر'];
        return view('admin/index', [
            'admins' => $admins->get()->count(),
            'offices' => $offices->get()->count(),
            'vendors' => $vendors->get()->count(),
            'branches' => $branches->get()->count(),
            'regions' => $regions->get()->count(),
            'stock' => $stock->get()->count(),
            'plans' => $plans->get()->count(),
            'activeSubscriptions' => $activeSubscriptions->get()->count(),
            'requestedSubscriptions' => $requestedSubscriptions->get()->count(),
            'rejectedSubscriptions' => $rejectedSubscriptions->get()->count(),
            'years' => $years,
            'months' => $months,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'totalOrdersAmounts'=> $totalOrdersAmounts,
            'totalOfficesAmounts'=> $totalOfficesAmounts,
            'totalUnSurpassedMoneyAmounts'=>$totalUnSurpassedMoneyAmounts,
            'totalPayedUnsurpassedMoneyAmounts'=>$totalPayedUnsurpassedMoneyAmounts

        ]);
    }

}
