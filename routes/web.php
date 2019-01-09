<?php

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

use Illuminate\Http\Request;
use App\CroppedImages;
Route::get('/', function () {
    return view('index');
})->name("home");
Route::post('/submit', function (Request $request) {
    try{
        if($request->hasFile('imageFile') && $request->get('imageFileExtenstion')){
            $file = $request->file('imageFile');
            $extension = '';
            if(in_array($request->get('imageFileExtenstion'),['image/jpeg','image/jpg'])){
                $extension = '.jpeg';
            }else 
            if($request->get('imageFileExtenstion') == 'image/png'){
                $extension = '.png';   
            }
            $fileName = $file->getClientOriginalName().str_random(40).$extension;
            $destinationPath = storage_path('app\public\croppedImages');
            $file->move($destinationPath, $fileName);
            CroppedImages::create([
                'image_path'    => url('storage/croppedImages/'.$fileName)
            ]);
        }else{
            return response()->json(["No Image File Uploaded"]);
        }
    }catch (\Exception $e){ 
       return response()->json([$e->getMessage()]);
    }
   return response()->json(["Image Added Successfully"]);
})->name("submit");
