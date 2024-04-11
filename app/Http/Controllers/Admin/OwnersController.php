<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner; // Eloquent（エロクアント）
use Illuminate\Support\Facades\DB; // QeryBuilder クエリビルダ
use Carbon\Carbon;

class OwnersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // admin-navigation.blade.phpのリンク先テスト
        // dd('オーナー一覧');

        // // Eloquent
        // $e_all = Owner::all();
        // // QeryBuilder
        // $q_get = DB::table('owners')->select('name', 'created_at')->get();
        // $q_first = DB::table('owners')->select('name')->first();
        // // Collection
        // $c_test = collect([
        //     'name'=>'テスト'
        // ]);

        // var_dump($q_first);

        // dd($e_all, $q_get, $q_first, $c_test);

        $e_all = Owner::all();
        $q_get = DB::table('owners')->select('name', 'created_at')->get();

        $date_now = Carbon::now();
        $date_parse = Carbon::parse(now());
        echo $date_now->year;
        echo $date_parse;

        return view('admin.owners.index',
        compact('e_all', 'q_get'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
