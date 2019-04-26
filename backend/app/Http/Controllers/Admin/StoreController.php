<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Cards;

class StoreController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $where = [];
            $result = Store::where($where)->orderBy('id', 'desc')->paginate(config('admin.pageSize'));
            
            $exchange_type = Store::TYPE;

            $cards = Cards::where(['status' => 1])->orderBy('id', 'desc')->get();

            $page   = $result->links();
            $stores = $result->toArray()['data'];
            foreach ($stores as &$store) {
                foreach ($exchange_type as $type) {
                    if ($type['code'] == $store['exchange']) {
                        $store['exchange_text'] = $type['title'];
                    }
                    if ($type['code'] == $store['swop']) {
                        $store['swop_text'] = $type['title'];
                    }
                }
            }
            
            return view('admin.store.store', compact(
                'stores',
                'page',
                'cards'
            ));
        }

        /**
         * Change status
         */
        if ($request->post('action') == 'status') {
            $data = $request->post('data');
            $m = Store::find($data['id']);
            $m->status = $data['status'];

            return $m->save()
                ? ['status' => true]
                : ['status' => false, 'msg' => 'failed to update'];
        }
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        if ($request->isMethod('get')) {
            $m = $request->get('id', '') == ''
                ? Store::attributeLabels()
                : Store::find($request->get('id'));
            $exchange_type = Store::TYPE;

            $cards = Cards::where(['status' => 1])->orderBy('id', 'desc')->get();

            return view('admin.store.store-save', compact(
                'm',
                'exchange_type',
                'cards'
            ));
        }

        /**
         * Save
         */
        if ($request->post('action') == 'save') {
            $data = $request->post('data');

            $m = new Store;
            foreach ($data as $key => $val) {
                $m->$key = $val;
            }

            return $m->save()
                ? ['status' => true]
                : ['status' => false, 'failed to save'];
        }
    }
}
