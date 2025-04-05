<?php

namespace App\Services\Admin;

use App\Models\Setting as ObjModel;
use App\Services\BaseService;

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

            return view($this->folder . '/index', [
                'updateRoute' => route("{$this->route}.update", $request),
                'bladeName' => ($this->route),
                'route' => $this->route,
                'settings'=>$settings
            ]);

    }


    public function update(array $data)
    {
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



            return response()->json(['status' => 200, 'message' => 'تمت العملية بنجاح']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'حدث خطأ أثناء العملية',
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