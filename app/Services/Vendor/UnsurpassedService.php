<?php

namespace App\Services\Vendor;

use App\Exports\UnsurpassedExampleExport;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Unsurpassed as ObjModel;
use App\Models\Vendor;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Imports\UnsurpassedImport;
use Maatwebsite\Excel\Facades\Excel;


class UnsurpassedService extends BaseService
{
    protected string $folder = 'vendor/unsurpassed';
    protected string $route = 'unsurpasseds';

    public function __construct(ObjModel $objModel, protected Excel $excel, protected UnsurpassedImport $unsurpassedImport, protected Client $client, protected Vendor $vendor, protected Branch $branch)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {

            $unsurpassed = ObjModel::query()->select('*', DB::raw("'unsurpassed' as model_type"));
            $vendorId = auth('vendor')->user()->parent_id !== null ? auth('vendor')->user()->parent_id : auth('vendor')->user()->id;

            $clients = $this->client->whereHas('orders', function ($query) use ($vendorId) {
//                $query->where('vendor_id', $vendorId)
                  $query  ->whereHas('order_status', function ($q) {
                        $q->whereNotIn('status', [3]);
                    });
            })->select('*', DB::raw("'client' as model_type"));

            $obj = $unsurpassed->unionAll($clients)->get();

            if ($request->filled('national_id')) {
                $obj = $obj->filter(function($item) use ($request) {
                    return $item->national_id === $request->national_id;
                });
            }

            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $branch = $this->branch->where('id', $obj->office_phone)->first();// i use office_phone because unionAll rename cols
                    if ($branch) {
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();

                    } else {


                        $vendorId = null;
                    }

                    if ($obj->model_type === 'unsurpassed' && $obj->office_phone === auth('vendor')->user()->phone) {

                        $buttons = '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    } else {
                        return '<h5 class="text-muted">لايمكنك اتخاد اي احراء</h5>';

                    }

                    return $buttons;
                })->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })->addColumn('office_phone', function ($obj) {


                    if ($obj->model_type === 'client') {

                        $branch = $this->branch->where('id', $obj->office_phone)->first();// i use office_phone because unionAll rename cols
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();
                    }


                    $phone = str_replace('+', '', $obj->model_type === 'client' ? $vendor->phone : $obj->office_phone);
                    return $phone;
                })->addColumn('office_name', function ($obj) {

                    if ($obj->model_type === 'client') {

                        $branch = $this->branch->where('id', $obj->office_phone)->first();// i use office_phone because unionAll rename cols
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();
                    }
                    return $obj->model_type === 'client' ? $vendor->name : $obj->office_name;
                })->addColumn('client_status', function ($obj) {

                    if ($obj->model_type === 'client') {
                        $order = $this->client->find($obj->id);
                        $orders = $order->orders ?? [];
                        $orderStatuses = [];
                        $orderDates = [];

                        foreach ($orders as $order) {
                            $orderStatuses[] = $order->order_status->status;
                            $orderDates[] = $order->order_status->date;
                        }

                        if (array_intersect([0, 1], $orderStatuses) && now()->greaterThan(min($orderDates))) {
                            return "<h5 class='text-warning'>متعثر</h5>";
                        } elseif (array_intersect([0, 1], $orderStatuses) && now()->lessThan(min($orderDates))) {
                            return "<h5 class='text-break'>لديه طلب قائم</h5>";
                        } elseif
                        (array_intersect([3], $orderStatuses) && now()->greaterThan(min($orderDates))) {
                            return "<h5 class='text-primary'>غير منتظم في السداد</h5>";
                        } elseif (in_array(3, $orderStatuses) && !array_intersect([1, 2, 0], $orderStatuses)) {
                            return "<h5 class='text-success'>منتظم في السداد</h5>";
                        } else {
                            return "<h5 class='text-muted'>غير معلن </h5>";
                        }
                    }

                    return '<h5 class="text-muted">ليس لديه طلبات</h5>';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'addExcelRoute' => route($this->route . '.add.excel'),
                'bladeName' => "",
                'route' => $this->route,
                'obj' => $this->model->get(),
            ]);
        }
    }
    public function myUnsurpassed($request)
    {
        if ($request->ajax()) {
            $vendorId = auth('vendor')->user()->parent_id !== null ? auth('vendor')->user()->parent_id : auth('vendor')->user()->id;
            $unsurpassed = ObjModel::query()->where('office_phone',$this->vendor->where('id',$vendorId)->first()->phone)->select('*', DB::raw("'unsurpassed' as model_type"));

            $clients = $this->client->whereHas('orders', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId)
                    ->whereHas('order_status', function ($q) {
                        $q->whereNotIn('status', [3]);
                    });
            })->select('*', DB::raw("'client' as model_type"));

            $obj = $unsurpassed->unionAll($clients)->get();


            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $branch = $this->branch->where('id', $obj->office_phone)->first();// i use office_phone because unionAll rename cols
                    if ($branch) {
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();

                    } else {


                        $vendorId = null;
                    }

                    if ($obj->model_type === 'unsurpassed' && $obj->office_phone === auth('vendor')->user()->phone) {

                        $buttons = '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    } else {
                        return '<h5 class="text-muted">لايمكنك اتخاد اي احراء</h5>';

                    }

                    return $buttons;
                })->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })->addColumn('office_phone', function ($obj) {


                    if ($obj->model_type === 'client') {

                        $branch = $this->branch->where('id', $obj->office_phone)->first();// i use office_phone because unionAll rename cols
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();
                    }


                    $phone = str_replace('+', '', $obj->model_type === 'client' ? $vendor->phone : $obj->office_phone);
                    return $phone;
                })->addColumn('office_name', function ($obj) {

                    if ($obj->model_type === 'client') {

                        $branch = $this->branch->where('id', $obj->office_phone)->first();// i use office_phone because unionAll rename cols
                        $vendor = $this->vendor->where('id', $branch->vendor_id)->first();
                    }
                    return $obj->model_type === 'client' ? $vendor->name : $obj->office_name;
                })->addColumn('client_status', function ($obj) {

                    if ($obj->model_type === 'client') {
                        $order = $this->client->find($obj->id);
                        $orders = $order->orders ?? [];
                        $orderStatuses = [];
                        $orderDates = [];

                        foreach ($orders as $order) {
                            $orderStatuses[] = $order->order_status->status;
                            $orderDates[] = $order->order_status->date;
                        }

                        if (array_intersect([0, 1], $orderStatuses) && now()->greaterThan(min($orderDates))) {
                            return "<h5 class='text-warning'>متعثر</h5>";
                        } elseif (array_intersect([0, 1], $orderStatuses) && now()->lessThan(min($orderDates))) {
                            return "<h5 class='text-break'>لديه طلب قائم</h5>";
                        } elseif
                        (array_intersect([3], $orderStatuses) && now()->greaterThan(min($orderDates))) {
                            return "<h5 class='text-primary'>غير منتظم في السداد</h5>";
                        } elseif (in_array(3, $orderStatuses) && !array_intersect([1, 2, 0], $orderStatuses)) {
                            return "<h5 class='text-success'>منتظم في السداد</h5>";
                        } else {
                            return "<h5 class='text-muted'>غير معلن </h5>";
                        }
                    }

                    return '<h5 class="text-muted">ليس لديه طلبات</h5>';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'createRoute' => route($this->route . '.create'),
                'addExcelRoute' => route($this->route . '.add.excel'),
                'bladeName' => "",
                'route' => $this->route,
                'obj' => $this->model->get(),
            ]);
        }
    }


//    public function myUnsurpassed($request)
//    {
//        if ($request->ajax()) {
//            $vendorId = auth('vendor')->user()->parent_id ?? auth('vendor')->user()->id;
//
//            $unsurpassed = $this->client->whereHas('orders', function ($query) use ($vendorId) {
//                $query->where('vendor_id', $vendorId)
//                    ->whereHas('order_status', function ($q) {
//                        $q->whereNotIn('status', [3]);
//                    });
//            })->get();
//
//
//            return DataTables::of($unsurpassed)
//                ->editColumn('phone', function ($obj) {
//                    $phone = str_replace('+', '', $obj->phone);
//                    return $phone;
//                })->addColumn('office_phone', function ($obj) {
//                    $vendor = $this->vendor->where('id', auth('vendor')->user()->parent_id ?? auth('vendor')->user()->id)->first();
//
//                    $phone = str_replace('+', '', $vendor->phone);
//                    return $phone;
//                })->addColumn('office_name', function ($obj) {
//                    $vendor = $this->vendor->where('id', auth('vendor')->user()->parent_id ?? auth('vendor')->user()->id)->first();
//                    return $vendor->name;
//                })->addColumn('client_status', function ($obj) {
//
//                    $orders = $obj->orders ?? [];
//                    $orderStatuses = [];
//                    $orderDates = [];
//
//                    foreach ($orders as $order) {
//                        $orderStatuses[] = $order->order_status->status;
//                        $orderDates[] = $order->order_status->date;
//                    }
//
//                    if (array_intersect([0, 1], $orderStatuses) && now()->greaterThan(min($orderDates))) {
//                        return "<h5 class='text-warning'>متعثر</h5>";
//                    } elseif (array_intersect([0, 1], $orderStatuses) && now()->lessThan(min($orderDates))) {
//                        return "<h5 class='text-break'>لديه طلب قائم</h5>";
//                    } elseif
//                    (array_intersect([3], $orderStatuses) && now()->greaterThan(min($orderDates))) {
//                        return "<h5 class='text-primary'>غير منتظم في السداد</h5>";
//                    } elseif (in_array(3, $orderStatuses) && !array_intersect([1, 2, 0], $orderStatuses)) {
//                        return "<h5 class='text-success'>منتظم في السداد</h5>";
//                    } else {
//                        return "<h5 class='text-muted'>غير معلن </h5>";
//                    }
//
//
//                })
//                ->addIndexColumn()
//                ->escapeColumns([])
//                ->make(true);
//        } else {
//            return view($this->folder . '/index', [
//                'createRoute' => route($this->route . '.create'),
//                'addExcelRoute' => route($this->route . '.add.excel'),
//                'bladeName' => "",
//                'route' => $this->route,
//                'obj' => $this->model->get(),
//            ]);
//        }
//    }

    public function create()
    {
        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),
        ]);
    }

    public function addExcel()
    {
        return view("{$this->folder}/parts/addExcel", [
            'storeExcelRoute' => route("{$this->route}.store.excel"),
        ]);
    }

    public function show()
    {

    }

    public function store($data): \Illuminate\Http\JsonResponse
    {

        try {
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);

        }
    }

    public function storeExcel($data): \Illuminate\Http\JsonResponse
    {
//        try {
        if (isset($data['excel_file'])) {
            $data->validate(['excel_file' => 'required|mimes:xlsx,xls,csv']);
            $file = $data->file('excel_file');

            Excel::import($this->unsurpassedImport, $file);

            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح", 'reload' => true]);
        }
        return response()->json('error importing the excel file');
//        } catch (\Exception $e) {
//            return response()->json('error importing the excel file');
//        }
    }

    public function edit($obj)
    {
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
        ]);
    }

    public function downloadExample()
    {
        return \Excel::download(new UnsurpassedExampleExport, 'unsurpassed_example.xlsx');
    }

    public function update($data, $id)
    {
        $oldObj = $this->getById($id);

        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Unsurpassed');

            if ($oldObj->image) {
                $this->deleteFile($oldObj->image);
            }
        }

        try {
            $oldObj->update($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);

        }


    }
}
