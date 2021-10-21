<?php

use Illuminate\Support\Facades\Route;
use Intervention\Image\ImageManagerStatic as Image;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/result',function (){
    return view('result');
});

Route::post('/test', function (\Illuminate\Http\Request $request){


    $dx = $request->get('dx');
    $dy = $request->get('dy');
    $scale = $request->get('scale');
    dd(($dx*$scale),$dy*$scale,);

    $path = $request->file('image')->storeAs('images','result.png', 'public');


    $img = Image::make($path)->crop(400, 480, $dx, $dy);

    return $img->response('png');

//    $image = Image::make('public/storage/images/result.png')->resize(300, 200);
//    return redirect('/result');
});
