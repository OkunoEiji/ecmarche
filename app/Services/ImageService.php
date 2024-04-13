<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService
{
    // 引数は$imageFileが入ってくる、戻り値は
    public static function upload($imageFile, $folderName)
    {
        // dd($imageFile['image']);
        // 取得した内容が配列かの確認
        if(is_array($imageFile))
        {
            $file = $imageFile['image'];
        } else {
            $file = $imageFile;
        }

        $fileName = uniqid(rand().'_'); // ランダムなファイル名にする
        $extension = $file->extension(); // アップロードされたimageFileの拡張子を取得
        $fileNameToStore = $fileName. '.' .$extension; // ファイル名と拡張子を付けて、変数に入れる
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode(); // アップロードされた画像をInterventionのmakeに入れ、リサイズする
        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage); // 第1引数がフォルダからのファイル名、第2引数がリサイズした画像
        // 作ったファイル名を返して、データベースに保存
        return $fileNameToStore;
    }
}