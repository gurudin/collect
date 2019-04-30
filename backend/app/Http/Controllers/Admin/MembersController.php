<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Members;

class MembersController extends Controller
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
            $search = [
                'nick'   => $request->get('nick', ''),
                'status' => $request->get('status', ''),
                'order'  => $request->get('order', ''),
            ];

            $where  = [];
            if (!empty($search['nick'])) {
                $where[] = ['nick_name', 'like', '%' . $search['nick'] . '%'];
            }
            if ($search['status'] != '') {
                $where[] = ['status', '=', $search['status']];
            }
            
            $query = Members::where($where);
            $query = $search['order'] == ''
                ? $query->orderBy('id', 'desc')
                : $query->orderBy('balance', $search['order']);

            $members = $query->paginate(config('admin.pageSize'));
            
            $avatars = config('admin.avatar');

            return view('admin.member.member', compact(
                'members',
                'avatars',
                'search'
            ));
        }

        /**
         * Change status
         */
        if ($request->post('action') == 'status') {
            $data = $request->post('data');
            $m = Members::find($data['id']);
            $m->status = $data['status'];

            return $m->save()
                ? ['status' => true]
                : ['status' => false, 'msg' => 'failed to update'];
        }
    }
}
