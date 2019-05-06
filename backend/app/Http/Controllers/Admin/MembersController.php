<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Members;
use App\Models\MemberLogs;
use App\Models\Accounts;
use App\Models\MemberCards;

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
        if ($action == 'cards') {
            return $this->card($request, $id);
        }

        $search_key = ['date' => [], 'type' => $request->get('type', '')];

        $member = Members::find($id);

        $where = [];
        $where[] = ['fk_member_id', '=', $id];
        if ($request->get('start', '') != '' && $request->get('end', '') != '') {
            $where[] = [
                'created_at',
                '>=',
                $request->get('start') . ' 00:00:00'
            ];
            $where[] = [
                'created_at',
                '<=',
                $request->get('end') . ' 23:59:59'
            ];
            $search_key['date'] = [$request->get('start'), $request->get('end')];
        }

        if ($action == 'logs') {
            if ($search_key['type'] != '') {
                $where[] = ['log_type', '=', $search_key['type']];
            }
            $result = MemberLogs::where($where)
                ->orderBy('id', 'desc')
                ->paginate(config('admin.pageSize'));
        }

        if ($action == 'accounts') {
            if ($search_key['type'] != '') {
                $where[] = $search_key['type'] == 1
                    ? ['income', '>', 0]
                    : ['income', '<', 0];
            }
            $result = Accounts::where($where)
                ->orderBy('id', 'desc')
                ->paginate(config('admin.pageSize'));
        }
        
        return view('admin.member.action', compact(
            'result',
            'action',
            'member',
            'search_key'
        ));
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function card(Request $request, int $id)
    {
        $member = Members::find($id);

        $cards = MemberCards::where(['fk_member_id' => $id])
            ->select([
                'member_cards.id',
                'member_cards.delete',
                'member_cards.delete_remark',
                'member_cards.created_at',
                'cards.id as card_id',
                'cards.name as card_name',
                'cards.cover as card_cover',
            ])
            ->leftJoin('cards', 'member_cards.fk_card_id', '=', 'cards.id')
            ->get();

        return view('admin.member.card', compact(
            'member',
            'cards'
        ));
    }
}
