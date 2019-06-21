<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SpiderRule;
use QL\QueryList;
use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;

class SpiderController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function spider(Request $request)
    {
        if ($request->isMethod('get')) {
            $rules = SpiderRule::orderBy('id', 'desc')->get();

            return view('admin.spider.spider', compact(
                'rules'
            ));
        }

        /**
         * Enabled
         */
        if ($request->post('action') == 'enabled') {
            return SpiderRule::where('id', $request->post('id'))->update(['enable' => $request->post('value')])
                ? ['status' => true]
                : ['status' => false];
        }

        /**
         * Delete
         */
        if ($request->post('action') == 'delete') {
            return SpiderRule::where('id', $request->post('id'))->delete()
                ? ['status' => true]
                : ['status' => false, 'msg' => 'Failed to delete.'];
        }
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function spiderSave(Request $request)
    {
        if ($request->isMethod('get')) {
            if ($request->get('id', 0) == 0) {
                // Create
                $m = SpiderRule::attributeLabels();
            } else {
                // Update
                $m = SpiderRule::find($request->get('id'));
            }

            return view('admin.spider.spider-save', compact(
                'm'
            ));
        }

        /**
         * Test spider
         */
        if ($request->post('action') == 'testRule') {
            $pinyin = new Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
            $data = $request->post('data');

            if ($data['type'] == 1) {
                // HTML
                try {
                    $result = QueryList::get($data['url'])
                    ->rules(json_decode($data['rule'], true))
                    ->range($data['slice'])
                    ->queryData();
                    
                    foreach ($result as &$value) {
                        $tmp = '';
                        foreach ($value as $v) {
                            $tmp .= $pinyin->permalink($v, '') . ' ';
                            $tmp .= $pinyin->abbr($v) . ' ';
                        }
                        
                        $value['transform'] = $tmp;
                    }
                    unset($value);
                    
                    return $result;
                } catch (\Throwable $th) {
                    return $th;
                }
            } else {
                // JSON
            }
        }

        /**
         * Save
         */
        if ($request->post('action') == 'save') {
            if (isset($request->post('data')['id'])) {
                // Update
                return SpiderRule::where('id', $request->post('data')['id'])->update($request->post('data'))
                    ? ['status' => true]
                    : ['status' => false, 'msg' => 'Failed to update.'];
            } else {
                // Create
                $m = new SpiderRule;
                foreach ($request->post('data') as $k => $v) {
                    $m->$k = $v;
                }
                
                return $m->save()
                    ? ['status' => true]
                    : ['status' => false, 'msg' => 'Failed to save.'];
            }
        }
    }
}
