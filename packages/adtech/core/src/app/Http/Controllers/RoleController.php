<?php

namespace Adtech\Core\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Adtech\Core\App\Repositories\RoleRepository;
use Adtech\Core\App\Http\Requests\RoleRequest;
use Adtech\Core\App\Models\Role;
use Yajra\Datatables\Datatables;
use Validator;

class RoleController extends Controller
{
    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct();
        $this->role = $roleRepository;
    }

    public function add(RoleRequest $request)
    {
        $roles = new Role($request->all());
        if ($request->has('permission_locked')) {
            $roles->permission_locked = 1;
        }
        $roles->save();

        if ($roles->role_id) {
            return redirect()->route('adtech.core.role.manage')->with('success', trans('adtech-core::messages.success.create'));
        } else {
            return redirect()->route('adtech.core.role.manage')->with('error', trans('adtech-core::messages.error.create'));
        }
    }

    public function create()
    {
        return view('modules.core.role.create');
    }

    public function delete(RoleRequest $request)
    {
        $role_id = $request->input('role_id');
        $role = $this->role->find($role_id);

        if ($role->permission_locked == 1) {
            return redirect()->route('adtech.core.role.manage')->with('error', trans('adtech-core::messages.error.permission'));
        }

        if ($role->delete()) {
            return redirect()->route('adtech.core.role.manage')->with('success', trans('adtech-core::messages.success.delete'));
        } else {
            return redirect()->route('adtech.core.role.manage')->with('error', trans('adtech-core::messages.error.delete'));
        }
    }

    public function manage()
    {
        return view('ADTECH-CORE::modules.core.role.manage');
    }

    public function show(RoleRequest $request)
    {
        $role_id = $request->input('role_id');
        $role = $this->role->find($role_id);
        $data = [
            'role' => $role
        ];

        if ($role->permission_locked == 1) {
            return redirect()->route('adtech.core.role.manage')->with('error', trans('adtech-core::messages.error.permission'));
        }

        return view('modules.core.role.edit', $data);
    }

    public function update(RoleRequest $request)
    {
        $role_id = $request->input('role_id');

        $role = $this->role->find($role_id);
        $role->name = $request->input('name');
        $role->status = ($request->has('status')) ? 1 : 0;
        $role->permission_locked = ($request->has('permission_locked')) ? 1 : 0;

        if ($role->save()) {
            return redirect()->route('adtech.core.role.manage')->with('success', trans('adtech-core::messages.success.update'));
        } else {
            return redirect()->route('adtech.core.role.show', ['role_id' => $request->input('role_id')])->with('error', trans('adtech-core::messages.error.update'));
        }
    }

    public function getModalDelete(RoleRequest $request)
    {
        $model = 'role';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('adtech.core.role.delete', ['role_id' => $request->input('role_id')]);
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {

//                $error = trans('news/message.error.destroy', compact('id'));
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    //Table Data to index page
    public function data()
    {
        $roles = Role::all();

        return Datatables::of($roles)
            ->editColumn('status', function ($roles) {
                if ($roles->status == 1) {
                    $status = '<span class="label label-sm label-success">Enable</span>';
                } else {
                    $status = '<span class="label label-sm label-danger">Disable</span>';
                }
                return $status;
            })
            ->editColumn('name', function ($roles) {
                if ($roles->permission_locked == 1) {
                    return $roles->name;
                } else {
                    return $actions = '<a href=' . route('adtech.core.permission.manage', ['object_type' => 'role', 'role_id' => $roles->role_id]) . '>' . $roles->name . '</a>';
                }
            })
            ->addColumn('actions', function ($roles) {
                if ($roles->permission_locked == 1) {
                    $actions = '';
                } else {
                    $actions = '<a href=' . route('adtech.core.permission.manage', ['object_type' => 'role', 'role_id' => $roles->role_id]) . '><i class="livicon" data-name="gear" data-size="18" data-loop="true" data-c="#6CC66C" data-hc="#6CC66C" title="add Role"></i></a>
                            <a href=' . route('adtech.core.role.show', ['role_id' => $roles->role_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update News"></i></a>
                            <a href=' . route('adtech.core.role.confirm-delete', ['role_id' => $roles->role_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete Roles"></i></a>';
                }

                return $actions;
            })
            ->rawColumns(['actions', 'name', 'status'])
            ->make();
    }
}
