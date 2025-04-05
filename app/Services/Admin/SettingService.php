<?php

namespace App\Services\Admin;

use App\Models\Setting as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SettingService extends BaseService
{
    protected string $folder = 'admin/setting';
    protected string $route = 'settings';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {

        $settings=$this->model->all();
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
        $settings=$this->model->all();
        return view("{$this->folder}/parts/edit", [
            'obj' => $obj,
            'updateRoute' => route("{$this->route}.update"),
            'settings'=>$settings
        ]);
    }

    public function update(array $data)
    {
//        dd($data);
        try {
            // Handle file uploads
            $files = ['logo', 'fav_icon', 'loader'];
            foreach ($files as $file) {
                if (isset($data[$file])) {
                    $filePath = $data[$file]->store('public/settings');
                    $this->storeOrUpdateSetting($file, basename($filePath));
                }
            }


            if (!empty($data['phones']) && is_array($data['phones'])) {
                $this->model->where('key', 'like', 'phone%')->delete();

                for ($index = 0; $index < count($data['phones']); $index++) {
                    $phone = $data['phones'][$index];
                    // check is phone number is unique
                    $phoneExists = $this->model->where('value', $phone)->exists();
                    if (!$phoneExists) {


                    if (!empty($phone)) {
                        $this->model->updateOrCreate([
                            'key' => 'phone'.$index,
                            'value' => $phone
                        ]);
                    }
                    }
                }
            }



            return response()->json(['status' => 200, 'message' => 'Settings stored or updated successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store or update a setting
     */
    private function storeOrUpdateSetting($key, $value)
    {
        ObjModel::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
