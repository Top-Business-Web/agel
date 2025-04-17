<?php

namespace App\Services\Admin;

use App\Services\BaseService;

use Yajra\DataTables\DataTables;

use App\Models\Category as ObjModel;
use App\Models\Vendor;

class CategoryService extends BaseService
{
    protected string $folder = 'admin/category';
    protected string $route = 'admin.categories';

    public function __construct(ObjModel $objModel,protected Vendor $vendor)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)


                ->addColumn('name', function ($obj) {
                    return $obj->name;
                })
                ->addColumn('vendor', function ($obj) {
                    return $this->vendor->parent_id == null ? $obj->vendor->name : $obj->vendor->parent->name;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'bladeName' => "الأصناف",
                'route' => $this->route,
                'vendors'=>$this->vendor->where('parent_id', null)->get(),
            ]);
        }
    }




}
