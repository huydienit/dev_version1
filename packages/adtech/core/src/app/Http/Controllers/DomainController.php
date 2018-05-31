<?php

namespace Adtech\Core\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Adtech\Core\App\Repositories\DomainRepository;
use Adtech\Core\App\Http\Requests\DomainRequest;
use Adtech\Core\App\Models\Domain;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Validator;

class DomainController extends Controller
{
    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(DomainRepository $domainRepository)
    {
        parent::__construct();
        $this->domain = $domainRepository;
    }

    public function add(DomainRequest $request)
    {
        $domains = new Domain($request->all());
        $domains->save();

        if ($domains->domain_id) {
            return redirect()->route('adtech.core.domain.manage')->with('success', trans('adtech-core::messages.success.create'));
        } else {
            return redirect()->route('adtech.core.domain.manage')->with('error', trans('adtech-core::messages.error.create'));
        }
    }

    public function create()
    {
        return view('ADTECH-CORE::modules.core.domain.create');
    }

    public function delete(DomainRequest $request)
    {
        $domain_id = $request->input('domain_id');
        $domain = $this->domain->find($domain_id);

        if ($domain->delete()) {
            return redirect()->route('adtech.core.domain.manage')->with('success', trans('adtech-core::messages.success.delete'));
        } else {
            return redirect()->route('adtech.core.domain.manage')->with('error', trans('adtech-core::messages.error.delete'));
        }
    }

    public function manage()
    {
        return view('ADTECH-CORE::modules.core.domain.manage');
    }

    public function show(DomainRequest $request)
    {
        $domain_id = $request->input('domain_id');
        $domain = $this->domain->find($domain_id);
        $data = [
            'domain' => $domain
        ];

        return view('ADTECH-CORE::modules.core.domain.edit', $data);
    }

    public function update(DomainRequest $request)
    {
        $domain_id = $request->input('domain_id');

        $domain = $this->domain->find($domain_id);
        $domain->name = $request->input('name');

        if ($domain->save()) {
            return redirect()->route('adtech.core.domain.manage')->with('success', trans('adtech-core::messages.success.update'));
        } else {
            return redirect()->route('adtech.core.domain.show', ['domain_id' => $request->input('domain_id')])->with('error', trans('adtech-core::messages.error.update'));
        }
    }

    public function getModalDelete(DomainRequest $request)
    {
        $model = 'domain';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'domain_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('adtech.core.domain.delete', ['domain_id' => $request->input('domain_id')]);
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    //Table Data to index page
    public function data()
    {
        $domains = new Collection();
        $domainsDir = base_path() . '/packages/adtech/application/src/configs';
        $ls = @scandir($domainsDir);
        if ($ls) {
            foreach ($ls as $index => $domain_name) {
                if ($this->is_valid_domain_name($domain_name)) {
                    $domain = $this->domain->findBy('name', addslashes($domain_name));
                    if (null === $domain) {
                        $domain = new Domain();
                        $domain->name = addslashes($domain_name);
                        $domain->save();
                    }
                }
            }
        }

        $domains = Domain::all();
        return Datatables::of($domains)
            ->editColumn('name', function ($domains) {
                return $actions = '<a href=' . route('adtech.core.package.manage', ['id' => $domains->domain_id]) . '>' . $domains->name . '</a>';
            })
            ->addColumn('actions', function ($domains) {
                $actions = '<a href=' . route('adtech.core.package.manage', ['id' => $domains->domain_id]) . '><i class="livicon" data-name="gear" data-size="18" data-loop="true" data-c="#6CC66C" data-hc="#6CC66C" title="package manage"></i></a>
                        <a href=' . route('adtech.core.domain.show', ['domain_id' => $domains->domain_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update domain"></i></a>
                        <a href=' . route('adtech.core.domain.confirm-delete', ['domain_id' => $domains->domain_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete domains"></i></a>';

                return $actions;
            })
            ->rawColumns(['actions', 'name'])
            ->make();
    }

    function is_valid_domain_name($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
    }
}
