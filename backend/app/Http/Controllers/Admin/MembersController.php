<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Members;
use App\Models\MemberLogs;
use App\Models\Accounts;

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

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function action(Request $request, string $action, int $id)
    {
        $member = Members::find($id);

        if ($action == 'logs') {
            $result = MemberLogs::where(['fk_member_id' => $id])
                ->orderBy('id', 'desc')
                ->paginate(config('admin.pageSize'));
        }

        if ($action == 'accounts') {
            $result = Accounts::where(['fk_member_id' => $id])
                ->orderBy('id', 'desc')
                ->paginate(config('admin.pageSize'));
        }
        
        return view('admin.member.action', compact(
            'result',
            'action',
            'member'
        ));
    }
}
