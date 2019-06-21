<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use QL\QueryList;
use Overtrue\Pinyin\Pinyin;
use GuzzleHttp\Client;

class jscjCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:jscj';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $rules = array (
        // 'title' => 
        // array (
        //     0 => 'h3>a',
        //     1 => 'text',
        // ),
        // 'link' => 
        // array (
        //     0 => 'h3>a',
        //     1 => 'href',
        // ),
        // );
        // $data = QueryList::get('https://toutiao.io')
        //     ->rules($rules)
        //     ->range('.posts>.post')
        //     ->queryData();

        // print_r($data);
        $this->live();
    }

    /**
     * 24 快讯
     */
    public function live()
    {
        $uri = 'https://api.jinse.com/v4/live/list?limit=2&reading=false&source=web&sort=&flag=down&id=0';
        $res = (new Client(['timeout' => 5]))->get(
            $uri,
            [
                'limit' => 2,
                'reading' => '',
                'source' => 'web',
                'sort' => '',
                'flag' => 'down',
                'id' => 0
            ]
        );
        $ret = json_decode($res->getBody(), true);

        print_r($ret);
    }
}
