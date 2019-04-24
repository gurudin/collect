<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CardGroup;

class CardGroupController extends Controller
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
            $groups = CardGroup::where($where)->orderBy('id', 'desc')->paginate(config('admin.pageSize'));
            
            return view('admin.cards.group', compact(
                'groups'
            ));
        }

        /**
         * Change status
         */
        if ($request->post('action') == 'status') {
            $data = $request->post('data');
            $m = CardGroup::find($data['id']);
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
                ? CardGroup::attributeLabels()
                : CardGroup::find($request->get('id'));
            
            return view('admin.cards.group-save', compact(
                'm'
            ));
        }

        /**
         * Save
         */
        if ($request->post('action') == 'save') {
            $data = $request->post('data');

            $m = (!isset($data['id']) || empty($data['id']))
                ? new CardGroup
                : CardGroup::find($data['id']);

            foreach ($data as $key => $val) {
                $m->$key = $val;
            }

            return $m->save()
                ? ['status' => true]
                : ['status' => false, 'failed to save'];
        }
    }
}
