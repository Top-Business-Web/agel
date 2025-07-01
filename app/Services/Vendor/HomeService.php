<?php

namespace App\Services\Vendor;

use App\Models\Client;
use App\Models\Investor;
use App\Models\Order;
use App\Models\Unsurpassed;
use App\Models\Vendor as ObjModel;
use App\Models\Branch;
use App\Models\Plan;
use App\Models\PlanSubscription;
use App\Models\Region;
use App\Models\Stock;
use App\Models\Vendor;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class HomeService extends BaseService
{

    public function __construct(protected ObjModel $objModel,protected PlanSubscription $planSubscription,protected Order $order , protected Unsurpassed $unsurpassed,protected Client $client,protected Branch $branch,protected Vendor $vendor,protected Investor $investor)
    {
        parent::__construct($objModel);
    }
    public function index($request = null)
    {
        $vendorPlanSubscription = $this->planSubscription
            ->where('vendor_id', VendorParentAuthData('id'))
            ->where('status', 1)
            ->first();

        $selectedYear = $request->year ?? 'all';
        $selectedMonth = $request->month ?? 'all';

        $data = $this->loadData();

        $data = $this->applyYearFilter($data, $selectedYear);
        $data = $this->applyMonthFilter($data, $selectedMonth);

        $totals = $this->calculateTotals($data);

        return view('vendor/index', array_merge(
            $this->prepareCounts($data),
            $totals,
            $this->getStaticData($selectedYear, $selectedMonth),
            ['vendorPlanSubscription' => $vendorPlanSubscription]
        ));
    }

    protected function loadData()
    {
        return [
            'admins' => $this->model,
            'investors' => $this->investor,
            'unsurpasseds' => $this->unsurpassed,
            'clients' => $this->client,
            'orders' => $this->order,
            'branches'=>$this->branch,
//            'offices' => $this->vendor->whereNull('parent_id'),
//            'vendors' => $this->vendor->whereNotNull('parent_id'),
//            'branches' => $this->branch,
//            'regions' => $this->region,
//            'stock' => $this->stock->whereNotNull('total_price_add'),
//            'plans' => $this->plan,
//            'vendorsWithOutPlans' => $this->vendor->whereNotNull('parent_id')->where('plan_id', 1),
            'activeSubscriptions' => $this->planSubscription->where('status', 1),
            'requestedSubscriptions' => $this->planSubscription->where('status', 0),
            'rejectedSubscriptions' => $this->planSubscription->where('status', 2),
            'expiredSubscriptions' => $this->planSubscription->where('status', 3),
            'totalOrdersAmounts' => $this->order->where('vendor_id', function ($query) { // get the main vendor
                $query->select('id')
                    ->from('vendors')
                    ->where('id', $this->model->id)
                    ->orWhere(function ($q) {
                        $q->where('parent_id', $this->model->id)
                            ->orWhereNull('parent_id');
                    });
            })->getQuery(),
            'totalClientsUnpaidAmount' => $this->order->whereIn('orders.vendor_id', function ($query) {
                $query->select('id')
                    ->from('vendors')
                    ->where('id', $this->model->id)
                    ->orWhere('parent_id', $this->model->id)
                    ->orWhereNull('parent_id');
            })->select(DB::raw('orders.id'))
                ->selectRaw('SUM(required_to_pay) as total_required, COALESCE(SUM(os.paid), 0) as total_paid')
                ->leftJoin('order_statuses as os', 'orders.id', '=', 'os.order_id')
                ->groupBy('orders.id')
                ->get(),
            'totalClientsPaidAmount' => $this->order->whereIn('orders.vendor_id', function ($query) {
                $query->select('id')
                    ->from('vendors')
                    ->where('id', $this->model->id)
                    ->orWhere('parent_id', $this->model->id)
                    ->orWhereNull('parent_id');
            })->select(DB::raw('orders.id'))
                ->selectRaw('SUM(required_to_pay) as total_required, COALESCE(SUM(os.paid), 0) as total_paid')
                ->leftJoin('order_statuses as os', 'orders.id', '=', 'os.order_id')
                ->groupBy('orders.id')
                ->get(),
            'totalUnSurpassedMoneyAmounts' => $this->unsurpassed->query(),
        ];
    }

    protected function applyYearFilter(array $data, $year)
    {
        if ($year === 'all') return $data;

        $keys = [
            'branches',
            'admins',
            'offices',
            'vendors',
            'branches',
            'regions',
            'stock',
            'plans',
            'investors',
            'unsurpasseds',
            'clients',
            'orders',
            'activeSubscriptions',
            'requestedSubscriptions',
            'rejectedSubscriptions',
            'expiredSubscriptions',
            'totalOrdersAmounts',
            'totalOfficesAmounts',
            'totalUnSurpassedMoneyAmounts'
        ];

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $data[$key] = $data[$key]->whereYear('created_at', $year);
            }
        }

        return $data;
    }

    protected function applyMonthFilter(array $data, $month)
    {
        if ($month === 'all') return $data;

        $keys = [
            'admins',
            'offices',
            'vendors',
            'branches',
            'regions',
            'stock',
            'plans',
            'investors',
            'unsurpasseds',
            'clients',
            'orders',
            'activeSubscriptions',
            'requestedSubscriptions',
            'rejectedSubscriptions',
            'expiredSubscriptions',
            'totalOrdersAmounts',
            'totalOfficesAmounts',
            'totalUnSurpassedMoneyAmounts'
        ];

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $data[$key] = $data[$key]->whereMonth('created_at', $month);
            }
        }

        return $data;
    }

    protected function calculateTotals(array $data)
    {
        return [
//            'totalOrdersAmounts' => $data['totalOrdersAmounts']->sum('required_to_pay') ?? 0,
//            'totalOfficesAmounts' => $data['totalOfficesAmounts']->sum('balance') ?? 0,
            'totalUnSurpassedMoneyAmounts' => $data['totalUnSurpassedMoneyAmounts']->sum('debt') ?? 0,
            'totalClientsPaidAmount' => $data['totalClientsPaidAmount']->sum(function($order) {
                    return $order->total_required - $order->total_paid;
                }) ?? 0,

        ];
    }

    protected function prepareCounts(array $data)
    {
        return [
            'investors' => $data['investors']->count(),
            'unsurpassed' => $data['unsurpasseds']->count(),
            'clients' => $data['clients']->count(),
            'orders' => $data['orders']->count(),
            'branches' => $data['branches']->count(),
//            'admins' => $data['admins']->get()->count(),
//            'offices' => $data['offices']->get()->count(),
//            'vendors' => $data['vendors']->get()->count(),
//            'branches' => $data['branches']->get()->count(),
//            'regions' => $data['regions']->get()->count(),
//            'categories' => $this->category->count(),
//            'stock' => $data['stock']->get()->sum('quantity'),
//            'plans' => $data['plans']->get()->count(),
//            'vendorsWithOutPlans' => $data['vendorsWithOutPlans']->count(),
//            'activeSubscriptions' => $data['activeSubscriptions']->get()->count(),
//            'requestedSubscriptions' => $data['requestedSubscriptions']->get()->count(),
//            'rejectedSubscriptions' => $data['rejectedSubscriptions']->get()->count(),
//            'expiredSubscriptions' => $data['expiredSubscriptions']->get()->count(),
        ];
    }

    protected function getStaticData($selectedYear, $selectedMonth)
    {
        return [
            'years' => range(2025, date('Y')),
            'months' => [
                '1' => 'يناير',
                '2' => 'فبراير',
                '3' => 'مارس',
                '4' => 'أبريل',
                '5' => 'مايو',
                '6' => 'يونيو',
                '7' => 'يوليو',
                '8' => 'أغسطس',
                '9' => 'سبتمبر',
                '10' => 'أكتوبر',
                '11' => 'نوفمبر',
                '12' => 'ديسمبر'
            ],
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
        ];
    }
}
