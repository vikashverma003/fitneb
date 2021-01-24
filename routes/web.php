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


//Admin Part
Route::get('/', function () {
    return view('login');
});

Route::post('adminlogin','AdminController@adminlogin');
Route::get('dashboard','AdminController@dashboard');
Route::get('logout','AdminController@logout');

Route::get('users_list','AdminController@users_list');
Route::get('user_delete/{id}','AdminController@user_delete');
Route::get('user_edit/{id}','AdminController@user_edit');
Route::post('user_update','AdminController@user_update');
Route::get('user_profile/{id}','AdminController@user_profile');

Route::get('trainers_list','AdminController@trainers_list');
Route::get('trainer_delete/{id}','AdminController@trainer_delete');
Route::get('trainer_edit/{id}','AdminController@trainer_edit');
Route::post('trainer_update','AdminController@trainer_update');
Route::get('trainer_profile/{id}','AdminController@trainer_profile');
Route::get('add_trainer','AdminController@add_trainer');
Route::post('add_trainer_action','AdminController@add_trainer_action');


Route::get('add_goals','AdminController@add_goals');
Route::post('add_goals_action','AdminController@add_goals_action');
Route::get('goals_list','AdminController@goals_list');
Route::get('goals_delete/{id}','AdminController@goals_delete');
Route::get('goals_edit/{id}','AdminController@goals_edit');
Route::post('goals_update','AdminController@goals_update');

Route::get('we_yoga','AdminController@we_yoga');
Route::get('we_weightlift','AdminController@we_weightlift');
Route::get('trainer_request_list','AdminController@trainer_request_list');
Route::post('respond_trainer_request','AdminController@respond_trainer_request');

//Trainer Part
Route::get('/trainer/login', function () {
    return view('trainer_panel/login');
});
Route::post('trainerlogin','TrainerController@trainerlogin');
Route::get('trainer/dashboard','TrainerController@dashboard');
Route::get('trainer/logout','TrainerController@logout');

//training module
Route::get('trainer/training_list','TrainerController@training_list');
Route::get('trainer/wl_exercise_list','TrainerController@wl_exercise_list');
Route::get('trainer/wl_workout_list','TrainerController@wl_workout_list');
Route::get('trainer/yoga_exercise_list','TrainerController@yoga_exercise_list');
Route::get('trainer/yoga_workout_list','TrainerController@yoga_workout_list');
Route::get('trainer/diet_list','TrainerController@diet_list');
Route::get('trainer/add_training','TrainerController@add_training');
Route::get('trainer/training_profile/{id}','TrainerController@training_profile');
Route::post('trainer/add_training_action','TrainerController@add_training_action');
Route::get('trainer/add_training_workout/{id}','TrainerController@add_training_workout');
Route::post('trainer/add_training_workout_action','TrainerController@add_training_workout_action');
Route::get('trainer/training_delete/{id}','TrainerController@training_delete');
Route::get('trainer/training_edit/{id}','TrainerController@training_edit');
Route::post('trainer/training_update','TrainerController@training_update');
Route::get('trainer/training_worouts_delete/{id}','TrainerController@training_worouts_delete');
Route::get('trainer/training_worouts_edit/{id}','TrainerController@training_worouts_edit');
Route::post('trainer/training_worouts_update','TrainerController@training_worouts_update');
Route::get('trainer/add_diet','TrainerController@add_diet');
Route::post('trainer/add_diet_action','TrainerController@add_diet_action');
Route::get('trainer/add_weightlift_exercise','TrainerController@add_weightlift_exercise');
Route::get('trainer/add_weightlift_workout','TrainerController@add_weightlift_workout');
Route::post('trainer/add_weightlift_exercise_action','TrainerController@add_weightlift_exercise_action');
Route::post('trainer/add_weightlift_workout_action','TrainerController@add_weightlift_workout_action');

Route::get('trainer/add_yoga_exercise','TrainerController@add_yoga_exercise');
Route::get('trainer/add_yoga_workout','TrainerController@add_yoga_workout');
Route::post('trainer/add_yoga_exercise_action','TrainerController@add_yoga_exercise_action');
Route::post('trainer/add_yoga_workout_action','TrainerController@add_yoga_workout_action');

Route::get('trainer/wl_exercise_delete/{id}','TrainerController@wl_exercise_delete');
Route::get('trainer/wl_exercise_edit/{id}','TrainerController@wl_exercise_edit');
Route::post('trainer/wl_exercise_update','TrainerController@wl_exercise_update');
Route::get('trainer/wl_exercise_profile/{id}','TrainerController@wl_exercise_profile');

Route::get('trainer/wl_workout_delete/{id}','TrainerController@wl_workout_delete');
Route::get('trainer/wl_workout_edit/{id}','TrainerController@wl_workout_edit');
Route::post('trainer/wl_workout_update','TrainerController@wl_workout_update');
Route::get('trainer/wl_workout_profile/{id}','TrainerController@wl_workout_profile');

Route::get('trainer/yoga_exercise_delete/{id}','TrainerController@yoga_exercise_delete');
Route::get('trainer/yoga_exercise_edit/{id}','TrainerController@yoga_exercise_edit');
Route::post('trainer/yoga_exercise_update','TrainerController@yoga_exercise_update');
Route::get('trainer/yoga_exercise_profile/{id}','TrainerController@yoga_exercise_profile');

Route::get('trainer/yoga_workout_delete/{id}','TrainerController@yoga_workout_delete');
Route::get('trainer/yoga_workout_edit/{id}','TrainerController@yoga_workout_edit');
Route::post('trainer/yoga_workout_update','TrainerController@yoga_workout_update');
Route::get('trainer/yoga_workout_profile/{id}','TrainerController@yoga_workout_profile');

Route::get('trainer/diet_delete/{id}','TrainerController@diet_delete');
Route::get('trainer/diet_edit/{id}','TrainerController@diet_edit');
Route::post('trainer/diet_update','TrainerController@diet_update');
Route::get('trainer/diet_profile/{id}','TrainerController@diet_profile');

Route::any('trainer/add_wl_ex_daywise/{id}','TrainerController@add_wl_ex_daywise');
Route::post('trainer/add_wl_ex_daywise_action','TrainerController@add_wl_ex_daywise_action');
Route::get('trainer/wl_ex_daywise_delete/{id}','TrainerController@wl_ex_daywise_delete');
Route::get('trainer/wl_ex_daywise_edit/{id}','TrainerController@wl_ex_daywise_edit');
Route::post('trainer/wl_ex_daywise_update','TrainerController@wl_ex_daywise_update');

Route::any('trainer/add_wl_wo_daywise/{id}','TrainerController@add_wl_wo_daywise');
Route::post('trainer/add_wl_wo_daywise_action','TrainerController@add_wl_wo_daywise_action');
Route::get('trainer/wl_wo_daywise_delete/{id}','TrainerController@wl_wo_daywise_delete');
Route::get('trainer/wl_wo_daywise_edit/{id}','TrainerController@wl_wo_daywise_edit');
Route::post('trainer/wl_wo_daywise_update','TrainerController@wl_wo_daywise_update');

Route::any('trainer/add_yoga_ex_daywise/{id}','TrainerController@add_yoga_ex_daywise');
Route::post('trainer/add_yoga_ex_daywise_action','TrainerController@add_yoga_ex_daywise_action');
Route::get('trainer/yoga_ex_daywise_delete/{id}','TrainerController@yoga_ex_daywise_delete');
Route::get('trainer/yoga_ex_daywise_edit/{id}','TrainerController@yoga_ex_daywise_edit');
Route::post('trainer/yoga_ex_daywise_update','TrainerController@yoga_ex_daywise_update');

Route::any('trainer/add_yoga_wo_daywise/{id}','TrainerController@add_yoga_wo_daywise');
Route::post('trainer/add_yoga_wo_daywise_action','TrainerController@add_yoga_wo_daywise_action');
Route::get('trainer/yoga_wo_daywise_delete/{id}','TrainerController@yoga_wo_daywise_delete');
Route::get('trainer/yoga_wo_daywise_edit/{id}','TrainerController@yoga_wo_daywise_edit');
Route::post('trainer/yoga_wo_daywise_update','TrainerController@yoga_wo_daywise_update');

Route::any('trainer/add_diet_daywise/{id}','TrainerController@add_diet_daywise');
Route::post('trainer/add_diet_daywise_action','TrainerController@add_diet_daywise_action');
Route::get('trainer/diet_daywise_delete/{id}','TrainerController@diet_daywise_delete');
Route::get('trainer/diet_daywise_edit/{id}','TrainerController@diet_daywise_edit');
Route::post('trainer/diet_daywise_update','TrainerController@diet_daywise_update');


