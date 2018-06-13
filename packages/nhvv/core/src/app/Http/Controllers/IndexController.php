<?php

namespace Nhvv\Core\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Nhvv\Core\App\Repositories\IndexRepository;
use Nhvv\Core\App\Models\Index;
use Spatie\Activitylog\Models\Activity;
use Yajra\Datatables\Datatables;
use Validator;

class IndexController extends Controller
{
    private $messages = array(
        'name.regex' => "Sai định dạng",
        'required' => "Bắt buộc",
        'numeric'  => "Phải là số"
    );

    public function __construct(IndexRepository $indexRepository)
    {
        parent::__construct();
        $this->index = $indexRepository;
    }

    public function add(IndexRequest $request)
    {
        $indexs = new Index($request->all());
        $indexs->save();

        if ($indexs->index_id) {
            return redirect()->route('nhvv.core.index.manage')->with('success', trans('NHVV-CORE::messages.success.create'));
        } else {
            return redirect()->route('nhvv.core.index.manage')->with('error', trans('NHVV-CORE::messages.error.create'));
        }
    }

    public function create()
    {
        return view('NHVV-CORE::modules.core.index.create');
    }

    public function delete(IndexRequest $request)
    {
        $index_id = $request->input('index_id');
        $index = $this->index->find($index_id);

        if (null != $index) {
            $this->index->deleteID($index_id);
            return redirect()->route('nhvv.core.index.manage')->with('success', trans('NHVV-CORE::messages.success.delete'));
        } else {
            return redirect()->route('nhvv.core.index.manage')->with('error', trans('NHVV-CORE::messages.error.delete'));
        }
    }

    public function manage()
    {
        return view('NHVV-CORE::modules.core.index.manage');
    }

    public function show(IndexRequest $request)
    {
        $index_id = $request->input('index_id');
        $index = $this->index->find($index_id);
        $data = [
            'index' => $index
        ];

        return view('NHVV-CORE::modules.core.index.edit', $data);
    }

    public function update(IndexRequest $request)
    {
        $index_id = $request->input('index_id');

        $index = $this->index->find($index_id);
        $index->name = $request->input('name');

        if ($index->save()) {
            return redirect()->route('nhvv.core.index.manage')->with('success', trans('NHVV-CORE::messages.success.update'));
        } else {
            return redirect()->route('nhvv.core.index.show', ['index_id' => $request->input('index_id')])->with('error', trans('NHVV-CORE::messages.error.update'));
        }
    }

    public function getModalDelete(indexRequest $request)
    {
        $model = 'index';
        $confirm_route = $error = null;
        $validator = Validator::make($request->all(), [
            'index_id' => 'required|numeric',
        ], $this->messages);
        if (!$validator->fails()) {
            try {
                $confirm_route = route('nhvv.core.index.delete', ['index_id' => $request->input('index_id')]);
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            } catch (GroupNotFoundException $e) {
                return view('includes.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } else {
            return $validator->messages();
        }
    }

    public function log(Request $request)
    {
        $model = 'index';
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
        $indexs = Index::all();
        return Datatables::of($indexs)
            ->addColumn('actions', function ($indexs) {
                $actions = '<a href=' . route('nhvv.core.index.log', ['type' => 'index', 'id' => $indexs->index_id]) . ' data-toggle="modal" data-target="#log"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#F99928" data-hc="#F99928" title="log index"></i></a>
                        <a href=' . route('nhvv.core.index.show', ['index_id' => $indexs->index_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update index"></i></a>
                        <a href=' . route('nhvv.core.index.confirm-delete', ['index_id' => $indexs->index_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete index"></i></a>';

                return $actions;
            })
            ->rawColumns(['actions'])
            ->make();
    }
}
