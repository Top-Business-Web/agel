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

        $years = range(2025, date('Y'));
        $months = ['1' => 'يناير', '2' => 'فبراير', '3' => 'مارس', '4' => 'أبريل', '5' => 'مايو', '6' => 'يونيو', '7' => 'يوليو', '8' => 'أغسطس', '9' => 'سبتمبر', '10' => 'أكتوبر', '11' => 'نوفمبر', '12' => 'ديسمبر'];
        return view( 'vendor/index',[
            'vendorPlanSubscription'=> $vendorPlanSubscription,
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

        $totalOrdersAmounts = $this->order->query();
        $totalOfficesAmounts = $this->vendor->query();
        $totalPayedUnsurpassedMoneyAmounts = $this->order->whereHas('order_status', function ($q) {
            $q->where(function ($query) {
                $query->where('is_graced', 1)
                    ->whereRaw('NOW() > grace_date');
            })->orWhere(function ($query) {
                $query->where('is_graced', 0)
                    ->whereNotNull('date')
                    ->whereRaw('NOW() > date');
            });
        });
        $totalUnSurpassedMoneyAmounts = $this->order->query();

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

            $totalOrdersAmounts = $totalOrdersAmounts->whereYear('created_at', $selectedYear);
            $totalOfficesAmounts = $totalOfficesAmounts->whereYear('created_at', $selectedYear);
            $totalPayedUnsurpassedMoneyAmounts = $totalPayedUnsurpassedMoneyAmounts->whereYear('created_at', $selectedYear);
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

            $totalOrdersAmounts = $totalOrdersAmounts->whereMonth('created_at', $selectedMonth);
            $totalOfficesAmounts = $totalOfficesAmounts->whereMonth('created_at', $selectedMonth);
            $totalPayedUnsurpassedMoneyAmounts = $totalPayedUnsurpassedMoneyAmounts->whereMonth('created_at', $selectedMonth)->with('order_status');
        }

        $totalOrdersAmounts = $totalOrdersAmounts->sum('required_to_pay') ?? 0;
        $totalOfficesAmounts = $totalOfficesAmounts->sum('balance') ?? 0;
        $totalPayedUnsurpassedMoneyAmounts = $totalPayedUnsurpassedMoneyAmounts->select('order_statuses.*')->join('order_statuses', 'orders.id', '=', 'order_statuses.order_id')->sum('order_statuses.paid') ?? 0;
        $totalUnSurpassedMoneyAmounts = $totalUnSurpassedMoneyAmounts->sum('required_to_pay') - $totalPayedUnsurpassedMoneyAmounts;

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
