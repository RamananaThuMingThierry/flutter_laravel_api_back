<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, Dispatchable;

    public function saveImage($image, $path = 'public'){
        if(!$image){
            return null;
        }

        $filename = time().'.png';
        // Save image
        Storage::disk($path)->put($filename, base64_decode($image));

        // return the path
        // url is the base exp: local
        return URL::to('/').'/storage/'. $path .'/'. $filename;
    }
}
