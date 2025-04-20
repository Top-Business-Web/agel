<?php



namespace App\Services\Admin;

use App\Models\Branch;

use App\Models\Investor as ObjModel;

use App\Services\BaseService;
use Illuminate\Http\Request;
use App\Models\Vendor;

use Yajra\DataTables\DataTables;

use function Symfony\Component\String\b;

class InvestorService extends BaseService
{
    protected string $folder = 'admin/investor';
    protected string $route = 'admin.investors';

    public function __construct(ObjModel $objModel, protected Vendor $vendor,protected Branch $branch)
    {
        parent::__construct($objModel);
    }

    public function index(Request $request)
    {
        $query = $this->model->query();

        // Apply filters
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->office_id) {
            $query->whereIn('branch_id', $this->branch->where('vendor_id', $request->office_id)->pluck('id'));
        }


        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('branch', function ($obj) {
                   return $obj->branch ? $obj->branch->name : "غير مرتبط بفرع";
                })
                ->addColumn('office', function ($obj) {
                   return $obj->branch->vendor->parent_id !=null? $obj->branch->vendor->parent->name : $obj->branch->vendor->name;
                })


                ->addColumn('vendor', function ($obj) {
                    return $obj->vendor() ? $obj->vendor()->name : "غير مرتبط بموظف";
                })
                ->addColumn('national_id', function ($obj) {
                    return $obj->national_id;
                })
                ->editColumn('phone', function ($obj) {
                    $phone = str_replace('+', '', $obj->phone);
                    return $phone;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        }

        return view($this->folder . '/index', [
            'bladeName' => "المستثمرين",
            'branches' => $this->branch->whereIn('id',$this->model->pluck('branch_id')->unique())->get(),
            'offices' => $this->vendor->whereIn('id', $this->branch->whereIn('id',$this->model->pluck('branch_id')->unique())->pluck('vendor_id')->unique())->where('parent_id', null)->get(),
            'route' => $this->route,
        ]);
    }

}
