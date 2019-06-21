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

            try {
                if ($data['type'] == 1) {
                    // HTML
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
                } else {
                    // JSON
                    $res = (new Client(['timeout' => 10]))->get($data['url']);
                    $ret = json_decode($res->getBody(), true);

                    // 获取字段规则
                    if ($data['filed_rule'] != '') {
                        $filed_rule = json_decode($data['filed_rule'], true);

                        // 是否是列表
                        if (isset($filed_rule['item'])) {
                            foreach (explode(",", $filed_rule['item']) as $filed) {
                                $ret = $ret[$filed];
                            }
                        }

                        $items = [];
                        foreach ($ret as $k => $v) {
                            $temp = [];
                            foreach ($filed_rule as $key => $rule) {
                                $tmp_v = $v;
                                if (in_array($key, ['item'])) {
                                    continue;
                                }

                                foreach (explode(",", $rule['field']) as $filed) {
                                    $tmp_v = $tmp_v[$filed];
                                }

                                if (isset($rule['reg']) && !empty($rule['reg'])) {
                                    preg_match($rule['reg'], $tmp_v, $match);
                                    $temp[$key] = $match[1];
                                } else {
                                    $temp[$key] = $tmp_v;
                                }
                            }

                            $items[] = $temp;
                        }

                        foreach ($items as &$value) {
                            $tmp = '';
                            foreach ($value as $v) {
                                $tmp .= $pinyin->permalink($v, '') . ' ';
                                $tmp .= $pinyin->abbr($v) . ' ';
                            }
                            
                            $value['transform'] = $tmp;
                        }
                        unset($value);

                        return $items;
                    }

                    return $ret;
                }
            } catch (\Throwable $th) {
                return $th;
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
