<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Room;
use App\Models\Type;
use App\Models\Customer;
use App\Http\Controllers\Api\SearchController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('rooms', function() {
    return Room::all();
});
 
Route::get('room_types', function() {
    return Type::all();
});

Route::get('customers', function() {
    return Customer::all();
});

Route::get('/rooms', [SearchController::class, 'rooms']);


Route::post('/search', [SearchController::class, 'search_room']);
/*{
    "check_in":"2023/06/15",
    "check_out":"2023/06/30",
    "count_person":3
}*/


