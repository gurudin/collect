<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\BaseController;
use App\Models\Members;
use App\Models\MemberCards;
use App\Models\MemberLogs;
use App\Models\Accounts;

class MemberController extends BaseController
{
    /**
     * Get all member cards.
     *
     * @param \Illuminate\Http\Request $request
     * @param string order
     *
     * @return \Illuminate\Http\Response
     */
    public function cards(Request $request)
    {
        $uid = 1; // TODO:

        $order = $request->get('order', '') == '' ? 'created_at' : 'fk_card_id';

        $cards = MemberCards::select([
                'id',
                'fk_card_id as card_id',
                'created_at',
            ])
            ->where(['fk_member_id' => $uid, 'delete' => 0])
            ->orderBy($order, 'asc')->get();

        return ['status' => true, 'data' => $cards];
    }

    /**
     * Destruct the card.
     *
     * @param \Illuminate\Http\Request $request
     * @param int member_card_id
     * @param string remark
     *
     * @return \Illuminate\Http\Response
     */
    public function destruct(Request $request)
    {
        $uid = 1; // TODO:
        
        if ($request->get('member_card_id', '') == '' || $request->get('remark', '') == '') {
            return ['status' => false, 'msg' => '参数错误！'];
        }

        $member = Members::find($uid);
        $member_cards = MemberCards::where(['id' => $request->get('member_card_id'), 'delete' => 0])->first();
        if (!$member_cards) {
            return ['status' => false, 'msg' => '卡片不存在！'];
        }

        DB::beginTransaction();

        // member_cards table.
        $member_cards->delete = MemberCards::DELETE;
        $member_cards->delete_remark = $request->get('remark', '');
        if (!$member_cards->save()) {
            DB::rollBack();
            return ['status' => false, 'msg' => 'card error'];
        }

        // member_logs table.
        $member_logs = new MemberLogs;
        $member_logs->fk_member_id = $member->id;
        $member_logs->log_type     = MemberLogs::TYPE_DESTRUCT;
        $member_logs->created_at   = date('Y-m-d H:i:s');
        $member_logs->extend       = json_encode([
            'member_cards_id' => $request->get('member_card_id'),
            'remark' => '拆分卡片'
        ], JSON_UNESCAPED_UNICODE);
        if (!$member_logs->save()) {
            DB::rollBack();

            return ['status' => false, 'msg' => 'destruct error'];
        }

        // member table.
        $member->balance    = $member->balance + 1;
        $member->updated_at = date('Y-m-d H:i:s');
        if (!$member->save()) {
            DB::rollBack();

            return ['status' => false, 'msg' => 'member error'];
        }

        // accounts table.
        $accounts = new Accounts;
        $accounts->fk_member_id = $member->id;
        $accounts->income       = 1;
        $accounts->balance      = $member->balance;
        $accounts->remark       = '拆分卡片';
        $accounts->created_at   = date('Y-m-d H:i:s');
        if (!$accounts->save()) {
            DB::rollBack();

            return ['status' => false, 'msg' => 'account error'];
        }

        // member_logs table.
        $member_logs = new MemberLogs;
        $member_logs->fk_member_id = $member->id;
        $member_logs->log_type     = MemberLogs::TYPE_GAIN;
        $member_logs->created_at   = date('Y-m-d H:i:s');
        $member_logs->extend       = json_encode([
            'member_cards_id' => $request->get('member_card_id'),
            'remark' => '拆分获取'
        ], JSON_UNESCAPED_UNICODE);
        if (!$member_logs->save()) {
            DB::rollBack();

            return ['status' => false, 'msg' => 'log error'];
        }

        DB::commit();

        return ['status' => true];
    }
}
