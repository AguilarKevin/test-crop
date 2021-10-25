<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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

        $scaledWidth = (int)($image->getWidth() * $data['scale']);
        $scaledHeight = (int)($image->getHeight() * $data['scale']);

        $scaledImage = $image->resize($scaledWidth, $scaledHeight, function ($constraint){
            $constraint->aspectRatio();
        });

//        dd($scaledImage->getWidth(), $scaledImage->getHeight());


        $deltaX = (int)($data['dx'] < 0 ? -($data['dx']): 0);
        $deltaY = (int)($data['dy'] < 0 ? -($data['dy']): 0);

        $offsetX =  (int)($data['dx'] < 0 ? -($data['dx']): $data['dx']);
        $offsetY = (int)($data['dy'] < 0 ? -($data['dy']): $data['dy']);

        //width = scaledWidth - offsetLeft
        //height = scaledWidth - offsetTop
        $width = $scaledWidth - $offsetX > 400 ? 400 - $offsetX : $scaledWidth - $offsetX ;
        $height = $scaledHeight - $offsetY > 480 ? 480 - $offsetY : $scaledHeight - $offsetY ;
        

        $scaledImage->crop($width, $height , $deltaX,  $deltaY)->save('storage/images/result.png');

        $request->file('image')->storeAs('/images', 'original.png', 'public');

        return redirect()->to('/result');

    }
}
