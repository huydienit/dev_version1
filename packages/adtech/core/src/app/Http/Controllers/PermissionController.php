<?php

namespace Adtech\Core\App\Http\Controllers;

use Adtech\Core\App\Repositories\PackageRepository;
use Adtech\Core\App\Repositories\RoleRepository;
use Adtech\Core\App\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Adtech\Core\App\Models\Acl;
use Illuminate\Http\Request;
use Adtech\Core\App\Models\Package;
use Illuminate\Support\Collection;
use Yajra\Datatables\Datatables;
use Validator;
use Auth;
use Adtech\Application\Cms\Controllers\Controller as Controller;

class PermissionController extends Controller
{
    /**
     * @var UserRepository
     */
    private $_userRepository;

    /**
     * @var RoleRepository
     */
    private $_roleRepository;
    private static $_aclKey = 'ADTECH_CMS_ACL_RULES';

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, PackageRepository $packageRepository)
    {
        parent::__construct();
        $this->_userRepository = $userRepository;
        $this->_roleRepository = $roleRepository;
        $this->package = $packageRepository;
    }

    public function manage(Request $request, $objectType, $objectId)
    {
        $object = null;
        $objectType = strtolower($objectType);
        switch ($objectType) {
            case 'role':
                $object = $this->_roleRepository->getById($objectId);
                break;
            case 'group':
                break;
            case 'user':
                $object = $this->_userRepository->getById($objectId);
                break;
        }

        if (null == $object) {
            abort(404);
        }

        if ($object->permission_locked == true) {
            abort(403);
        }

        $titleP =  (isset($object->email)) ? 'User: ' . $object->email : 'Role: ' . $object->name;
        $packages = Package::select('package')->distinct()->get();

        $data = [
            'titleP',
            'objectType',
            'objectId',
            'packages'
        ];

        return view('ADTECH-CORE::modules.core.permission.manage', compact($data));
    }

    public function manageMore(Request $request, $objectType, $objectId)
    {
        $object = null;
        $objectType = strtolower($objectType);
        switch ($objectType) {
            case 'role':
                $object = $this->_roleRepository->getById($objectId);
                break;
            case 'group':
                break;
            case 'user':
                $object = $this->_userRepository->getById($objectId);
                break;
        }

        if (null == $object) {
            abort(404);
        }

        if ($object->permission_locked == true) {
            abort(403);
        }
        $titleP =  (isset($object->email)) ? 'User: ' . $object->email : 'Role: ' . $object->name;

        $data = [
            'titleP',
            'objectType',
            'objectId'
        ];

        return view('ADTECH-CORE::modules.core.permission.manage_more', compact($data));
    }

    public function set(Request $request)
    {
        $result = [];
        $result['type'] = 'error';
        $result['msg'] = 'Update Permission Fail';

        $user_id = Auth::id();
        $objectType = $request->input('object_type', 'role');
        $objectId = (int)$request->input('object_id');
        $allow = (int)$request->input('allow');
        $object = null;

        switch ($objectType) {
            case 'role':
                $object = $this->_roleRepository->getById($objectId);
                break;
            case 'group':
                break;
            case 'user':
                $object = $this->_userRepository->getById($objectId);
                break;
        }

        if (null == $object) {
            abort(404);
        }

        $domain_id = $this->currentDomain->domain_id;
        $route_name = $request->input('route_name');
        $route_name_crc = abs(crc32($route_name));
        $arrName = explode('.', $route_name);
        
        $acl = Acl::where('object_type', $objectType)
            ->where('object_id', $objectId)
            ->where('domain_id', $domain_id)
            ->where('route_name_crc', $route_name_crc)->first();

        if (null == $acl) {
            $acl = new Acl();
        }
        
        $acl->object_id = $objectId;
        $acl->object_type = $objectType;
        $acl->domain_id = $domain_id;
        $acl->allow = $allow;
        $acl->route_name = $route_name;
        $acl->route_name_crc = $route_name_crc;
        $acl->created_user_id = $user_id;
        $acl->created_at = date('Y-m-d H:i:s');
        $acl->updated_at = date('Y-m-d H:i:s');
        $acl->vendor = $arrName[0];
        $acl->package = $arrName[1];
        $acl->params = '';
        if ($acl->save()) {
            $cache = Cache::store('file');
            $cache->forget(self::$_aclKey);
            $columns = [
                DB::raw('CONCAT(`object_type`, \'_\', `object_id`) as `role_name`'),
                'allow', 'route_name', 'domain_id'
            ];
            $data = Acl::select($columns)
                ->where('route_name_crc', '=', '0')
                ->union(Acl::select($columns)
                    ->where('route_name_crc', '<>', '0'))
                ->get();
            $data && $cache->forever(self::$_aclKey, $data);

            $result['type'] = 'success';
            $result['group'] = 'Permission';
            $result['msg'] = 'Update Permission Successfull';
        }

        if ($request->has('route_name1')) {
            $route_name = $request->input('route_name1');
            $route_name_crc = abs(crc32($route_name));
            $arrName = explode('.', $route_name);

            $acl = Acl::where('object_type', $objectType)
                ->where('object_id', $objectId)
                ->where('route_name_crc', $route_name_crc)->first();

            if (null == $acl) {
                $acl = new Acl();
            }

            $acl->object_id = $objectId;
            $acl->object_type = $objectType;
            $acl->domain_id = $domain_id;
            $acl->allow = $allow;
            $acl->route_name = $route_name;
            $acl->route_name_crc = $route_name_crc;
            $acl->created_user_id = $user_id;
            $acl->created_at = date('Y-m-d H:i:s');
            $acl->updated_at = date('Y-m-d H:i:s');
            $acl->vendor = $arrName[0];
            $acl->package = $arrName[1];
            $acl->params = '';
            if ($acl->save()) {
                $cache = Cache::store('file');
                $cache->forget(self::$_aclKey);
                $columns = [
                    DB::raw('CONCAT(`object_type`, \'_\', `object_id`) as `role_name`'),
                    'allow', 'route_name', 'domain_id'
                ];
                $data = Acl::select($columns)
                    ->where('route_name_crc', '=', '0')
                    ->union(Acl::select($columns)
                        ->where('route_name_crc', '<>', '0'))
                    ->get();
                $data && $cache->forever(self::$_aclKey, $data);

                $result['type'] = 'success';
                $result['group'] = 'Permission';
                $result['msg'] = 'Update Permission Successfull';
            }
        }
        return $result;
    }

    public function setName(Request $request)
    {
        $name = $request->input('name', null);
        $name_value = $request->input('name_value', null);

        $pathControllerName = base_path('controller.json');
        $controllerFile = file_get_contents($pathControllerName);
        $controllerObject = json_decode($controllerFile, true);

        $controllerObject[$name] = $name_value;
        if (file_put_contents($pathControllerName, str_replace('\/', '/', json_encode($controllerObject)))) {
            $result['type'] = 'success';
            $result['group'] = 'Controller Name';
            $result['msg'] = 'Set Name Successfull';
        } else {
            $result['type'] = 'error';
            $result['group'] = 'Controller Name';
            $result['msg'] = 'Set Name Failed';
        }
        return $result;
    }

    //Table Data to index page
    public function data(Request $request)
    {
        $pathControllerName = base_path('controller.json');
        $controllerFile = file_get_contents($pathControllerName);
        $controllerObject = json_decode($controllerFile, true);

        $module_select = $package_select = null;
        $package_id = $request->input('package_id', null);
        $module_id = $request->input('module_id', null);
        if (!empty($package_id)) {
            $package_select = $package_id;
        }
        if (!empty($module_id) && is_numeric($module_id)) {
            $package = $this->package->find($module_id);
            if (null != $package) {
                $module_select = $package->module;
            }
        }

        //user-role-group data
        $object_data = null;
        $objectType = strtolower($request->input('object_type'));
        switch ($objectType) {
            case 'role':
                $object_data = $this->_roleRepository->getById($request->input('object_id'));
                break;
            case 'group':
                break;
            case 'user':
                $object_data = $this->_userRepository->getById($request->input('object_id'));
                break;
        }

        if (null == $object_data) {
            abort(404);
        }

        if ($object_data->permission_locked == true) {
            abort(403);
        }

        //route
        $app = app();
        $list = $listName = $listController = [];
        $routes = $app->routes->getRoutes();
        foreach ($routes as $route) {

            if (isset($route->action['as']) && (isset($route->action['controller']))) {
                $route_name = explode('.' , $route->action['as']);
                if (count($route_name) > 2) {
                    $package = $route_name[0];
                    $module = $route_name[1];
                    $name = (isset($route->action['as'])) ? $route->action['as'] : 'N/A';
                    $actions = substr($route->action['controller'], strrpos($route->action['controller'], "\\") + 1, strlen($route->action['controller']));
                    $controller = substr($actions, 0, strpos($actions, '@'));
                    $action = substr($actions, strrpos($actions, '@') + 1, strlen($actions));
                    if ($package_select != null && $module_select != null) {
                        if ($package != $package_select || $module != $module_select) {
                            continue;
                        }
                    } elseif ($package_select != null) {
                        if ($package != $package_select) {
                            continue;
                        }
                    }
                    $list[$package][$module][$controller] = (isset($list[$package][$module][$controller])) ? $list[$package][$module][$controller] . ',' . $action : $action;
                    $listName[$package][$module][$controller] = (isset($listName[$package][$module][$controller])) ? $listName[$package][$module][$controller] . ',' . $name : $name;
                }
            }
        }

        $listMethod = ['data', 'manage', 'create', 'add', 'show', 'update', 'delete', 'getModalDelete'];
        $listNameYC = ['data', 'manage', 'create', 'add', 'show', 'update', 'delete', 'confirm-delete'];
        if (count($list) > 0) {
            foreach ($list as $package => $moduleList) {
                foreach ($moduleList as $module => $controllers) {
                    foreach ($controllers as $controller => $methods) {

                        $nameList = explode(',', $listName[$package][$module][$controller]);
                        $methodsList = explode(',', $methods);
                        if (empty(array_diff($listMethod, $methodsList))) {

                            $route_name = substr($nameList[0], 0, strrpos($nameList[0], '.') + 1);
                            if ($object_data->canAccess($route_name . $listNameYC[0]) && $object_data->canAccess($route_name . $listNameYC[1])) {
                                $method_view = '<input type="checkbox" class="allow_permission" data-size="mini" data-name="'.$route_name . $listNameYC[0].'" data-name1="'.$route_name . $listNameYC[1].'" checked>';
                            } else {
                                $method_view = '<input type="checkbox" class="allow_permission" data-size="mini" data-name="'.$route_name . $listNameYC[0].'" data-name1="'.$route_name . $listNameYC[1].'">';
                            }
                            if ($object_data->canAccess($route_name . $listNameYC[2]) && $object_data->canAccess($route_name . $listNameYC[3])) {
                                $method_add = '<input type="checkbox" class="allow_permission" data-size="mini" data-name="'.$route_name . $listNameYC[2].'" data-name1="'.$route_name . $listNameYC[3].'" checked>';
                            } else {
                                $method_add = '<input type="checkbox" class="allow_permission" data-size="mini" data-name="'.$route_name . $listNameYC[2].'" data-name1="'.$route_name . $listNameYC[3].'">';
                            }
                            if ($object_data->canAccess($route_name . $listNameYC[4]) && $object_data->canAccess($route_name . $listNameYC[5])) {
                                $method_update = '<input type="checkbox" class="allow_permission" data-size="mini" data-name="'.$route_name . $listNameYC[4].'" data-name1="'.$route_name . $listNameYC[5].'" checked>';
                            } else {
                                $method_update = '<input type="checkbox" class="allow_permission" data-size="mini" data-name="'.$route_name . $listNameYC[4].'" data-name1="'.$route_name . $listNameYC[5].'">';
                            }
                            if ($object_data->canAccess($route_name . $listNameYC[6]) && $object_data->canAccess($route_name . $listNameYC[7])) {
                                $method_delete = '<input type="checkbox" class="allow_permission" data-size="mini" data-name="'.$route_name . $listNameYC[6].'" data-name1="'.$route_name . $listNameYC[7].'" checked>';
                            } else {
                                $method_delete = '<input type="checkbox" class="allow_permission" data-size="mini" data-name="'.$route_name . $listNameYC[6].'" data-name1="'.$route_name . $listNameYC[7].'">';
                            }
                            $method = '<table>
                            <tr>
                                <td class="col-md-3">' . $method_view . '</td>
                                <td class="col-md-3">' . $method_add . '</td>
                                <td class="col-md-3">' . $method_update . '</td>
                                <td class="col-md-3">' . $method_delete . '</td>
                            </tr>
                            </table>';

                            if (!isset($controllerObject[$package.'-'.$module.'-'.$controller])) {
                                $controllerObject[$package.'-'.$module.'-'.$controller] = $controller;
                            } else {
                                $controller = $controllerObject[$package.'-'.$module.'-'.$controller];
                            }

                            $listController[] = [
                                'package' => $package,
                                'module' => $module,
                                'controller' => $controller,
                                'method' => $method
                            ];
                        }
                    }
                }
            }
        }
        file_put_contents($pathControllerName, str_replace('\/', '/', json_encode($controllerObject)));

        $collection = Collection::make($listController);
        return Datatables::of($collection)
            ->editColumn('controller', function ($collection) {
                $inputName = $collection['package'] . '-' . $collection['module'] . '-' . $collection['controller'];
                $controller = '<div class="form-group input-group" style="width: 100%">
                                <input type="text" class="form-control" name="' . $inputName . '" value="' . $collection['controller'] . '">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btnSaveControllerName" type="button" data-name="' . $inputName . '">
                                        Save
                                    </button>
                                </span>
                            </div>';
                return $controller;
            })
            ->rawColumns(['method', 'controller'])->make(true);
    }

    public function dataMore(Request $request)
    {
        $app = app();
        $list = array();
        $routes = $app->routes->getRoutes();

        $object_data = null;
        $objectType = strtolower($request->input('object_type'));
        switch ($objectType) {
            case 'role':
                $object_data = $this->_roleRepository->getById($request->input('object_id'));
                break;
            case 'group':
                break;
            case 'user':
                $object_data = $this->_userRepository->getById($request->input('object_id'));
                break;
        }

        if (null == $object_data) {
            abort(404);
        }

        $whitelist = ['user' , 'role'];
        if ($object_data->permission_locked == true) {
            abort(403);
        }

        foreach ($routes as $route) {

            if (isset($route->action['as'])) {
                $module = explode('.', $route->action['as']);
                if (count($module) == 4) {
//                    if (in_array($module[2], $whitelist)) {
                        $wheres = '';
                        if ($route->wheres) {
                            foreach ($route->wheres as $k => $v) {
                                $wheres .= '<div>' . $k . ': ' . $v . '</div>';
                            }
                        }

                        $list[] = [
                            'uri' => $route->uri . $wheres,
                            'name' => (isset($route->action['as'])) ? $route->action['as'] : 'N/A',
                            'controller' => (isset($route->action['controller'])) ? $route->action['controller'] : 'N/A',
                            'middleware' => (isset($route->action['middleware'])) ? (is_array($route->action['middleware']) ? implode(', ', $route->action['middleware'])  : $route->action['middleware']) : 'N/A'
                        ];
//                    }
                }
            }
        }
        $collection = Collection::make($list);
        return Datatables::of($collection)
            ->addColumn('actions', function ($collection) use ($object_data) {
                $actions = '';
                if ($collection['name'] != 'N/A') {
                    if ($object_data->canAccess($collection['name'])) {
                        $actions = '<input type="checkbox" class="allow_permission" data-size="mini" data-name="'.$collection['name'].'" checked>';
                    } else {
                        $actions = '<input type="checkbox" class="allow_permission" data-size="mini" data-name="'.$collection['name'].'">';
                    }
                }
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
