<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner; // Eloquent（エロクアント）
use Illuminate\Support\Facades\DB; // QeryBuilder クエリビルダ
use Carbon\Carbon;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

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

        // $e_all = Owner::all();
        // $q_get = DB::table('owners')->select('name', 'created_at')->get();

        // $date_now = Carbon::now();
        // $date_parse = Carbon::parse(now());
        // echo $date_now->year;
        // echo $date_parse;

        $owners = Owner::select('id', 'name', 'email', 'created_at')->get();

        return view('admin.owners.index',
        compact('owners'));
    }

    public function create()
    {
        return view('admin.owners.create');
    }

    // Requestは、formで入力された内容がRequestクラスになり、インスタンス化する
    public function store(Request $request)
    {
        // $request->name;
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Owner::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Owner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
        ->route('admin.owners.index')
        ->with('message', 'オーナーを登録致しました。');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        // findOrFailは、$idがなければ404画面
        $owner = Owner::findOrFail($id);
        // dd($owner);
        return view('admin.owners.edit', compact('owner'));
    }

    public function update(Request $request, string $id)
    {
        $owner = Owner::findOrFail($id);
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
        ->route('admin.owners.index')
        ->with('message', 'オーナー情報を更新致しました。');
    }

    public function destroy(string $id)
    {
        //
    }
}
