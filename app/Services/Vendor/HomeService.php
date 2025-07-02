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



    public function __construct(
        protected ObjModel $objModel,
        protected PlanSubscription $planSubscription,
        protected Order $order,
        protected Unsurpassed $unsurpassed,
        protected Client $client,
        protected Branch $branch,
        protected Vendor $vendor,
        protected Investor $investor
    ) {
        parent::__construct($objModel);
    }

    public function index(Request $request = null)
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
        $counts = $this->prepareCounts($data);
        $staticData = $this->getStaticData($selectedYear, $selectedMonth);

        return view('vendor.index', array_merge(
            $counts,
            $totals,
            $staticData,
            ['vendorPlanSubscription' => $vendorPlanSubscription]
        ));
    }

    protected function loadData(): array
    {
        // Get vendor ID safely with fallback
        $vendorId = $this->getVendorId();

        return [
            'admins' => $this->model,
            'investors' => $this->investor,
            'unsurpasseds' => $this->unsurpassed,
            'clients' => $this->client,
            'orders' => $this->order,
            'branches' => $this->branch,
            'activeSubscriptions' => $this->planSubscription->where('status', 1),
            'requestedSubscriptions' => $this->planSubscription->where('status', 0),
            'rejectedSubscriptions' => $this->planSubscription->where('status', 2),
            'expiredSubscriptions' => $this->planSubscription->where('status', 3),
            'totalOrdersAmounts' => $this->getVendorOrders($vendorId),
            'totalInvestorsAmount' => $this->getInvestorWalletData(),
            'totalClientsUnpaidAmount' => $this->getClientUnpaidAmount($vendorId),
            'totalClientsPaidAmount' => $this->getClientPaidAmount($vendorId),
            'totalUnSurpassedMoneyAmounts' => $this->unsurpassed->query(),
        ];
    }

    /**
     * Get vendor ID safely with proper error handling
     */
    protected function getVendorId(): int
    {
        // Try to get from model first
        if ($this->model && $this->model->id) {
            return (int) $this->model->id;
        }

        // Try to get from VendorParentAuthData helper
        $authVendorId = VendorParentAuthData('id');
        if ($authVendorId) {
            return (int) $authVendorId;
        }

        // Try to get from authenticated user/session
        if (auth()->check() && auth()->user()->vendor_id) {
            return (int) auth()->user()->vendor_id;
        }

        // Throw exception if no vendor ID found
        throw new \Exception('Vendor ID not found. User may not be properly authenticated.');
    }

    protected function getVendorOrders(?int $vendorId)
    {
        if (!$vendorId) {
            return $this->order->whereRaw('1 = 0'); // Return empty query
        }

        // Get all vendor IDs that belong to this vendor (including sub-vendors)
        $vendorIds = $this->vendor
            ->where('id', $vendorId)
            ->orWhere('parent_id', $vendorId)
            ->pluck('id')
            ->toArray();

        // Add the main vendor ID if not already included
        if (!in_array($vendorId, $vendorIds)) {
            $vendorIds[] = $vendorId;
        }

        return $this->order->whereIn('vendor_id', $vendorIds);
    }

    protected function getInvestorWalletData()
    {
        return $this->investor
            ->join('investor_wallets', 'investors.id', '=', 'investor_wallets.investor_id')
            ->where('investors.id', $this->investor->id);
    }

    protected function getClientUnpaidAmount(?int $vendorId)
    {
        if (!$vendorId) {
            return collect(); // Return empty collection
        }

        // Get all vendor IDs that belong to this vendor (including sub-vendors)
        $vendorIds = $this->vendor
            ->where('id', $vendorId)
            ->orWhere('parent_id', $vendorId)
            ->pluck('id')
            ->toArray();

        // Add the main vendor ID if not already included
        if (!in_array($vendorId, $vendorIds)) {
            $vendorIds[] = $vendorId;
        }

        return $this->order
            ->whereIn('orders.vendor_id', $vendorIds)
            ->select(DB::raw('orders.id'))
            ->selectRaw('SUM(required_to_pay) as total_required, COALESCE(SUM(os.paid), 0) as total_paid')
            ->leftJoin('order_statuses as os', 'orders.id', '=', 'os.order_id')
            ->groupBy('orders.id')
            ->get();
    }

    protected function getClientPaidAmount(?int $vendorId)
    {
        if (!$vendorId) {
            return collect(); // Return empty collection
        }

        // Get all vendor IDs that belong to this vendor (including sub-vendors)
        $vendorIds = $this->vendor
            ->where('id', $vendorId)
            ->orWhere('parent_id', $vendorId)
            ->pluck('id')
            ->toArray();

        // Add the main vendor ID if not already included
        if (!in_array($vendorId, $vendorIds)) {
            $vendorIds[] = $vendorId;
        }

        return $this->order
            ->whereIn('orders.vendor_id', $vendorIds)
            ->select(DB::raw('orders.id'))
            ->selectRaw('SUM(required_to_pay) as total_required, COALESCE(SUM(os.paid), 0) as total_paid')
            ->leftJoin('order_statuses as os', 'orders.id', '=', 'os.order_id')
            ->groupBy('orders.id')
            ->get();
    }

    protected function applyYearFilter(array $data, $year): array
    {
        if ($year === 'all') {
            return $data;
        }

        $filterableKeys = [
            'branches', 'admins', 'investors', 'unsurpasseds', 'clients',
            'orders', 'activeSubscriptions', 'totalOrdersAmounts',
            'totalUnSurpassedMoneyAmounts'
        ];

        foreach ($filterableKeys as $key) {
            if (isset($data[$key]) && method_exists($data[$key], 'whereYear')) {
                $data[$key] = $data[$key]->whereYear('created_at', $year);
            }
        }

        return $data;
    }

    protected function applyMonthFilter(array $data, $month): array
    {
        if ($month === 'all') {
            return $data;
        }

        $filterableKeys = [
            'branches', 'investors', 'unsurpasseds', 'clients',
            'orders', 'activeSubscriptions', 'totalOrdersAmounts',
            'totalUnSurpassedMoneyAmounts'
        ];

        foreach ($filterableKeys as $key) {
            if (isset($data[$key]) && method_exists($data[$key], 'whereMonth')) {
                $data[$key] = $data[$key]->whereMonth('created_at', $month);
            }
        }

        return $data;
    }

    protected function calculateTotals(array $data): array
    {
        return [
            'totalOrdersAmounts' => $data['totalOrdersAmounts']->sum('required_to_pay') ?? 0,
            'totalUnSurpassedMoneyAmounts' => $data['totalUnSurpassedMoneyAmounts']->sum('debt') ?? 0,
            'totalClientsPaidAmount' => $data['totalClientsPaidAmount']->sum('total_paid') ?? 0,
            'totalClientsUnpaidAmount' =>
                ($data['totalClientsUnpaidAmount']->sum('total_required') ?? 0) -
                ($data['totalClientsUnpaidAmount']->sum('total_paid') ?? 0),
            'totalInvestorsAmount' => $data['totalInvestorsAmount']->sum('investor_wallets.amount') ?? 0,
        ];
    }

    protected function prepareCounts(array $data): array
    {
        try {
            $vendorId = $this->getVendorId();
        } catch (\Exception $e) {
            // Return zero counts if vendor ID cannot be determined
            return [
                'investorsCount' => 0,
                'unsurpassedCount' => 0,
                'clientsCount' => 0,
                'ordersCount' => 0,
                'branchesCount' => 0,
                'vendorsCount' => 0,
            ];
        }

        $vendorQuery = function ($query) use ($vendorId) {
            $query->select('id')
                ->from('vendors')
                ->where('id', $vendorId)
                ->orWhere('parent_id', $vendorId)
                ->orWhereNull('parent_id');
        };
        // Get branches associated with the vendors
        $branchSubquery = function ($query) use ($vendorQuery) {
            $query->select('branch_id')
                ->from('vendors')
                ->whereIn('id', $vendorQuery);
        };

        return [
            'investorsCount' => $this->investor->whereIn('branch_id', $branchSubquery)->count(),
            'unsurpassedCount' => $this->unsurpassed->where('phone', auth()->user()->phone)->count(),
            'clientsCount' => $this->client->whereIn('branch_id', $branchSubquery)->count(),
            'ordersCount' => $this->order->whereIn('vendor_id', $vendorQuery)->count(),
            'branchesCount' => $this->branch->whereIn('vendor_id', $vendorQuery)->count(),
            'vendorsCount' => $this->vendor->where('parent_id', $vendorId)->count(),
        ];
    }

    protected function getStaticData($selectedYear, $selectedMonth): array
    {
        return [
            'years' => range(2020, date('Y')), // Changed from 2025 to 2020
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










//    public function __construct(protected ObjModel $objModel,protected PlanSubscription $planSubscription,protected Order $order , protected Unsurpassed $unsurpassed,protected Client $client,protected Branch $branch,protected Vendor $vendor,protected Investor $investor)
//    {
//        parent::__construct($objModel);
//    }
//    public function index($request = null)
//    {
//        $vendorPlanSubscription = $this->planSubscription
//            ->where('vendor_id', VendorParentAuthData('id'))
//            ->where('status', 1)
//            ->first();
//
//        $selectedYear = $request->year ?? 'all';
//        $selectedMonth = $request->month ?? 'all';
//
//        $data = $this->loadData();
//
//        $data = $this->applyYearFilter($data, $selectedYear);
//        $data = $this->applyMonthFilter($data, $selectedMonth);
//
//        $totals = $this->calculateTotals($data);
//
//        return view('vendor/index', array_merge(
//            $this->prepareCounts($data),
//            $totals,
//            $this->getStaticData($selectedYear, $selectedMonth),
//            ['vendorPlanSubscription' => $vendorPlanSubscription]
//        ));
//    }
//
//    protected function loadData()
//    {
//        return [
//            'admins' => $this->model,
//            'investors' => $this->investor,
//            'unsurpasseds' => $this->unsurpassed,
//            'clients' => $this->client,
//            'orders' => $this->order,
//            'branches'=>$this->branch,
////            'offices' => $this->vendor->whereNull('parent_id'),
////            'vendors' => $this->vendor->whereNotNull('parent_id'),
////            'branches' => $this->branch,
////            'regions' => $this->region,
////            'stock' => $this->stock->whereNotNull('total_price_add'),
////            'plans' => $this->plan,
////            'vendorsWithOutPlans' => $this->vendor->whereNotNull('parent_id')->where('plan_id', 1),
//            'activeSubscriptions' => $this->planSubscription->where('status', 1),
//            'requestedSubscriptions' => $this->planSubscription->where('status', 0),
//            'rejectedSubscriptions' => $this->planSubscription->where('status', 2),
//            'expiredSubscriptions' => $this->planSubscription->where('status', 3),
//            'totalOrdersAmounts' => $this->order->where('vendor_id', function ($query) { // get the main vendor
//                $query->select('id')
//                    ->from('vendors')
//                    ->where('id', $this->model->id)
//                    ->orWhere(function ($q) {
//                        $q->where('parent_id', $this->model->id)
//                            ->orWhereNull('parent_id');
//                    });
//            })->getQuery(),
//            'totalInvestorsAmount' => $this->investor
//                ->join('investor_wallets', 'investors.id', '=', 'investor_wallets.investor_id')
//                ->where('investors.id', $this->investor->id),
//            'totalClientsUnpaidAmount' => $this->order->whereIn('orders.vendor_id', function ($query) {
//                $query->select('id')
//                    ->from('vendors')
//                    ->where('id', $this->model->id)
//                    ->orWhere('parent_id', $this->model->id)
//                    ->orWhereNull('parent_id');
//            })->select(DB::raw('orders.id'))
//                ->selectRaw('SUM(required_to_pay) as total_required, COALESCE(SUM(os.paid), 0) as total_paid')
//                ->leftJoin('order_statuses as os', 'orders.id', '=', 'os.order_id')
//                ->groupBy('orders.id')
//                ->get(),
//            'totalClientsPaidAmount' => $this->order->whereIn('orders.vendor_id', function ($query) { // إجمالي المبلغ المسلم
//                $query->select('id')
//                    ->from('vendors')
//                    ->where('id', $this->model->id)
//                    ->orWhere('parent_id', $this->model->id)
//                    ->orWhereNull('parent_id');
//            })->select(DB::raw('orders.id'))
//                ->selectRaw('SUM(required_to_pay) as total_required, COALESCE(SUM(os.paid), 0) as total_paid')
//                ->leftJoin('order_statuses as os', 'orders.id', '=', 'os.order_id')
//                ->groupBy('orders.id')
//                ->get(),
//            'totalUnSurpassedMoneyAmounts' => $this->unsurpassed->query(),
//        ];
//    }
//
//    protected function applyYearFilter(array $data, $year)
//    {
//        if ($year === 'all') return $data;
//
//        $keys = [
//            'branches',
//            'admins',
////            'offices',
//            'vendors',
//            'branches',
////            'stock',
//            'investors',
//            'unsurpasseds',
//            'clients',
//            'orders',
//            'activeSubscriptions',
//            'totalOrdersAmounts',
//            'totalOfficesAmounts',
//            'totalUnSurpassedMoneyAmounts'
//        ];
//
//        foreach ($keys as $key) {
//            if (isset($data[$key])) {
//                $data[$key] = $data[$key]->whereYear('created_at', $year);
//            }
//        }
//
//        return $data;
//    }
//
//    protected function applyMonthFilter(array $data, $month)
//    {
//        if ($month === 'all') return $data;
//
//        $keys = [
////            'offices',
//            'vendors',
//            'branches',
////            'stock',
//            'investors',
//            'unsurpasseds',
//            'clients',
//            'orders',
//            'activeSubscriptions',
//            'totalOrdersAmounts',
//            'totalOfficesAmounts',
//            'totalUnSurpassedMoneyAmounts'
//        ];
//
//        foreach ($keys as $key) {
//            if (isset($data[$key])) {
//                $data[$key] = $data[$key]->whereMonth('created_at', $month);
//            }
//        }
//
//        return $data;
//    }
//
//    protected function calculateTotals(array $data)
//    {
//        return [
//            'totalOrdersAmounts' => $data['totalOrdersAmounts']->sum('required_to_pay') ?? 0,
////            'totalOfficesAmounts' => $data['totalOfficesAmounts']->sum('balance') ?? 0,
//            'totalUnSurpassedMoneyAmounts' => $data['totalUnSurpassedMoneyAmounts']->sum('debt') ?? 0,
//            'totalClientsPaidAmount' => $data['totalClientsPaidAmount']->sum('total_paid') ?? 0,
//            'totalClientsUnpaidAmount' => $data['totalClientsUnpaidAmount']->sum('total_required') - $data['totalClientsUnpaidAmount']->sum('total_paid') ?? 0,
//            'totalInvestorsAmount' => $data['totalInvestorsAmount']->sum('investor_wallets.amount') ?? 0,
//
//        ];
//    }
//
//
//    protected function prepareCounts(array $data)
//    {
//        $vendorQuery = function ($query) {
//            $query->select('id')
//                ->from('vendors')
//                ->where('id', $this->model->id)
//                ->orWhere('parent_id', $this->model->id)
//                ->orWhereNull('parent_id');
//        };
//
//        return [
//            'investors' => $this->investor->whereIn('vendor_id', $vendorQuery)->count(),
//            'unsurpassed' => $this->unsurpassed->whereIn('vendor_id', $vendorQuery)->count(),
//            'clients' => $this->client->whereIn('vendor_id', $vendorQuery)->count(),
//            'orders' => $this->order->whereIn('vendor_id', $vendorQuery)->count(),
//            'branches' => $this->branch->whereIn('vendor_id', $vendorQuery)->count(),
//            'vendors' => $this->vendor->where('parent_id', $this->model->id)->count(),
//        ];
//    }
//
//
//
//
//
//
//
//
//    protected function getStaticData($selectedYear, $selectedMonth)
//    {
//        return [
//            'years' => range(2025, date('Y')),
//            'months' => [
//                '1' => 'يناير',
//                '2' => 'فبراير',
//                '3' => 'مارس',
//                '4' => 'أبريل',
//                '5' => 'مايو',
//                '6' => 'يونيو',
//                '7' => 'يوليو',
//                '8' => 'أغسطس',
//                '9' => 'سبتمبر',
//                '10' => 'أكتوبر',
//                '11' => 'نوفمبر',
//                '12' => 'ديسمبر'
//            ],
//            'selectedYear' => $selectedYear,
//            'selectedMonth' => $selectedMonth,
//        ];
//    }
}





//    protected function prepareCounts(array $data)
//    {
//        return [
//            'investors' => $data['investors']->count(),
//            'unsurpassed' => $data['unsurpasseds']->count(),
//            'clients' => $data['clients']->count(),
//            'orders' => $data['orders']->count(),
//            'branches' => $data['branches']->count(),
////            'admins' => $data['admins']->get()->count(),
////            'offices' => $data['offices']->get()->count(),
////            'vendors' => $data['vendors']->get()->count(),
////            'branches' => $data['branches']->get()->count(),
////            'regions' => $data['regions']->get()->count(),
////            'categories' => $this->category->count(),
////            'stock' => $data['stock']->get()->sum('quantity'),
////            'plans' => $data['plans']->get()->count(),
////            'vendorsWithOutPlans' => $data['vendorsWithOutPlans']->count(),
////            'activeSubscriptions' => $data['activeSubscriptions']->get()->count(),
////            'requestedSubscriptions' => $data['requestedSubscriptions']->get()->count(),
////            'rejectedSubscriptions' => $data['rejectedSubscriptions']->get()->count(),
////            'expiredSubscriptions' => $data['expiredSubscriptions']->get()->count(),
//        ];
//    }
