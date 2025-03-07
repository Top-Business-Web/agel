<?php

namespace App\Services\Admin;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission as PermissionObj;
use Spatie\Permission\Models\Role as RoleObj;
use Yajra\DataTables\DataTables;

class RoleService extends BaseService
{
    protected string $folder = 'admin/role';
    protected string $route = 'roles';
    protected RoleObj $roleObj;
    protected PermissionObj $permissionObj;

    public function __construct(PermissionObj $permissionObj,RoleObj $roleObj)
    {
        $this->roleObj=$roleObj;
        $this->permissionObj=$permissionObj;
        parent::__construct($roleObj);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $models = $this->model->all();
            return DataTables::of($models)
                ->addColumn('action', function ($models) {
                    $buttons = '';
                    $buttons .= '

                            <button type="button" data-id="' . $models->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                            </button>
                                                    <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                            data-bs-target="#delete_modal" data-id="' . $models->id . '" data-title="' . $models->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                       ';



                    if ($models->id >8):

                    $buttons .= '<button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                        data-bs-target="#delete_modal" data-id="' . $models->id . '" data-title="' . $models->name . '">
                        <i class="fas fa-trash"></i>
                        </button>';
                    endif;

                    return $buttons;
                })
                ->addColumn('name',function($model){
                    return trns($model->name);
                })
                ->addColumn('permissions', function ($models) {
                    return $models->permissions->count() > 0 ? '<span class="badge badge-success">' .
                       $models->permissions->count() .' '. trns('permissions')
                        . '</span>' :
                        'No Permissions';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route
            ]);
        }
    }

    public function create()
    {
        $permissions = $this->permissionObj->all();
        $roles = $this->roleObj->all();
        return view($this->folder . '/parts/create', [
            'permissions' => $permissions,
            'storeRoute' => route($this->route . '.store'),
            'roles'=>$roles
        ]);
    }


    public function store($data): JsonResponse
    {
        // Ensure $data is an array
        if (!is_array($data)) {
            return response()->json(['status' => 405, 'message' => 'Invalid data format']);
        }
        
        try {
            // Create the role
            $roleData = [
                'name' => $data['name'],
                'guard_name' => $data['guard_name']
            ];
            
            $model = $this->createData($roleData);
    
            if ($model) {
                // Get permission objects
                $permissions = $this->permissionObj->query()
                    ->whereIn('name', $data['permissions'])
                    ->where('guard_name', $data['guard_name'])
                    ->get();
                
                // Check if permissions were found
                if ($permissions->isEmpty()) {
                    \Log::error('No permissions found for: ' . json_encode($data['permissions']) . ' with guard: ' . $data['guard_name']);
                    return response()->json(['status' => 405, 'message' => 'No permissions found']);
                }
                
                // Get permission IDs for direct assignment if needed
                $permissionIds = $permissions->pluck('id')->toArray();
                
                // Try direct sync with IDs first
                $model->permissions()->sync($permissionIds);
                
                // Also try the syncPermissions method as a backup
                $model->syncPermissions($permissions);
                
                // Verify permissions were assigned
                $assignedCount = $model->permissions()->count();
                if ($assignedCount === 0) {
                    \Log::error('Failed to assign permissions to role: ' . $model->name);
                    return response()->json(['status' => 405, 'message' => 'Failed to assign permissions']);
                }
                
                return response()->json(['status' => 200, 'assigned_permissions' => $assignedCount]);
            } else {
                return response()->json(['status' => 405, 'message' => 'Failed to create role']);
            }
        } catch (\Exception $e) {
            \Log::error('Error assigning permissions: ' . $e->getMessage());
            return response()->json(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }


    public function edit($role)
    {
        return view($this->folder . '/parts/edit', [
            'obj' => $role,
            'old_permissions' => $role->permissions()->pluck('name')->toArray(),
            'updateRoute' => route($this->route . '.update', $role->id),
        ]);
    }

    public function update($id, $data)
    {
        try {
            $model = $this->getById($id);
            
            if (!$model) {
                return response()->json(['status' => 404, 'message' => 'Role not found']);
            }
    
            if ($this->updateData($id, $data)) {
                // Get permission objects
                $permissions = $this->permissionObj->query()
                    ->whereIn('name', $data['permissions'])
                    ->where('guard_name', $data['guard_name'])
                    ->get();
                
                // Check if permissions were found
                if ($permissions->isEmpty()) {
                    \Log::error('No permissions found for: ' . json_encode($data['permissions']) . ' with guard: ' . $data['guard_name']);
                    return response()->json(['status' => 405, 'message' => 'No permissions found']);
                }
                
                // Get permission IDs for direct assignment
                $permissionIds = $permissions->pluck('id')->toArray();
                
                // Try direct sync with IDs first
                $model->permissions()->sync($permissionIds);
                
                // Also try the syncPermissions method as a backup
                $model->syncPermissions($permissions);
                
                // Verify permissions were assigned
                $assignedCount = $model->permissions()->count();
                if ($assignedCount === 0) {
                    \Log::error('Failed to assign permissions to role: ' . $model->name);
                    return response()->json(['status' => 405, 'message' => 'Failed to assign permissions']);
                }
                
                return response()->json(['status' => 200, 'assigned_permissions' => $assignedCount]);
            } else {
                return response()->json(['status' => 405, 'message' => 'Failed to update role']);
            }
        } catch (\Exception $e) {
            \Log::error('Error updating role permissions: ' . $e->getMessage());
            return response()->json(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
