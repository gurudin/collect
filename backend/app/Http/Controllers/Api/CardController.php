<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Cards;
use App\Models\CardGroup;

class CardController extends BaseController
{
    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function cards(Request $request)
    {
        $groups = CardGroup::select([
            'id as group_id',
            'name as group_name',
            'number as group_number',
            'description as group_description',
            'cover as group_cover',
            'created_at as created_at'
        ])->where(['status' => 1])->orderBy('id', 'desc')->get();

        $cards = Cards::select([
            'id',
            'fk_group_id',
            'name',
            'description',
            'cover',
        ])->where(['status' => 1])->orderBy('id', 'desc')->get();
        
        
        
        $groups = $groups->toArray();
        foreach ($groups as &$group) {
            $group['group_cover'] = is_null($group['group_cover']) || $group['group_cover'] == ''
                ? ''
                : env('IMG_URL') . $group['group_cover'];

            foreach ($cards as $card) {
                if ($group['group_id'] == $card->fk_group_id) {
                    $card->cover = env('IMG_URL') . $card->cover;

                    $group['cards'][] = $card->toArray();
                }
            }
        }

        return $groups;
    }
}
