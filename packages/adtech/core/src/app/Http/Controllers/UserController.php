<?php

namespace Adtech\Core\App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Adtech\Core\App\Repositories\UserRepository;
use Adtech\Core\App\Http\Requests\UserRequest;
use Adtech\Core\App\Repositories\RoleRepository;
use Adtech\Core\App\Models\Role;
use Adtech\Core\App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Spatie\Activitylog\Models\Activity;
use Auth;


class UserController extends Controller
{
    /**
     * @var UserRepository
     */

    private $messages = array(
        'required' => "Bắt buộc",
        'email' => "Email không chính xác",
        'unique'    => "Đã tồn tại email/username",
        'regex' => "Sai định dạng",
        'max' => "Chuỗi quá dài",
        'min' => "Chuỗi quá ngắn",
        'boolean' => "Sai định dạng",
        'confirmed' => "Xác nhận không chính xác",
    );
    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        parent::__construct();
        $this->user = $userRepository;
        $this->role = $roleRepository;
        $this->_user = Auth::user();
    }

    public function manage(Request $request)
    {
        return view('ADTECH-CORE::modules.core.user.manage');
    }

    public function show(UserRequest $request)
    {
        $user_id = $request->input('user_id');
        $user = $this->user->find($user_id);

        if ($user->permission_locked == 1) {
            return redirect()->route('adtech.core.user.manage')->with('error', trans('adtech-core::messages.error.permission'));
        }
        $groups = Role::all();

        return view('modules.core.user.edit', compact('user', 'groups'));
    }

    public function add(UserRequest $request)
    {
        $user = new User($request->except('_token', 'password_confirm', 'group', 'activate'));
        $user->password = Hash::make($request->password);
        $user->status = ($request->has('status')) ? 1 : 0;
        $user->permission_locked = ($request->has('permission_locked')) ? 1 : 0;
        $user->save();

        if ($user->user_id) {
            $user_id = $user->user_id;
            $role_id = $request->input('group');
            DB::insert('insert into adtech_core_users_role (user_id, role_id, created_at, updated_at) values (?, ?, ?, ?)',
                [$user_id, $role_id, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);

            activity('user')
                ->performedOn($user)
                ->withProperties($request->all())
                ->log('User: :causer.email - Add User - contact_name: :properties.contact_name, user_id: ' . $user->user_id);

            return redirect()->route('adtech.core.user.manage')->with('success', trans('adtech-core::messages.success.create'));
        } else {
            return redirect()->route('adtech.core.user.manage')->with('error', trans('adtech-core::messages.error.create'));
        }
    }

    public function update(UserRequest $request)
    {
        $user_id = $request->input('user_id');

        $user = $this->user->find($user_id);
        $user->contact_name = $request->input('contact_name');
        if ( !empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->status = ($request->has('status')) ? 1 : 0;
        $user->permission_locked = ($request->has('permission_locked')) ? 1 : 0;

        if ($user->save()) {
            $role_id = $request->input('groups');
            $user_role_item = DB::select('select * from adtech_core_users_role where user_id = :id', ['id' => $user_id]);
            if (null == $user_role_item) {
                DB::insert('insert into adtech_core_users_role (user_id, role_id, created_at, updated_at) values (?, ?, ?, ?)',
                    [$user_id, $role_id, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            } else {
                DB::update('update adtech_core_users_role set role_id = ?, updated_at = ? where user_id = ?', [$role_id, date('Y-m-d H:i:s'), $user_id]);
            }

            activity('user')
                ->performedOn($user)
                ->withProperties($request->all())
                ->log('User: :causer.email - Update User - user_id: :properties.user_id, contact_name: :properties.contact_name, status: :properties.status');

            return redirect()->route('adtech.core.user.manage')->with('success', trans('adtech-core::messages.success.update'));
        } else {
            return redirect()->route('adtech.core.user.show', ['role_id' => $request->input('user_id')])->with('error', trans('adtech-core::messages.error.update'));
        }
    }

    public function create()
    {
        $groups = Role::where('status', 1)->get();
        return view('modules.core.user.create', compact('groups'));
    }

    public function delete(UserRequest $request)
    {
        $user_id = $request->input('user_id');
        $user = $this->user->find($user_id);

        if ($user->permission_locked == 1) {
            return redirect()->route('adtech.core.user.manage')->with('error', trans('adtech-core::messages.error.permission'));
        }

        if (null != $user) {

            $user_role_item = DB::select('select * from adtech_core_users_role where user_id = :id', ['id' => $user_id]);
            if (null != $user_role_item) {
                DB::table('adtech_core_users_role')->where('user_id', $user_id)->delete();
            }

            $this->user->deleteID($user_id);

            activity('user')
                ->performedOn($user)
                ->withProperties($request->all())
                ->log('User: :causer.email - Delete User - user_id: :properties.user_id, email: ' . $user->email);

            return redirect()->route('adtech.core.user.manage')->with('success', trans('adtech-core::messages.success.delete'));
        } else {
            return redirect()->route('adtech.core.user.manage')->with('error', trans('adtech-core::messages.error.delete'));
        }
    }

    public function getModalDelete(UserRequest $request)
    {
        $model = 'user';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('adtech.core.user.delete', ['user_id' => $request->input('user_id')]);
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {

//                $error = trans('news/message.error.destroy', compact('id'));
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function log(Request $request)
    {
        $model = 'user';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $logs = Activity::where([
                    ['log_name', $model],
                    ['subject_id', $request->input('id')]
                ])->get();
                return view('includes.modal_table', compact('error', 'model', 'confirm_route', 'logs'));
            } catch (GroupNotFoundException $e) {
                return view('includes.modal_table', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    //Table Data to index page
    public function data()
    {
        $role_id = $this->_user->role_id;
        $role = $this->role->find($role_id);
        $users = User::with('roles')
            ->whereHas('roles', function ($query) use ($role) {
                $query->where('adtech_core_roles.sort', '>=', $role->sort);
            });
        return Datatables::of($users)
            ->addColumn('role_name', function($users) {
                return $users->roles[0]->name;
            })
            ->editColumn('status', function ($users) {
                if ($users->status == 1) {
                    $status = '<span class="label label-sm label-success">Enable</span>';
                } else {
                    $status = '<span class="label label-sm label-danger">Disable</span>';
                }
                return $status;
            })
            ->editColumn('email', function ($users) {
                if ($users->permission_locked == 1) {
                    return $users->email;
                } else {
                    if ($this->_user->canAccess('adtech.core.permission.manage', ['object_type' => 'user', 'user_id' => $users->user_id])) {
                        return $actions = '<a href=' . route('adtech.core.permission.manage', ['object_type' => 'user', 'user_id' => $users->user_id]) . '>' . $users->email . '</a>';
                    } else {
                        return $users->email;
                    }
                }
            })
            ->addColumn('actions', function ($users) {
                $actions = '';
                if ($users->permission_locked != 1) {
                    if ($this->_user->canAccess('adtech.core.user.log', ['object_type' => 'user', 'user_id' => $users->user_id])) {
                        $actions .= '<a href=' . route('adtech.core.user.log', ['type' => 'user', 'id' => $users->user_id]) . ' data-toggle="modal" data-target="#log"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#F99928" data-hc="#F99928" title="Log User"></i></a>';
                    }
                    if ($this->_user->canAccess('adtech.core.permission.manage', ['object_type' => 'user', 'user_id' => $users->user_id])) {
                        $actions .= '<a href=' . route('adtech.core.permission.manage', ['object_type' => 'user', 'user_id' => $users->user_id]) . '><i class="livicon" data-name="gear" data-size="18" data-loop="true" data-c="#6CC66C" data-hc="#6CC66C" title="add Role"></i></a>';
                    }
                    if ($this->_user->canAccess('adtech.core.user.show', ['user_id' => $users->user_id])) {
                        $actions .= '<a href=' . route('adtech.core.user.show', ['user_id' => $users->user_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update User"></i></a>';
                    }
                    if ($this->_user->canAccess('adtech.core.user.confirm-delete', ['user_id' => $users->user_id])) {
                        $actions .= '<a href=' . route('adtech.core.user.confirm-delete', ['user_id' => $users->user_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete User"></i></a>';
                    }
                }

                return $actions;
            })
            ->rawColumns(['actions', 'email', 'status'])
            ->make();
    }
}
