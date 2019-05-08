<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\BaseController;
use App\Models\Members;
use App\Models\MemberCards;
use App\Models\MemberLogs;
use App\Models\Accounts;
use App\Models\Cards;

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

    /**
     * Destruct the card.
     *
     * @param \Illuminate\Http\Request $request
     * @param int type
     *
     * @return \Illuminate\Http\Response
     */
    public function gainCard(Request $request)
    {
        if ($request->get('type', '') == '') {
            return ['status' => false, 'msg' => '参数错误！'];
        }
        
        $uid = 2; // TODO:

        $date   = date('Y-m-d');
        $member = Members::find($uid);
        $logs   = MemberLogs::where(['fk_member_id' => $member->id])
            ->select(['id', 'log_type', 'created_at'])
            ->whereIn('log_type', [
                MemberLogs::TYPE_DRAW,
                MemberLogs::TYPE_SHARE,
                MemberLogs::TYPE_AD,
                MemberLogs::TYPE_LOGIN
            ])
            ->whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])
            ->get();
        
        $base = $share = $ad = $login = 0;
        $types = array_column($logs->toArray(), 'log_type');
        foreach ($types as $type) {
            switch ($type) {
                case 1:
                    $base++;
                    break;
                case 2:
                    $share++;
                    break;
                case 3:
                    $ad++;
                    break;
                case 7:
                    $login++;
                    break;
                default:
                    break;
            }
        }

        // Check total draw.
        if ($member->total_draw <= count($types)) {
            return ['status' => false, 'msg' => '今日抽奖次数已用完，请明日再来！'];
        }

        // Check base drwa.
        if ($member->base_draw <= $base && $request->get('type') == MemberLogs::TYPE_DRAW) {
            return ['status' => false, 'msg' => '获取卡片次数已用完！', 'code' => 1];
        }

        // Get member total card.
        $total_card = memberCards::where(['fk_member_id' => $member->id, 'delete' => 0])->count();

        // Get Usable card.
        $cards = Cards::select(['id', 'fk_group_id', 'chance'])
            ->where([
                ['difficulty_level', '<=', $total_card],
                ['status', '=', 1]
            ])
            ->whereRaw('total_cards - issued > 0')
            ->orderBy('chance', 'desc')
            ->get();
        
        // Draw card.
        $prize = [];
        foreach ($cards as $card) {
            $prize[$card->id] = round($card->chance * 10000, 0);
        }

        $card_id = $this->getRand($prize);
        $award   = [];
        foreach ($cards as $card) {
            if ($card->id == $card_id) {
                $award = [
                    'card_id'  => $card->id,
                    'group_id' => $card->fk_group_id
                ];
            }
        }

        DB::beginTransaction();
        $date = date('Y-m-d H:i:s');

        // Add member_cards table.
        $member_card_id = MemberCards::insertGetId([
            'fk_member_id'  => $member->id,
            'fk_card_id'    => $award['card_id'],
            'delete'        => 0,
            'delete_remark' => '',
            'created_at'    => $date,
            'updated_at'    => $date,
        ]);
        if (!$member_card_id) {
            return ['status' => false, 'msg' => '获取卡片失败！'];
            DB::rollBack();
        }
        $award['member_card_id'] = $member_card_id;

        // Change cards table.
        $card = Cards::find($award['card_id']);
        $card->issued = $card->issued + 1;
        if (!$card->save()) {
            return ['status' => false, 'msg' => '获取卡片失败！'];
            DB::rollBack();
        }


        // Add member_logs table.
        $member_logs = new MemberLogs;
        $member_logs->fk_member_id = $member->id;
        $member_logs->log_type     = $request->get('type');
        $member_logs->created_at   = $date;
        $member_logs->extend       = json_encode([
            'member_card_id' => $award['member_card_id'],
            'remark'         => '获取卡片',
        ], JSON_UNESCAPED_UNICODE);
        if (!$member_logs->save()) {
            return ['status' => false, 'msg' => '获取卡片失败！'];
            DB::rollBack();
        }

        DB::commit();

        return ['status' => true, 'data' => $award];
    }

    /**
     * 几率计算
     */
    public function getRand($prize)
    {
        $result = '';
     
        $sum = array_sum($prize);
     
        foreach ($prize as $key => $current) {
            $random = mt_rand(1, $sum);
            if ($random <= $current) {
                $result = $key;
                break;
            } else {
                $sum -= $current;
            }
        }
        unset($prize);
     
        return$result;
    }
}
