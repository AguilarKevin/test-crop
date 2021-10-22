<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class TestController extends Controller
{

    function crop(Request $request){

        $data = $this->validate($request, [
            'image' => 'required|image',
            'dx' => 'required',
            'dy' => 'required',
            'scale' => 'required'
        ]);

        $image = Image::make($data['image']->getRealPath());

        $scaledProps = [
            'width' => $image->getWidth()*$data['scale'],
            'height'=> $image->getHeight()*$data['scale'],
            'dx' => (int)($data['dx'] * $data['scale']),
            'dy' => (int)($data['dy'] * $data['scale'])
        ];

        $scaledImage = $image->resize($scaledProps['width'], $scaledProps['height'], function ($constraint){
            $constraint->aspectRatio();
        });

        $scaledImage->crop(400,480, $scaledProps['dx'],  $scaledProps['dy'])->save('storage/images/result.png');

        $request->file('image')->storeAs('/images', 'original.png', 'public');


        return redirect()->to('/result');
    }
}
