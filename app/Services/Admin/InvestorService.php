<?php

namespace App\Services\Admin;

use App\Models\Investor as ObjModel;

use App\Services\BaseService;

use Yajra\DataTables\DataTables;

class InvestorService extends BaseService
{
    protected string $folder = 'admin/investor';
    protected string $route = 'admin.investors';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->model->all();
            return DataTables::of($obj)
                ->addColumn('branch', function ($obj) {
                    return  $obj->branch?$obj->branch->name:"غير مرتبط بفرع";
                })
                ->addColumn('office', function ($obj) {

                    return $obj->branch?($obj->branch->vendor->parent_id==null? $obj->branch->vendor->name:$obj->branch->vendor->parent->name):"غير مرتبط بمكتب";
                })

                ->addColumn('vendor', function ($obj) {
                    return $obj->branch?$obj->branch->vendor->name:"غير مرتبط بموظف";


                })

                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'bladeName' => "المستثمرين",
                'route' => $this->route,
            ]);
        }
    }


}
