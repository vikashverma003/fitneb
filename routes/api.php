<?php

use Illuminate\Http\Request;

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

Route::post('register',['as' => 'register','uses' =>'ApiController@register']);
Route::post('login',['as' => 'login','uses' =>'ApiController@login']);
Route::post('forgot_password',['as' => 'forgot_password','uses' =>'ApiController@forgot_password']);
Route::post('resend_verify_code',['as' => 'resend_verify_code','uses' =>'ApiController@resend_verify_code']);
Route::post('social_login',['as' => 'social_login','uses' =>'ApiController@social_login']);
Route::post('verify_code',['as' => 'verify_code','uses' =>'ApiController@verify_code']);
Route::post('get_profile',['as' => 'get_profile','uses' =>'ApiController@get_profile']);
Route::post('get_profile_other',['as' => 'get_profile_other','uses' =>'ApiController@get_profile_other']);
Route::post('edit_profile',['as' => 'edit_profile','uses' =>'ApiController@edit_profile']);

Route::post('addPost',['as' => 'addPost','uses' =>'ApiController@addPost']);
Route::post('get_posts',['as' => 'get_posts','uses' =>'ApiController@get_posts']);
Route::post('get_all_posts',['as' => 'get_all_posts','uses' =>'ApiController@get_all_posts']);
Route::post('get_images',['as' => 'get_images','uses' =>'ApiController@get_images']);
Route::post('get_videos',['as' => 'get_videos','uses' =>'ApiController@get_videos']);
Route::post('get_news',['as' => 'get_news','uses' =>'ApiController@get_news']);
Route::post('follow_user',['as' => 'follow_user','uses' =>'ApiController@follow_user']);
Route::post('get_follow_list',['as' => 'get_follow_list','uses' =>'ApiController@get_follow_list']);
Route::post('addDiet',['as' => 'addDiet','uses' =>'ApiController@addDiet']);
Route::post('get_diets',['as' => 'get_diets','uses' =>'ApiController@get_diets']);
Route::post('my_diets',['as' => 'my_diets','uses' =>'ApiController@my_diets']);
Route::post('diet_detail',['as' => 'diet_detail','uses' =>'ApiController@diet_detail']);

Route::post('addLike',['as' => 'addLike','uses' =>'ApiController@addLike']);
Route::post('addComment',['as' => 'addComment','uses' =>'ApiController@addComment']);
Route::post('getComment',['as' => 'getComment','uses' =>'ApiController@getComment']);



Route::post('getForYoga',['as' => 'getForYoga','uses' =>'ApiController@getForYoga']);
Route::post('getForWeightLifting',['as' => 'getForWeightLifting','uses' =>'ApiController@getForWeightLifting']);


Route::post('changeAccountType',['as' => 'changeAccountType','uses' =>'ApiController@changeAccountType']);
Route::post('AddBlockUnblock',['as' => 'AddBlockUnblock','uses' =>'ApiController@AddBlockUnblock']);
Route::post('getNewsDetail',['as' => 'getNewsDetail','uses' =>'ApiController@getNewsDetail']);

Route::post('getExerciseDetail',['as' => 'getExerciseDetail','uses' =>'ApiController@getExerciseDetail']);

Route::post('searchUser',['as' => 'searchUser','uses' =>'ApiController@searchUser']);
Route::post('addFavorite',['as' => 'addFavorite','uses' =>'ApiController@addFavorite']);
Route::post('myFavoriteList',['as' => 'myFavoriteList','uses' =>'ApiController@myFavoriteList']);


Route::post('changePassword',['as' => 'changePassword','uses' =>'ApiController@changePassword']);
Route::post('getBlockUserList',['as' => 'getBlockUserList','uses' =>'ApiController@getBlockUserList']);
Route::post('getWorkoutDeatils',['as' => 'getWorkoutDeatils','uses' =>'ApiController@getWorkoutDeatils']);
Route::post('getExcerciseDetails',['as' => 'getExcerciseDetails','uses' =>'ApiController@getExcerciseDetails']);


Route::post('makeGymPartnerOnOff',['as' => 'makeGymPartnerOnOff','uses' =>'ApiController@makeGymPartnerOnOff']);
Route::post('reportUserPost',['as' => 'reportUserPost','uses' =>'ApiController@reportUserPost']);

Route::post('getGoals',['as' => 'getGoals','uses' =>'ApiController@getGoals']);

Route::post('addStat',['as' => 'addStat','uses' =>'ApiController@addStat']);
Route::post('getUserStat',['as' => 'getUserStat','uses' =>'ApiController@getUserStat']);
Route::post('getForRunning',['as' => 'getForRunning','uses' =>'ApiController@getForRunning']);
Route::post('dietDailyDetails',['as' => 'dietDailyDetails','uses' =>'ApiController@dietDailyDetails']);
Route::post('getAllWorkoutDetail',['as' => 'getAllWorkoutDetail','uses' =>'ApiController@getAllWorkoutDetail']);

Route::post('WorkoutDayDetail',['as' => 'WorkoutDayDetail','uses' =>'ApiController@WorkoutDayDetail']);
Route::post('TrainingDayDetail',['as' => 'TrainingDayDetail','uses' =>'ApiController@TrainingDayDetail']);

Route::post('addToMyDiet',['as' => 'addToMyDiet','uses' =>'ApiController@addToMyDiet']);
Route::post('myDiet',['as' => 'myDiet','uses' =>'ApiController@myDiet']);

Route::post('addWorkout',['as' => 'addWorkout','uses' =>'ApiController@addWorkout']);
Route::post('addExercises',['as' => 'addExercises','uses' =>'ApiController@addExercises']);

Route::post('addYourComment',['as' => 'addYourComment','uses' =>'ApiController@addYourComment']);
Route::post('getYourComment',['as' => 'getYourComment','uses' =>'ApiController@getYourComment']);
Route::post('rateWorkout',['as' => 'rateWorkout','uses' =>'ApiController@rateWorkout']);
Route::post('nearbyGymPartner',['as' => 'nearbyGymPartner','uses' =>'ApiController@nearbyGymPartner']);
Route::post('likeGymPartner',['as' => 'likeGymPartner','uses' =>'ApiController@likeGymPartner']);
Route::post('favoriteGymPartner',['as' => 'favoriteGymPartner','uses' =>'ApiController@favoriteGymPartner']);
Route::post('myMatches',['as' => 'myMatches','uses' =>'ApiController@myMatches']);
Route::post('myGymPartnerFavorites',['as' => 'myGymPartnerFavorites','uses' =>'ApiController@myGymPartnerFavorites']);
Route::post('addWorkoutInterest',['as' => 'addWorkoutInterest','uses' =>'ApiController@addWorkoutInterest']);
Route::post('myWorkoutInterest',['as' => 'myWorkoutInterest','uses' =>'ApiController@myWorkoutInterest']);
Route::post('saveState',['as' => 'saveState','uses' =>'ApiController@saveState']);
Route::post('deletePost',['as' => 'deletePost','uses' =>'ApiController@deletePost']);
Route::post('createChat',['as' => 'createChat','uses' =>'ApiController@createChat']);
Route::post('getChatList',['as' => 'getChatList','uses' =>'ApiController@getChatList']);
Route::post('saveLastMessage',['as' => 'saveLastMessage','uses' =>'ApiController@saveLastMessage']);
Route::post('search',['as' => 'search','uses' =>'ApiController@search']);
Route::post('searchDiet',['as' => 'searchDiet','uses' =>'ApiController@searchDiet']);
Route::post('notification_list',['as' => 'notification_list','uses' =>'ApiController@notification_list']);
Route::post('saveStateWY',['as' => 'saveStateWY','uses' =>'ApiController@saveStateWY']);
Route::post('addToMyRunning',['as' => 'addToMyRunning','uses' =>'ApiController@addToMyRunning']);
Route::post('addToMyWeightLifting',['as' => 'addToMyWeightLifting','uses' =>'ApiController@addToMyWeightLifting']);
Route::post('addToMyYoga',['as' => 'addToMyYoga','uses' =>'ApiController@addToMyYoga']);
Route::post('deleteChat',['as' => 'deleteChat','uses' =>'ApiController@deleteChat']);
Route::post('trainerRequest',['as' => 'deleteChat','uses' =>'ApiController@trainerRequest']);






