<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService
{
    // 引数は$imageFileが入ってくる、戻り値は
    public static function upload($imageFile, $folderName)
    {
        $fileName = uniqid(rand().'_'); // ランダムなファイル名にする
        $extension = $imageFile->extension(); // アップロードされたimageFileの拡張子を取得
        $fileNameToStore = $fileName. '.' .$extension; // ファイル名と拡張子を付けて、変数に入れる
        $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode(); // アップロードされた画像をInterventionのmakeに入れ、リサイズする
        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage); // 第1引数がフォルダからのファイル名、第2引数がリサイズした画像
        // 作ったファイル名を返して、データベースに保存
        return $fileNameToStore;
    }
}