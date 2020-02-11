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

use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $databaseValues = DB::table('product_data')->paginate(100);
    return view('craigsitems', ['results' => $databaseValues]);
});
Route::get('/edit/{id}', function ($id) {
    $selectedItem = DB::table('product_data')->find($id);
    return view('editcraigslistitem', ['selected' => $selectedItem]);
});
Route::post('form-submit', function () {
    $title = request('Title');
    $imagelink = request('ImageLink');
    $price = request('Price');
    $description = request('Description');
    $location = request('LocationLink');
    $id = request('id');
    DB::table('product_data')->where('id', $id)
        ->update(['Title' => $title, 'ImageLink' => $imagelink, 
        'Price' => $price, 'Description' => $description, 
        'LocationLink' => $location]);
    $returnDigit = 0;
    if ($id <= 100) $returnDigit = 1;
    else $returnDigit = $id[0] + 1;
    return redirect('/?page=' . $returnDigit);
});