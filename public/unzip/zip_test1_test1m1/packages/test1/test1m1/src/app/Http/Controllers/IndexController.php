<?php

namespace Test1\Test1m1\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Test1\Test1m1\App\Repositories\IndexRepository;

class IndexController extends Controller
{
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
            return redirect()->route('test1.test1m1.index.manage')->with('success', trans('TEST1-TEST1M1::messages.success.create'));
        } else {
            return redirect()->route('test1.test1m1.index.manage')->with('error', trans('TEST1-TEST1M1::messages.error.create'));
        }
    }

    public function create()
    {
        return view('TEST1-TEST1M1::modules.test1m1.index.create');
    }

    public function delete(IndexRequest $request)
    {
        $index_id = $request->input('index_id');
        $index = $this->index->find($index_id);

        if ($index->delete()) {
            return redirect()->route('test1.test1m1.index.manage')->with('success', trans('TEST1-TEST1M1::messages.success.delete'));
        } else {
            return redirect()->route('test1.test1m1.index.manage')->with('error', trans('TEST1-TEST1M1::messages.error.delete'));
        }
    }

    public function manage()
    {
        return view('TEST1-TEST1M1::modules.test1m1.index.manage');
    }

    public function show(IndexRequest $request)
    {
        $index_id = $request->input('index_id');
        $index = $this->index->find($index_id);
        $data = [
            'index' => $index
        ];

        return view('TEST1-TEST1M1::modules.test1m1.index.edit', $data);
    }

    public function update(IndexRequest $request)
    {
        $index_id = $request->input('index_id');

        $index = $this->index->find($index_id);
        $index->name = $request->input('name');

        if ($index->save()) {
            return redirect()->route('test1.test1m1.index.manage')->with('success', trans('TEST1-TEST1M1::messages.success.update'));
        } else {
            return redirect()->route('test1.test1m1.index.show', ['index_id' => $request->input('index_id')])->with('error', trans('TEST1-TEST1M1::messages.error.update'));
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
                $confirm_route = route('test1.test1m1.index.delete', ['index_id' => $request->input('index_id')]);
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
        $indexs = Index::all();
        return Datatables::of($indexs)
            ->addColumn('actions', function ($indexs) {
                $actions = '<a href=' . route('test1.test1m1.index.show', ['index_id' => $indexs->index_id]) . '><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="update index"></i></a>
                        <a href=' . route('test1.test1m1.index.confirm-delete', ['index_id' => $indexs->index_id]) . ' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete index"></i></a>';

                return $actions;
            })
            ->rawColumns(['actions'])
            ->make();
    }
}
