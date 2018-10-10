<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

// Route::get('/endpoint', function (Request $request) {
//     //
// });
Route::post('/add', 'Naif\MailchimpTool\Http\Controllers\MailChimpController@add');
Route::post('/send', 'Naif\MailchimpTool\Http\Controllers\MailChimpController@send');
Route::post('/delete', 'Naif\MailchimpTool\Http\Controllers\MailChimpController@delete');
Route::get('/subscribers_count', 'Naif\MailchimpTool\Http\Controllers\MailChimpController@subscribersCount');
Route::get('/subscribers', 'Naif\MailchimpTool\Http\Controllers\MailChimpController@subscribers');