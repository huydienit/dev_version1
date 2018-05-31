<?php

namespace Adtech\Core\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Console\Scheduling\Schedule;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Adtech\Core\App\Repositories\DomainsPackageRepository;
use Adtech\Core\App\Repositories\PackageRepository;
use Adtech\Core\App\Repositories\DomainRepository;
use Adtech\Core\App\Http\Requests\PackageRequest;
use Adtech\Core\App\Models\DomainsPackage;
use Adtech\Core\App\Models\Package;
use Yajra\Datatables\Datatables;
use Illuminate\Filesystem\Filesystem;
use Adtech\Core\App\Models\Domain;
use Validator;

class PackageController extends Controller
{
    /**
     * @var Filesystem
     */
    protected $files;

    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(PackageRepository $packageRepository, DomainsPackageRepository $domainsPackageRepository,
                                DomainRepository $domainRepository, Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        $this->domain = $domainRepository;
        $this->package = $packageRepository;
        $this->domainsPackage = $domainsPackageRepository;
        $this->composer = app()['composer'];
    }

    public function add(PackageRequest $request)
    {
        if ($request->has('domain_id') && $request->input('domain_id') > 0) {
            $space = $request->input('space');
            $vendor = $request->input('package');
            $package = $request->input('module');
            $domain_id = $request->input('domain_id');
            $directory = '../packages/' . $vendor . '/' . $package;
            if (!$this->files->isDirectory($directory)) {

                //Upload file tar
                if ($request->has('check_file')) {
                    if ($request->hasFile('file_upload')) {
                        $file = $request->file_upload;
                        if ($file->getMimeType() == 'application/x-gzip' && $file->getClientOriginalExtension() == 'gz') {
                            $filename = substr($file->getClientOriginalName(), 0, strpos($file->getClientOriginalName(), '.'));
                            $dir = 'unzip/' . $filename;
                            if (!$this->files->isDirectory($dir)) {
                                mkdir($dir, 0755, true);
                            }
                            shell_exec('cd unzip/' . $filename . ' && tar xzf ' . $file->getRealPath());
                            //get package - modules name
                            $packagesDir = public_path() . '/' . $dir . '/packages/';
                            $ls = @scandir($packagesDir);
                            if ($ls) {
                                foreach ($ls as $index => $package) {
                                    if ($package === '.' || $package === '..') {
                                        continue;
                                    }
                                    if (is_dir($packagesDir . $package)) {
                                        //
                                        $vendor = $package;
                                        $arrPackage = [];
                                        $lsModule = @scandir($packagesDir . $package);
                                        if ($lsModule) {
                                            foreach ($lsModule as $index1 => $module) {
                                                if ($module === '.' || $module === '..') {
                                                    continue;
                                                }
                                                $arrPackage[] = $module;
                                            }
                                        }

                                        foreach ($arrPackage as $packageItem) {
                                            if ($this->files->isDirectory($dir . '/packages/' . $vendor . '/' . $packageItem) &&
                                                !$this->files->isDirectory('../packages/' . $vendor . '/' . $packageItem)) {

                                                $packages = new Package();
                                                $packages->space = $space;
                                                $packages->package = $vendor;
                                                $packages->module = $packageItem;
                                                $packages->create_by = $this->user->user_id;
                                                $packages->save();

                                                if ($packages->package_id) {
                                                    $domainsPackage = new DomainsPackage();
                                                    $domainsPackage->package_id = $packages->package_id;
                                                    $domainsPackage->domain_id = $domain_id;
                                                    $domainsPackage->save();
                                                }
                                            }
                                        }
                                        //
                                    }
                                }
                            }

                            //copy folder chua code
                            shell_exec('cd ../ && cp -r public/' . $dir . '/packages/*' . ' packages/');
                            return redirect()->route('adtech.core.package.manage', ['id' => $domain_id])->with('success', trans('adtech-core::messages.success.create'));
                        }
                    } else {
                        return redirect()->route('adtech.core.package.manage')->with('error', trans('adtech-core::messages.error.create'));
                    }
                } else { //Create new
                    $packages = new Package($request->all());
                    $packages->create_by = $this->user->user_id;
                    $packages->save();

                    if ($packages->package_id) {
                        \Artisan::call('make:package', [
                            'vendor' => $vendor,
                            'package' => $package,
                            '--path_value' => -1
                        ]);

                        $domainsPackage = new DomainsPackage();
                        $domainsPackage->package_id = $packages->package_id;
                        $domainsPackage->domain_id = $domain_id;
                        $domainsPackage->save();

                        return redirect()->route('adtech.core.package.manage', ['id' => $domain_id])->with('success', trans('adtech-core::messages.success.create'));
                    } else {
                        return redirect()->route('adtech.core.package.manage', ['id' => $domain_id])->with('error', trans('adtech-core::messages.error.create'));
                    }
                }
            } else {
                return redirect()->route('adtech.core.package.manage', ['id' => $domain_id])->with('error', trans('adtech-core::messages.error.create'));
            }
        } else {
            return redirect()->route('adtech.core.package.manage')->with('error', trans('adtech-core::messages.error.create'));
        }
        return redirect()->route('adtech.core.package.manage', ['id' => $domain_id])->with('error', trans('adtech-core::messages.error.create'));
    }

    public function create(Request $request)
    {
        $domain_id = $active = 0;
        if ($request->has('id')) {
            $domain_id = $request->input('id');
        }
        if ($request->has('active')) {
            $active = $request->input('active');
        }
        $arrPackage = [];
        $packages = Package::select('package')->distinct()->get();
        if (count($packages) > 0) {
            foreach ($packages as $package) {
                $arrPackage[] = $package->package;
            }
        }
        $arrPackage = json_encode($arrPackage);
        return view('ADTECH-CORE::modules.core.package.create', compact('domain_id', 'active', 'arrPackage'));
    }

    public function status(PackageRequest $request, Schedule $schedule)
    {
        $package_id = $request->input('package_id');
        $domain_id = $request->input('domain_id');

        $domainsPackage = $this->domainsPackage->findWhere([
            'package_id' => $package_id,
            'domain_id' => $domain_id
        ])->first();

        if (null != $domainsPackage) {
            $domainsPackage->status = ($domainsPackage->status == 0) ? 1 : 0;
            if ($domainsPackage->save()) {
                if ($domainsPackage->status == 1) {
                    $package = $this->package->find($package_id);

                    //khai bao trong composer root
                    $path = base_path('composer.json');
                    $composerFile = file_get_contents($path);
                    $composerObject = json_decode($composerFile, true);
                    $repositories = $composerObject['repositories'];
                    $require = $composerObject['require'];
                    $autoload_dev_classmap = $composerObject['autoload-dev']['classmap'];
                    $urlRepositorie = "packages"."/".$package->package."/".$package->module;

                    $checkRepo = true;
                    if (count($repositories) > 0) {
                        foreach ($repositories as $repositorie) {
                            if ($repositorie['url'] == $urlRepositorie) {
                                $checkRepo = false;
                                break;
                            }
                        }
                    }

                    if ($checkRepo) {
                        //them vao composer repositories
                        $repositoriesMore = new \stdClass();
                        $repositoriesMore->type = "path";
                        $repositoriesMore->url = $urlRepositorie;
                        $repositoriesMore->options = (object) array("symlink" => true);
                        $repositories[] = $repositoriesMore;

                        //them vao composer require
                        $require[$package->package . '/' . $package->module] = 'dev-master';

                        //them vao composer autoload dev
                        $autoload_dev_classmap[] = 'packages/' . $package->package . '/' . $package->module . '/src/';

                        $composerObject['repositories'] = $repositories;
                        $composerObject['require'] = $require;
                        $composerObject['autoload-dev']['classmap'] = $autoload_dev_classmap;

                        file_put_contents($path, str_replace('\/', '/', json_encode($composerObject)));
                    }

                    // Dump autoload.
                    $this->composer->dumpAutoloads();

                    //bung file /views/publics module
//                    \Artisan::call('vendor:publish', [
//                        '--provider' => ucfirst($package->package) .'\\' . ucfirst($package->module) . '\\' . ucfirst($package->module) . 'ServiceProvider'
//                    ]);
                }
                return redirect()->route('adtech.core.package.manage', ['id' => $domain_id])->with('success', trans('adtech-core::messages.success.update'));
            }
        }
        return redirect()->route('adtech.core.package.manage', ['id' => $domain_id])->with('error', trans('adtech-core::messages.error.update'));
    }

    public function delete(PackageRequest $request)
    {
        $package_id = $request->input('package_id');
        $domain_id = $request->input('domain_id');

        $domainsPackage = $this->domainsPackage->findWhere([
            'package_id' => $package_id,
            'domain_id' => $domain_id
        ])->first();

        if (null != $domainsPackage) {
            if ($domainsPackage->delete()) {

                $package = $this->package->find($package_id);
                //khai bao trong composer root
                $path = base_path('composer.json');
                $composerFile = file_get_contents($path);
                $composerObject = json_decode($composerFile, true);
                $repositories = $repositoriesEmpty = $composerObject['repositories'];
                $require = $composerObject['require'];
                $autoload_dev_classmap = $composerObject['autoload-dev']['classmap'];
                $urlRepositorie = "packages"."/".$package->package."/".$package->module;

                $checkRepo = false;
                if (count($repositories) > 0) {
                    $repositoriesEmpty = [];
                    foreach ($repositories as $repositorie) {
                        if ($repositorie['url'] == $urlRepositorie) {
                            $checkRepo = true;
                            continue;
                        }
                        $repositoriesEmpty[] = $repositorie;
                    }
                }

                if ($checkRepo) {
                    unset($require[$package->package . '/' . $package->module]);
                    if (($key = array_search('packages/' . $package->package . '/' . $package->module . '/src/', $autoload_dev_classmap)) !== false) {
                        unset($autoload_dev_classmap[$key]);
                    }

                    $composerObject['require'] = $require;
                    $composerObject['repositories'] = $repositoriesEmpty;
                    $composerObject['autoload-dev']['classmap'] = $autoload_dev_classmap;

                    file_put_contents($path, str_replace('\/', '/', json_encode($composerObject)));
                }

                return redirect()->route('adtech.core.package.manage', ['id' => $domain_id])->with('success', trans('adtech-core::messages.success.delete'));
            } else {
                return redirect()->route('adtech.core.package.manage', ['id' => $domain_id])->with('error', trans('adtech-core::messages.error.delete'));
            }
        } else {
            return redirect()->route('adtech.core.package.manage', ['id' => $domain_id])->with('error', trans('adtech-core::messages.error.delete'));
        }
    }

    public function download(Request $request)
    {
        if ($request->has('packages') && $request->input('packages') != '') {
            $packages = $request->input('packages');
            $arrPackages = explode(',', $packages);
            if (count($arrPackages) > 0) {
                $myfile = 'zip/';
                $mydir = '';
                foreach ($arrPackages as $package_id) {
                    $package = $this->package->find($package_id);
                    if (null != $package) {
                        $mydir .= ' packages/' . $package->package . '/' . $package->module;
                        $myfile .= '_' . $package->package . '_' . $package->module;
                    }
                }
                $myfile .= '.tar.gz';
                shell_exec('cd ../ && tar -zcvf public/' . $myfile . '' . $mydir);

                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$myfile");
                header("Content-Type: application/octet-stream");
                header('Content-Length: ' . filesize($myfile));
                header("Content-Transfer-Encoding: binary");

                // read the file from disk
                readfile($myfile);
            }
        } elseif ($request->has('package_id') && $request->input('package_id') > 0) {
            $package = $this->package->find($request->input('package_id'));
            if (null != $package) {
                $mydir = 'packages/' . $package->package . '/' . $package->module;
                $myfile = 'zip/' . $package->package . '_' . $package->module . '.tar.gz';
                shell_exec('cd ../ && tar -zcvf public/' . $myfile . ' ' . $mydir);

                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$myfile");
                header("Content-Type: application/octet-stream");
                header('Content-Length: ' . filesize($myfile));
                header("Content-Transfer-Encoding: binary");

                // read the file from disk
                readfile($myfile);
            }
        }
        return false;
    }

    public function manage(Request $request)
    {
        $domains = Domain::all(['domain_id', 'name']);
        if ($request->has('id')) {
            $domain_id = $request->input('id');
        } else {
            $domain_id = $domains[0]->domain_id;
        }

        $packages = Package::select('package')->distinct()->get();

        return view('ADTECH-CORE::modules.core.package.manage', compact('domains', 'domain_id', 'packages'));
    }

    public function show(PackageRequest $request)
    {
        $package_id = $request->input('package_id');
        $package = $this->package->find($package_id);
        $data = [
            'package' => $package
        ];

        return view('modules.core.package.edit', $data);
    }

    public function update(PackageRequest $request)
    {
        $package_id = $request->input('package_id');

        $package = $this->package->find($package_id);
        $package->package = $request->input('package');
        $package->module = $request->input('module');
        $package->space = $request->input('space');

        if ($package->save()) {
            return redirect()->route('adtech.core.package.manage')->with('success', trans('adtech-core::messages.success.update'));
        } else {
            return redirect()->route('adtech.core.package.show', ['package_id' => $request->input('package_id')])->with('error', trans('adtech-core::messages.error.update'));
        }
    }

    public function getModalDelete(PackageRequest $request)
    {
        $model = 'package';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|numeric',
            'domain_id' => 'required|numeric'
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('adtech.core.package.delete', [
                    'package_id' => $request->input('package_id'),
                    'domain_id' => $request->input('domain_id')
                ]);
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function getModalStatus(PackageRequest $request)
    {
        $model = 'module_status';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|numeric',
            'domain_id' => 'required|numeric'
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('adtech.core.package.status', [
                    'package_id' => $request->input('package_id'),
                    'domain_id' => $request->input('domain_id')
                ]);
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function getModalSearch(PackageRequest $request)
    {
        $model = 'module_status';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('adtech.core.package.status', ['package_id' => $request->input('package_id')]);
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function searchPackage(Request $request){
        $package_name = $request->input('package_name');

        $results = array();
        $queries = Package::where('package', $package_name)->get();
        foreach ($queries as $query)
        {
            $results[] = [ 'id' => $query->package_id, 'name' => $query->module ];
        }
        return Response::json($results);
    }

    public function addPackage(Request $request){
        $result = [];
        $result['type'] = 'error';
        $result['msg'] = 'Add Package Fail';

        $package_id = $request->input('package_id');
        $domain_id = $request->input('domain_id');

        $domainsPackage = $this->domainsPackage->findWhere([
            'package_id' => $package_id,
            'domain_id' => $domain_id
        ]);

        if (null === $domainsPackage || count($domainsPackage) === 0) {
            $domainsPackage = new DomainsPackage();
            $domainsPackage->package_id = $package_id;
            $domainsPackage->domain_id = $domain_id;
            $domainsPackage->save();

            $result['type'] = 'success';
            $result['group'] = 'Permission';
            $result['msg'] = 'Add Package Successfull';
        }

        return $result;
    }

    //Table Data to index page
    public function data(Request $request)
    {
        if ($request->has('id')) {
            $domain_id = $request->input('id');
        } else {
            $domain_id = Domain::first()->domain_id;
        }

        $packagesDir = base_path() . '/packages/';
        $ls = @scandir($packagesDir);
        if ($ls) {
            foreach ($ls as $index => $package) {
                if ($package === '.' || $package === '..') { continue; }
                if (is_dir($packagesDir . $package)) {

                    $lsModule = @scandir($packagesDir . $package);
                    if ($lsModule) {
                        foreach ($lsModule as $index1 => $module) {
                            if ($module === '.' || $module === '..') { continue; }

                            $packageDetail = Package::where([
                                'package' => addslashes($package),
                                'module' => addslashes($module)
                            ])->first();

                            if (null === $packageDetail) {
                                $packageDetail = new Package();
                                $packageDetail->package = addslashes($package);
                                $packageDetail->module = addslashes($module);
                                $packageDetail->space = 'Backend';
                                $packageDetail->save();
                            }

                        }
                    }

                }
            }
        }

        $app = app();
        $list = $listName = [];
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
                    $list[$package][$module][$controller] = (isset($list[$package][$module][$controller])) ? $list[$package][$module][$controller] . ',' . $action : $action;
                    $listName[$package][$module][$controller] = (isset($listName[$package][$module][$controller])) ? $listName[$package][$module][$controller] . ',' . $name : $name;
                }
            }
        }

        //
        $packages = Package::with('domains')
            ->whereHas('domains', function ($query) use ($domain_id) {
                $query->where('adtech_core_domains_package.domain_id', $domain_id);
            })
            ->get();

        $arrModules = [];
        if ($packages && count($packages) > 0) {
            foreach ($packages as $k => $package) {
                if ($package->domains[0]->pivot->status == 1) {
                    $package_name = $package->package;
                    $module_name = $package->module;
                    $arrModules[$package_name][] = $module_name;
                }
            }
        }

        $newString = '';
        foreach ($arrModules as $k => $modules) {
            $newString .= $k . '.' . implode(',', $modules) . '_';
        }
        $newString = substr($newString, 0, -1);
        $path = base_path('.env');
        if (file_exists($path)) {
            $domain = $this->domain->find($domain_id);
            if (null != $domain) {
                $host = $domain->name;
                $variable = 'APP_MODULES_' . strtoupper(str_replace('.', '_', $host));
                if (strpos(file_get_contents($path), $variable . '=') > 0) {
                    file_put_contents($path, str_replace(
                        $variable . '='.env($variable), $variable . '='.$newString,
                        file_get_contents($path)
                    ));
                } else {
                    file_put_contents($path, file_get_contents($path) . "\r\n" . $variable . '=' . $newString);
                }
            }
        }
        \Artisan::call('config:clear');

        return Datatables::of($packages)
            ->editColumn('status', function ($packages) use ($domain_id) {
                if ($packages->domains[0]->pivot->status == 1) {
                    $status = '<a href=' . route('adtech.core.package.confirm-status', ['package_id' => $packages->package_id, 'domain_id' => $domain_id]) . ' data-toggle="modal" data-target="#status_confirm"><span class="label label-sm label-success">Enable</span></a>';
                } else {
                    $status = '<a href=' . route('adtech.core.package.confirm-status', ['package_id' => $packages->package_id, 'domain_id' => $domain_id]) . ' data-toggle="modal" data-target="#status_confirm"><span class="label label-sm label-warning">Disable</span></a>
                            <a href=' . route('adtech.core.package.confirm-delete', ['package_id' => $packages->package_id, 'domain_id' => $domain_id]) . ' data-toggle="modal" data-target="#delete_confirm"><span class="label label-sm label-danger">Remove</span></a>';
                }
                return $status;
            })
            ->addColumn('DT_RowId', function ($packages) {
                return $packages->package_id;
            })
            ->addColumn('methods', function ($packages) use ($list) {
                $package_name = $packages->package;
                $module_name = $packages->module;
                $methodTbl = '';
                if (isset($list[$package_name][$module_name])) {
                    foreach ($list[$package_name][$module_name] as $k => $methods) {
                        $methodStr = '';
                        $methods = explode(',', $methods);
                        if (count($methods) > 0) {
                            foreach ($methods as $method)
                                $methodStr .= $method . ', ';
                        }
                        $methodTbl .= '<b>' . $k . ': </b>' . substr($methodStr, 0, -2) . '<br>';
                    }
                }
                return $methodTbl;
            })
            ->addColumn('actions', function ($packages) use ($domain_id) {
                $actions = '<a href=' . route('adtech.core.package.download', ['package_id' => $packages->package_id]) . '><i class="livicon" data-name="download" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="download packages"></i></a>';

                return $actions;
            })
            ->rawColumns(['actions', 'status', 'methods'])
            ->make();
    }
}
