<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class TestController extends Controller
{

    function crop(Request $request){

        $data = $this->validate($request, [
            'image' => 'required|image|dimensions:min_width=1024,min_height=1024',
            'dx' => 'required',
            'dy' => 'required',
            'scale' => 'required'
        ]);

        $image = Image::make($data['image']->getRealPath());

        $width = $image->getWidth()*$data['scale'];
        $height= $image->getHeight()*$data['scale'];
        $dx = (int)($data['dx'] <= 0 ? 0 : $data['dx'] * $data['scale']);
        $dy = (int)($data['dy'] <= 0 ? 0 : $data['dy'] * $data['scale']);

        $scaledImage = $image->resize($width, $height, function ($constraint){
            $constraint->aspectRatio();
        })->save('storage/images/result.png');

        $newWidth = $scaledImage->getWidth() - $dx;
        $newHeight = $scaledImage->getHeight() - $dy;

        if($newWidth > 400){
            $newWidth = 400;
        }

        if($newHeight > 480){
            $newHeight = 480;
        }

//        dd($scaledImage->getHeight(), $newHeight);

        $scaledImage->crop($newWidth, $newHeight , $dx,  $dy)->save('storage/images/result.png');

        $request->file('image')->storeAs('/images', 'original.png', 'public');

        return redirect()->to('/result');

    }
}
