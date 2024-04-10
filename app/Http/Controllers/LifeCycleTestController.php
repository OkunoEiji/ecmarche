<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    public function showServiceProviderTest()
    {
        // encryptメソッドでパスワードという文字列が暗号化される。
        // decryptで元に戻して、passwordが表示される。
        $encrypt = app()->make('encrypter');
        $password = $encrypt->encrypt('password');
        // dd($password, $encrypt->decrypt($password));

        // appのmakeでサービスコンテナから（'serviceProviderTest'）を取り出す
        $sample = app()->make('serviceProviderTest');

        dd($sample);
    }

    public function showServiceContainerTest()
    {
        // appのbindでサービスコンテナに登録したい名前（'lifeCycleTest'）、処理の内容（return）
        app()->bind('lifeCycleTest', function(){
            return 'ライフサイクルテスト';
        });

        $test = app()->make('lifeCycleTest');

        //サービスコンテナなしの場合
        // $message = new Message();
        // $sample = new Sample($message);
        // $sample->run();

        //サービスコンテナありの場合
        app()->bind('sample', Sample::class);
        $sample = app()->make('sample');
        $sample->run();

        dd($test,app());
    }
}

class Sample
{
    public $message;
    public function __construct(Message $message){
        $this->message = $message;
    }

    public function run()
    {
        $this->message->send();
    }
}


class Message
{
    public function send()
    {
        echo('メッセージ表示');
    }
}
