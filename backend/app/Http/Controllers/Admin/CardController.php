<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cards;
use App\Models\CardGroup;

class CardController extends Controller
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
            $groups = CardGroup::orderBy('id', 'desc')->get();
            
            $where = [];
            $group_id = $request->get('group_id', '');
            if ($group_id != '') {
                $where[] = ['fk_group_id', $request->get('group_id')];
            }
            $cards = Cards::where($where)->orderBy('id', 'desc')->paginate(config('admin.pageSize'));

            return view('admin.cards.card', compact(
                'groups',
                'cards',
                'group_id'
            ));
        }

        /**
         * Change status
         */
        if ($request->post('action') == 'status') {
            $data = $request->post('data');
            $m = Cards::find($data['id']);
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
                ? Cards::attributeLabels()
                : Cards::find($request->get('id'));

            $groups = CardGroup::where(['status' => 1])->orderBy('id', 'desc')->get();

            $current_group = null;
            foreach ($groups as $group) {
                if ($group['id'] == $m['fk_group_id']) {
                    $current_group = $group;
                }
            }

            return view('admin.cards.card-save', compact(
                'current_group',
                'groups',
                'm'
            ));
        }

        /**
         * Save
         */
        if ($request->post('action') == 'save') {
            $data = $request->post('data');

            $m = (!isset($data['id']) || empty($data['id']))
                ? new Cards
                : Cards::find($data['id']);

            foreach ($data as $key => $val) {
                $m->$key = $val;
            }

            return $m->save()
                ? ['status' => true]
                : ['status' => false, 'failed to save'];
        }
    }
}
