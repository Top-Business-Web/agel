<?php

namespace App\Services\Vendor;

use App\Models\Unsurpassed as ObjModel;
use App\Services\BaseService;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Excel;
use App\Imports\UnsurpassedImport;

class UnsurpassedService extends BaseService
{
    protected string $folder = 'vendor/unsurpassed';
    protected string $route = 'unsurpasseds';

    public function __construct(ObjModel $objModel ,protected Excel $excel, protected UnsurpassedImport $unsurpassedImport)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $buttons = '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    return $buttons;
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

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Unsurpassed');
        }

        try {
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'خطأ' => $e->getMessage()]);

        }
    }
    public function storeExcel($data): \Illuminate\Http\JsonResponse
    {
        try {
            if (isset($data['excel_file'])) {
                $data->validate(['excel_file' => 'required|mimes:xlsx,xls,csv']);
                $file = $data->file('excel_file');
                $this->excel->import($this->unsurpassedImport, $file);
                return response()->json(['status' => 200, 'message' => "تمت العملية بنجاح", 'reload' => true]);
            }

            return response()->json(['status' => 400, 'message' => 'No file uploaded']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'حدث خطأ ما.', 'error' => $e->getMessage()]);
        }
    }

    public function edit($obj)
    {
        $obj['phone'] = str_replace('+966', '', $obj['phone']);
        $obj['office_phone'] = str_replace('+966', '', $obj['office_phone']);
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update", $obj->id),
        ]);
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
