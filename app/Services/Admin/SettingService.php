<?php

namespace App\Services\Admin;

use App\Models\Setting as ObjModel;
use App\Models\Setting as settingObj;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SettingService extends BaseService
{
    protected string $folder = 'admin/setting';
    protected string $route = 'settings';
    protected SettingObj $settingObj;

    public function __construct(ObjModel $objModel , SettingObj $settingObj)
    {
        $this->settingObj=$settingObj;
        parent::__construct($objModel);
    }

    public function index($request)
    {

        $settings=$this->settingObj->all();
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
            ->editColumn('key', function ($obj) {
                return $obj->key;
            })
            ->editColumn('value', function ($obj) {
                return $obj->value;
            })
                ->addColumn('action', function ($obj) {
                        $buttons = ' ';

                        $buttons .= '
                        <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                    ';          

                        $buttons .= '<button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                            <i class="fas fa-trash"></i>
                        </button>';
                    return $buttons;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'updateRoute' => route("{$this->route}.update", $request),
                'bladeName' => ($this->route),
                'route' => $this->route,
                'settings'=>$settings
            ]);
        }
    }

    public function create()
    {

        return view("{$this->folder}/parts/create", [
            'storeRoute' => route("{$this->route}.store"),

        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleFile($data['image'], 'Setting');
        }

        try {
            $this->createData($data);
            return response()->json(['status' => 200, 'message' => ('Data created successfully.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => ('Something went wrong.'), ('error') => $e->getMessage()]);
        }
    }

    public function edit($obj)
    {
        $settings=$this->settingObj->all();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update"),
            'settings'=>$settings
        ]);
    }

    public function update($data)
    {
        try {
            // Handle app_version
            if (isset($data['app_version'])) {
                $setting = $this->settingObj->where('key', 'app_version')->first();
    
                if ($setting) {
                    // Update if exists
                    $setting->update(['value' => $data['app_version']]);
                } else {
                    // Store if not found
                    $this->settingObj->create([
                        'key' => 'app_version',
                        'value' => $data['app_version']
                    ]);
                }
            }
    
            // Handle about
            if (isset($data['about'])) {
                $setting = $this->settingObj->where('key', 'about')->first();
    
                if ($setting) {
                    // Update if exists
                    $setting->update(['value' => $data['about']]);
                } else {
                    // Store if not found
                    $this->settingObj->create([
                        'key' => 'about',
                        'value' => $data['about']
                    ]);
                }
            }
    
            return response()->json(['status' => 200, 'message' => 'Data stored or updated successfully.']);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ]);
        }
    }
    
}