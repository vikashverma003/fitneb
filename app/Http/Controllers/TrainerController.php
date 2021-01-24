<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin as Admin;
use App\User as User;
use App\Trainer as Trainer;
use App\Workout as Workout;
use App\WorkoutDetail as WorkoutDetail;
use App\TrainingDetail as TrainingDetail;
use App\Goal as Goal;
use App\Diet as Diet;
use App\DietDetail as DietDetail;
use Validator;
use Hash;
use Illuminate\Support\Facades\Mail;

class TrainerController extends Controller
{
    public function trainerlogin(Request $request){
    	$messages = [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required'
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required' 

        ], $messages);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else{
            $users = Trainer::where('email','=',$request->input('email'))->first();
            
            if($users){
            	$hashedPassword = $users['password'];
            	if (Hash::check($request->input('password'), $hashedPassword)) {
            		$request->session()->put('trainer_user_id', $users['_id']);
                	return redirect('trainer/dashboard');
            	}
            	else{
            		return redirect()->back()->with("er_status","Wrong Email ID or Password")->withInput();
            	}
            }
            else{
                return redirect()->back()->with("er_status","Wrong Email ID or Password")->withInput();
            }
        }
    }

    public function dashboard(Request $request){
    	$user_id = $request->session()->get('trainer_user_id');
    	if($user_id){
    		return view('trainer_panel/dashboard');
    	}
    	else{
    		return redirect('/')->with('er_status','Session Expired. Please Login again.');
    	}
    }

    public function logout(Request $request){
    	$request->session()->forget('trainer_user_id');
    	return redirect('/trainer/login');
    }

    public function training_list(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $trainings = Workout::where(['page_for'=>'running'])->where(['type'=>'workout'])->where(['user_id'=>$user_id])->orderBy('_id','desc')->get();
            if($trainings){
                return view('trainer_panel/training/list',['trainings' => $trainings]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function wl_exercise_list(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $wl_exercises = Workout::where(['page_for'=>'weightlifting'])->where(['type'=>'exercise'])->where(['user_id'=>$user_id])->orderBy('_id','desc')->get();
            if($wl_exercises){
                return view('trainer_panel/weightlifting/e_list',['wl_exercises' => $wl_exercises]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function wl_workout_list(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $wl_workouts = Workout::where(['page_for'=>'weightlifting'])->where(['type'=>'workout'])->where(['user_id'=>$user_id])->orderBy('_id','desc')->get();
            if($wl_workouts){
                return view('trainer_panel/weightlifting/w_list',['wl_workouts' => $wl_workouts]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_exercise_list(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $yoga_exercises = Workout::where(['page_for'=>'yoga'])->where(['type'=>'exercise'])->where(['user_id'=>$user_id])->orderBy('_id','desc')->get();
            if($yoga_exercises){
                return view('trainer_panel/yoga/e_list',['yoga_exercises' => $yoga_exercises]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_workout_list(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $yoga_workouts = Workout::where(['page_for'=>'yoga'])->where(['type'=>'workout'])->where(['user_id'=>$user_id])->orderBy('_id','desc')->get();
            if($yoga_workouts){
                return view('trainer_panel/yoga/w_list',['yoga_workouts' => $yoga_workouts]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_training(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/training/add');
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_training_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'exercises.required' => 'Exercises field is required.',
                'time.required' => 'Time is required.',
                'image.required' => 'Image is required.',
            ];

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'exercises' => 'required',
                'time' => 'required',
                'image' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $training_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $training_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $training_image);
                }
                $insertData = [
                    'user_id' => $user_id,
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'type' => "workout",
                    'page_for' => "running",
                    'image' => $training_image,
                    'exercises'=>$request->input('exercises'),
                    'time'=>$request->input('time'),
                    "rating"=>"",
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  Workout::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/training_list')->with('su_status', 'Training Added Sucessfully!');
                } else {
                    return redirect('trainer/add_training')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function training_profile(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $training = Workout::where(['_id'=>$id])->first();
            if($training){
                $t_workouts = TrainingDetail::where(['training_id'=>$training['_id']])->get();
                return view('trainer_panel/training/profile',['training' => $training, 't_workouts'=>$t_workouts]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_training_workout(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/training/add_workout', ['id'=>$id]);
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_training_workout_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'title.required' => 'Title is required.',
                'subtitle.required' => 'Subtitle is required.',
                'miles.required' => 'Miles field is required.',
                'time.required' => 'Time is required.',
                'image.required' => 'Image is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'title' => 'required',
                'subtitle' => 'required',
                'miles' => 'required',
                'time' => 'required',
                'image' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $training_workout_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $training_workout_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/image');
                    $image->move($destinationPath, $training_workout_image);
                }
                $insertData = [
                    'training_id' => $request->input('training_id'),
                    'day' => $request->input('day'),
                    'title' => $request->input('title'),
                    'image' => $training_workout_image,
                    'subtitle' => $request->input('subtitle'),
                    'time'=>$request->input('time'),
                    'miles'=>$request->input('miles'),
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  TrainingDetail::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/training_list')->with('su_status', 'Training Workout Added Sucessfully!');
                } else {
                    return redirect('trainer/training_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function training_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $training = Workout::where(['_id'=>$id])->delete();
            if($training){
                return redirect('trainer/training_list')->with('su_status','Training Deleted Successfully');
            }
            else{
                return redirect('trainer/training_list')->with('er_status','Training Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function training_worouts_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $training_workout = TrainingDetail::where(['_id'=>$id])->delete();
            if($training_workout){
                return redirect('trainer/training_list')->with('su_status','Training Workout Deleted Successfully');
            }
            else{
                return redirect('trainer/training_list')->with('er_status','Training Workout Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function training_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $training = Workout::where(['_id'=>$id])->first();
            if($training){
                return view('trainer_panel/training/edit',['training'=>$training]);
            }
            else{
                return redirect('trainer/training_list')->with('er_status','Training Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function training_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'exercises.required' => 'Exercises field is required.',
                'time.required' => 'Time is required.',
            ];

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'exercises' => 'required',
                'time' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $training_id = $request->input('training_id');
                $training_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $training_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $training_image);
                }
                else{
                    $trainerdetails = Workout::where(['_id'=>$training_id])->select('image')->first();
                    $training_image = $trainerdetails['image'];
                }
                $updateData = [
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'image' => $training_image,
                    'exercises'=>$request->input('exercises'),
                    'time'=>$request->input('time'),
                    'updated_at'=> date('d-m-y h:i:s')
                ];
   
                $update =  Workout::where(['_id'=>$training_id])->update($updateData);

                if ($update) {
                     return redirect('trainer/training_list')->with('su_status', 'Training updated Sucessfully!');
                } else {
                    return redirect('trainer/training_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function training_worouts_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $training_workout = TrainingDetail::where(['_id'=>$id])->first();
            if($training_workout){
                return view('trainer_panel/training/edit_workout',['training_workout'=>$training_workout]);
            }
            else{
                return redirect('trainer/training_list')->with('er_status','Training Workout Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function training_worouts_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'title.required' => 'Title is required.',
                'subtitle.required' => 'Subtitle is required.',
                'miles.required' => 'Miles field is required.',
                'time.required' => 'Time is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'title' => 'required',
                'subtitle' => 'required',
                'miles' => 'required',
                'time' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $trainingWorkout_id = $request->input('trainingWorkout_id');
                $training_workout_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $training_workout_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/image');
                    $image->move($destinationPath, $training_workout_image);
                }
                else{
                    $trainerdetails = TrainingDetail::where(['_id'=>$trainingWorkout_id])->select('image')->first();
                    $training_workout_image = $trainerdetails['image'];
                }
                $updateData = [
                    'day' => $request->input('day'),
                    'title' => $request->input('title'),
                    'image' => $training_workout_image,
                    'subtitle' => $request->input('subtitle'),
                    'time'=>$request->input('time'),
                    'miles'=>$request->input('miles'),
                    'updated_at'=> date('d-m-y h:i:s')
                ];
   
                $update =  TrainingDetail::where(['_id'=>$trainingWorkout_id])->update($updateData);

                if ($update) {
                     return redirect('trainer/training_list')->with('su_status', 'Training Workout updated Sucessfully!');
                } else {
                    return redirect('trainer/training_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function diet_list(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $diets = Diet::where(['user_id'=>$user_id])->orderBy('_id','desc')->get();
            if($diets){
                return view('trainer_panel/diet/list',['diets' => $diets]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_diet(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/diet/add');
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_diet_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'name.required' => 'Name is required.',
                'description.required' => 'Description is required.',
                'period.required' => 'Period field is required.',
                'amount.required' => 'Amount is required.',
                'image.required' => 'Image is required.',
            ];

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'period' => 'required',
                'amount' => 'required',
                'image' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $diet_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $diet_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/diet');
                    $image->move($destinationPath, $diet_image);
                }
                $insertData = [
                    'user_id' => $user_id,
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'period' => $request->input('period'),
                    'amount' => $request->input('amount'),
                    'image' => $diet_image,
                    "rating"=>"0",
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  Diet::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/diet_list')->with('su_status', 'Diet Added Sucessfully!');
                } else {
                    return redirect('trainer/add_diet')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function add_weightlift_exercise(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/weightlifting/add_e');
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_weightlift_exercise_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'category.required' => 'Category is required.',
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'exercises.required' => 'Exercises field is required.',
                'time.required' => 'Time is required.',
                'image.required' => 'Image is required.',
            ];

            $validator = Validator::make($request->all(), [
                'category' => 'required',
                'title' => 'required',
                'description' => 'required',
                'exercises' => 'required',
                'time' => 'required',
                'image' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $exercise_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $exercise_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $exercise_image);
                }
                $insertData = [
                    'user_id' => $user_id,
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'type' => "exercise",
                    'category' => $request->input('category'),
                    'page_for' => "weightlifting",
                    'image' => $exercise_image,
                    'exercises'=>$request->input('exercises'),
                    'time'=>$request->input('time'),
                    "rating"=>"",
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  Workout::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/wl_exercise_list')->with('su_status', 'Exercise Added Sucessfully!');
                } else {
                    return redirect('trainer/add_weightlift_exercise')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function add_weightlift_workout(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/weightlifting/add_w');
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_weightlift_workout_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'exercises.required' => 'Exercises field is required.',
                'time.required' => 'Time is required.',
                'image.required' => 'Image is required.',
            ];

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'exercises' => 'required',
                'time' => 'required',
                'image' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $workout_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $workout_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $workout_image);
                }
                $insertData = [
                    'user_id' => $user_id,
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'type' => "workout",
                    'page_for' => "weightlifting",
                    'image' => $workout_image,
                    'exercises'=>$request->input('exercises'),
                    'time'=>$request->input('time'),
                    "rating"=>"",
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  Workout::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/wl_workout_list')->with('su_status', 'Workout Added Sucessfully!');
                } else {
                    return redirect('trainer/add_weightlift_workout')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function add_yoga_exercise(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/yoga/add_e');
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_yoga_exercise_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'category.required' => 'Category is required.',
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'exercises.required' => 'Exercises field is required.',
                'time.required' => 'Time is required.',
                'image.required' => 'Image is required.',
            ];

            $validator = Validator::make($request->all(), [
                'category' => 'required',
                'title' => 'required',
                'description' => 'required',
                'exercises' => 'required',
                'time' => 'required',
                'image' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $exercise_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $exercise_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $exercise_image);
                }
                $insertData = [
                    'user_id' => $user_id,
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'type' => "exercise",
                    'category' => $request->input('category'),
                    'page_for' => "yoga",
                    'image' => $exercise_image,
                    'exercises'=>$request->input('exercises'),
                    'time'=>$request->input('time'),
                    "rating"=>"",
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  Workout::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/yoga_exercise_list')->with('su_status', 'Exercise Added Sucessfully!');
                } else {
                    return redirect('trainer/add_yoga_exercise')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function add_yoga_workout(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/yoga/add_w');
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_yoga_workout_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'exercises.required' => 'Exercises field is required.',
                'time.required' => 'Time is required.',
                'image.required' => 'Image is required.',
            ];

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'exercises' => 'required',
                'time' => 'required',
                'image' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $workout_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $workout_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $workout_image);
                }
                $insertData = [
                    'user_id' => $user_id,
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'type' => "workout",
                    'page_for' => "yoga",
                    'image' => $workout_image,
                    'exercises'=>$request->input('exercises'),
                    'time'=>$request->input('time'),
                    "rating"=>"",
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  Workout::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/yoga_workout_list')->with('su_status', 'Workout Added Sucessfully!');
                } else {
                    return redirect('trainer/add_yoga_workout')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function wl_exercise_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $wl_exercise = Workout::where(['_id'=>$id])->delete();
            if($wl_exercise){
                return redirect('trainer/wl_exercise_list')->with('su_status','Weightlifting Exercise Deleted Successfully');
            }
            else{
                return redirect('trainer/wl_exercise_list')->with('er_status','Weightlifting Exercise Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function wl_exercise_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $wl_exercise = Workout::where(['_id'=>$id])->first();
            if($wl_exercise){
                return view('trainer_panel/weightlifting/wl_exercise_edit',['wl_exercise'=>$wl_exercise]);
            }
            else{
                return redirect('trainer/wl_exercise_list')->with('er_status','Weightlifting Exercise Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function wl_exercise_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'category.required' => 'Category is required.',
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'exercises.required' => 'Exercises field is required.',
                'time.required' => 'Time is required.',
            ];

            $validator = Validator::make($request->all(), [
                'category' => 'required',
                'title' => 'required',
                'description' => 'required',
                'exercises' => 'required',
                'time' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $exercise_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $exercise_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $exercise_image);
                }
                else{
                    $workoutDetail =  Workout::where(['_id'=>$request->input('wl_exercise_id')])->select('image')->first();
                    $exercise_image = $workoutDetail['image'];
                }
                $updateData = [
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'category' => $request->input('category'),
                    'image' => $exercise_image,
                    'exercises'=>$request->input('exercises'),
                    'time'=>$request->input('time'),
                    'updated_at' => date('Y-m-d h:i:s')
                ];
   
                $update =  Workout::where(['_id'=>$request->input('wl_exercise_id')])->update($updateData);

                if ($update) {
                     return redirect('trainer/wl_exercise_list')->with('su_status', 'Exercise updated Sucessfully!');
                } else {
                    return redirect('trainer/wl_exercise_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function wl_workout_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $wl_workout = Workout::where(['_id'=>$id])->delete();
            if($wl_workout){
                return redirect('trainer/wl_workout_list')->with('su_status','Weightlifting Workout Deleted Successfully');
            }
            else{
                return redirect('trainer/wl_workout_list')->with('er_status','Weightlifting Workout Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function wl_workout_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $wl_workout = Workout::where(['_id'=>$id])->first();
            if($wl_workout){
                return view('trainer_panel/weightlifting/wl_workout_edit',['wl_workout'=>$wl_workout]);
            }
            else{
                return redirect('trainer/wl_workout_list')->with('er_status','Weightlifting Workout Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function wl_workout_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'exercises.required' => 'Exercises field is required.',
                'time.required' => 'Time is required.',
            ];

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'exercises' => 'required',
                'time' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $workout_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $workout_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $workout_image);
                }
                else{
                    $workoutDetail =  Workout::where(['_id'=>$request->input('wl_workout_id')])->select('image')->first();
                    $workout_image = $workoutDetail['image'];
                }
                $updateData = [
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'image' => $workout_image,
                    'exercises'=>$request->input('exercises'),
                    'time'=>$request->input('time'),
                    'updated_at' => date('Y-m-d h:i:s')
                ];
   
                $update =  Workout::where(['_id'=>$request->input('wl_workout_id')])->update($updateData);

                if ($update) {
                     return redirect('trainer/wl_workout_list')->with('su_status', 'Workout Updated Sucessfully!');
                } else {
                    return redirect('trainer/wl_workout_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function yoga_exercise_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $yoga_exercise = Workout::where(['_id'=>$id])->delete();
            if($yoga_exercise){
                return redirect('trainer/yoga_exercise_list')->with('su_status','Yoga Exercise Deleted Successfully');
            }
            else{
                return redirect('trainer/yoga_exercise_list')->with('er_status','Yoga Exercise Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_exercise_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $yoga_exercise = Workout::where(['_id'=>$id])->first();
            if($yoga_exercise){
                return view('trainer_panel/yoga/yoga_exercise_edit',['yoga_exercise'=>$yoga_exercise]);
            }
            else{
                return redirect('trainer/yoga_exercise_list')->with('er_status','Yoga Exercise Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_exercise_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'category.required' => 'Category is required.',
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'exercises.required' => 'Exercises field is required.',
                'time.required' => 'Time is required.',
            ];

            $validator = Validator::make($request->all(), [
                'category' => 'required',
                'title' => 'required',
                'description' => 'required',
                'exercises' => 'required',
                'time' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $exercise_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $exercise_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $exercise_image);
                }
                else{
                    $exerciseDetail =  Workout::where(['_id'=>$request->input('yoga_exercise_id')])->select('image')->first();
                    $exercise_image = $exerciseDetail['image'];
                }
                $updateData = [
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'category' => $request->input('category'),
                    'image' => $exercise_image,
                    'exercises'=>$request->input('exercises'),
                    'time'=>$request->input('time'),
                    'updated_at' => date('Y-m-d h:i:s')
                ];
   
                $update =  Workout::where(['_id'=>$request->input('yoga_exercise_id')])->update($updateData);

                if ($update) {
                     return redirect('trainer/yoga_exercise_list')->with('su_status', 'Exercise Updated Sucessfully!');
                } else {
                    return redirect('trainer/yoga_exercise_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function yoga_workout_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $yoga_workout = Workout::where(['_id'=>$id])->delete();
            if($yoga_workout){
                return redirect('trainer/yoga_workout_list')->with('su_status','Yoga Workout Deleted Successfully');
            }
            else{
                return redirect('trainer/yoga_workout_list')->with('er_status','Yoga Workout Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_workout_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $yoga_workout = Workout::where(['_id'=>$id])->first();
            if($yoga_workout){
                return view('trainer_panel/yoga/yoga_workout_edit',['yoga_workout'=>$yoga_workout]);
            }
            else{
                return redirect('trainer/yoga_workout_list')->with('er_status','Yoga Exercise Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_workout_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'exercises.required' => 'Exercises field is required.',
                'time.required' => 'Time is required.',
            ];

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'exercises' => 'required',
                'time' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $workout_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $workout_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $workout_image);
                }
                else{
                    $workoutDetail =  Workout::where(['_id'=>$request->input('yoga_workout_id')])->select('image')->first();
                    $workout_image = $workoutDetail['image'];
                }
                $updateData = [
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'image' => $workout_image,
                    'exercises'=>$request->input('exercises'),
                    'time'=>$request->input('time'),
                    'updated_at' => date('Y-m-d h:i:s')
                ];
   
                $update =  Workout::where(['_id'=>$request->input('yoga_workout_id')])->update($updateData);

                if ($update) {
                     return redirect('trainer/yoga_workout_list')->with('su_status', 'Workout Updated Sucessfully!');
                } else {
                    return redirect('trainer/yoga_workout_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function diet_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $diet = Diet::where(['_id'=>$id])->delete();
            if($diet){
                return redirect('trainer/diet_list')->with('su_status','Diet Deleted Successfully');
            }
            else{
                return redirect('trainer/diet_list')->with('er_status','Diet Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function diet_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $diet = Diet::where(['_id'=>$id])->first();
            if($diet){
                return view('trainer_panel/diet/diet_edit',['diet'=>$diet]);
            }
            else{
                return redirect('trainer/diet_list')->with('er_status','Diet Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function diet_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'name.required' => 'Name is required.',
                'description.required' => 'Description is required.',
                'period.required' => 'Period field is required.',
                'amount.required' => 'Amount is required.',
            ];

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'period' => 'required',
                'amount' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $diet_image = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $diet_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/diet');
                    $image->move($destinationPath, $diet_image);
                }
                else{
                    $dietDetail =  Diet::where(['_id'=>$request->input('diet_id')])->select('image')->first();
                    $diet_image = $dietDetail['image'];
                }
                $updateData = [
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'period' => $request->input('period'),
                    'amount' => $request->input('amount'),
                    'image' => $diet_image,
                    'updated_at' => date('Y-m-d h:i:s')
                ];
   
                $update =  Diet::where(['_id'=>$request->input('diet_id')])->update($updateData);

                if ($update) {
                     return redirect('trainer/diet_list')->with('su_status', 'Diet Updated Sucessfully!');
                } else {
                    return redirect('trainer/diet_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function wl_exercise_profile(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $wl_exercise = Workout::where(['_id'=>$id])->first();
            if($wl_exercise){
                $wl_exercise_details = WorkoutDetail::where(['workout_id'=>$wl_exercise['_id']])->get();
                return view('trainer_panel/weightlifting/wl_exercise_profile',['wl_exercise' => $wl_exercise, 'wl_exercise_details'=>$wl_exercise_details]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function wl_workout_profile(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $wl_workout = Workout::where(['_id'=>$id])->first();
            if($wl_workout){
                $wl_workout_details = WorkoutDetail::where(['workout_id'=>$wl_workout['_id']])->get();
                return view('trainer_panel/weightlifting/wl_workout_profile',['wl_workout' => $wl_workout, 'wl_workout_details'=>$wl_workout_details]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_exercise_profile(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $yoga_exercise = Workout::where(['_id'=>$id])->first();
            if($yoga_exercise){
                $yoga_exercise_details = WorkoutDetail::where(['workout_id'=>$yoga_exercise['_id']])->get();
                return view('trainer_panel/yoga/yoga_exercise_profile',['yoga_exercise' => $yoga_exercise, 'yoga_exercise_details'=>$yoga_exercise_details]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_workout_profile(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $yoga_workout = Workout::where(['_id'=>$id])->first();
            if($yoga_workout){
                $yoga_workout_details = WorkoutDetail::where(['workout_id'=>$yoga_workout['_id']])->get();
                return view('trainer_panel/yoga/yoga_workout_profile',['yoga_workout' => $yoga_workout, 'yoga_workout_details'=>$yoga_workout_details]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function diet_profile(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $diet = Diet::where(['_id'=>$id])->first();
            if($diet){
                $diet_details = DietDetail::where(['diet_id'=>$diet['_id']])->get();
                return view('trainer_panel/diet/diet_profile',['diet' => $diet, 'diet_details'=>$diet_details]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_wl_ex_daywise(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/weightlifting/add_wl_ex_daywise', ['id'=>$id]);
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_wl_ex_daywise_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'title.required' => 'Title is required.',
                'daily_desc.required' => 'Description is required.',
                'reps.required' => 'Reps field is required.',
                'rest_time.required' => 'Rest Time is required.',
                'image.required' => 'Image is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'title' => 'required',
                'daily_desc' => 'required',
                'reps' => 'required',
                'rest_time' => 'required',
                'image' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $image1 = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $image1 = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/image');
                    $image->move($destinationPath, $image1);
                }
                $insertData = [
                    'workout_id' => $request->input('wl_excercise_id'),
                    'day' => $request->input('day'),
                    'title' => $request->input('title'),
                    'image' => $image1,
                    'daily_desc' => $request->input('daily_desc'),
                    'reps'=>$request->input('reps'),
                    'rest_time'=>$request->input('rest_time'),
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  WorkoutDetail::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/wl_exercise_list')->with('su_status', 'Excercise Added Sucessfully!');
                } else {
                    return redirect('trainer/wl_exercise_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function wl_ex_daywise_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $wl_ex_daywise_detail = WorkoutDetail::where(['_id'=>$id])->first();
            if($wl_ex_daywise_detail){
                return view('trainer_panel/weightlifting/edit_wl_ex_daywise',['wl_ex_daywise_detail'=>$wl_ex_daywise_detail]);
            }
            else{
                return redirect('trainer/wl_exercise_list')->with('er_status','Excercise Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function wl_ex_daywise_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'title.required' => 'Title is required.',
                'daily_desc.required' => 'Description is required.',
                'reps.required' => 'Reps field is required.',
                'rest_time.required' => 'Time is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'title' => 'required',
                'daily_desc' => 'required',
                'reps' => 'required',
                'rest_time' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $wl_ex_daywise_id = $request->input('wl_ex_daywise_id');
                $image1 = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $image1 = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/image');
                    $image->move($destinationPath, $image1);
                }
                else{
                    $trainerdetails = WorkoutDetail::where(['_id'=>$wl_ex_daywise_id])->select('image')->first();
                    $image1 = $trainerdetails['image'];
                }
                $updateData = [
                    'day' => $request->input('day'),
                    'title' => $request->input('title'),
                    'image' => $image1,
                    'daily_desc' => $request->input('daily_desc'),
                    'reps'=>$request->input('reps'),
                    'rest_time'=>$request->input('rest_time'),
                    'updated_at'=> date('d-m-y h:i:s')
                ];
   
                $update =  WorkoutDetail::where(['_id'=>$wl_ex_daywise_id])->update($updateData);

                if ($update) {
                     return redirect('trainer/wl_exercise_list')->with('su_status', 'Excercise updated Sucessfully!');
                } else {
                    return redirect('trainer/wl_exercise_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function wl_ex_daywise_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $detail = WorkoutDetail::where(['_id'=>$id])->delete();
            if($detail){
                return redirect('trainer/wl_exercise_list')->with('su_status','Exercise Deleted Successfully');
            }
            else{
                return redirect('trainer/wl_exercise_list')->with('er_status','Exercise Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_wl_wo_daywise(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/weightlifting/add_wl_wo_daywise', ['id'=>$id]);
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_wl_wo_daywise_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'title.required' => 'Title is required.',
                'daily_desc.required' => 'Description is required.',
                'reps.required' => 'Reps field is required.',
                'rest_time.required' => 'Rest Time is required.',
                'image.required' => 'Image is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'title' => 'required',
                'daily_desc' => 'required',
                'reps' => 'required',
                'rest_time' => 'required',
                'image' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $image1 = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $image1 = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/image');
                    $image->move($destinationPath, $image1);
                }
                $insertData = [
                    'workout_id' => $request->input('wl_workout_id'),
                    'day' => $request->input('day'),
                    'title' => $request->input('title'),
                    'image' => $image1,
                    'daily_desc' => $request->input('daily_desc'),
                    'reps'=>$request->input('reps'),
                    'rest_time'=>$request->input('rest_time'),
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  WorkoutDetail::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/wl_workout_list')->with('su_status', 'Workout Added Sucessfully!');
                } else {
                    return redirect('trainer/wl_workout_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function wl_wo_daywise_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $wl_wo_daywise_detail = WorkoutDetail::where(['_id'=>$id])->first();
            if($wl_wo_daywise_detail){
                return view('trainer_panel/weightlifting/wl_wo_daywise_edit',['wl_wo_daywise_detail'=>$wl_wo_daywise_detail]);
            }
            else{
                return redirect('trainer/wl_workout_list')->with('er_status','Workout Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function wl_wo_daywise_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'title.required' => 'Title is required.',
                'daily_desc.required' => 'Description is required.',
                'reps.required' => 'Reps field is required.',
                'rest_time.required' => 'Time is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'title' => 'required',
                'daily_desc' => 'required',
                'reps' => 'required',
                'rest_time' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $wl_wo_daywise_id = $request->input('wl_wo_daywise_id');
                $image1 = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $image1 = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/image');
                    $image->move($destinationPath, $image1);
                }
                else{
                    $trainerdetails = WorkoutDetail::where(['_id'=>$wl_wo_daywise_id])->select('image')->first();
                    $image1 = $trainerdetails['image'];
                }
                $updateData = [
                    'day' => $request->input('day'),
                    'title' => $request->input('title'),
                    'image' => $image1,
                    'daily_desc' => $request->input('daily_desc'),
                    'reps'=>$request->input('reps'),
                    'rest_time'=>$request->input('rest_time'),
                    'updated_at'=> date('d-m-y h:i:s')
                ];
   
                $update =  WorkoutDetail::where(['_id'=>$wl_wo_daywise_id])->update($updateData);

                if ($update) {
                     return redirect('trainer/wl_workout_list')->with('su_status', 'Workout updated Sucessfully!');
                } else {
                    return redirect('trainer/wl_workout_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function wl_wo_daywise_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $detail = WorkoutDetail::where(['_id'=>$id])->delete();
            if($detail){
                return redirect('trainer/wl_workout_list')->with('su_status','Workout Deleted Successfully');
            }
            else{
                return redirect('trainer/wl_workout_list')->with('er_status','Workout Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_yoga_ex_daywise(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/yoga/add_yoga_ex_daywise', ['id'=>$id]);
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_yoga_ex_daywise_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'title.required' => 'Title is required.',
                'daily_desc.required' => 'Description is required.',
                'sets.required' => 'Sets field is required.',
                'hold_time.required' => 'Hold Time is required.',
                'image.required' => 'Image is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'title' => 'required',
                'daily_desc' => 'required',
                'sets' => 'required',
                'hold_time' => 'required',
                'image' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $image1 = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $image1 = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/image');
                    $image->move($destinationPath, $image1);
                }
                $insertData = [
                    'workout_id' => $request->input('yoga_excercise_id'),
                    'day' => $request->input('day'),
                    'title' => $request->input('title'),
                    'image' => $image1,
                    'daily_desc' => $request->input('daily_desc'),
                    'sets'=>$request->input('sets'),
                    'hold_time'=>$request->input('hold_time'),
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  WorkoutDetail::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/yoga_exercise_list')->with('su_status', 'Exercise Added Sucessfully!');
                } else {
                    return redirect('trainer/yoga_exercise_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function yoga_ex_daywise_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $yoga_ex_daywise_detail = WorkoutDetail::where(['_id'=>$id])->first();
            if($yoga_ex_daywise_detail){
                return view('trainer_panel/yoga/yoga_ex_daywise_edit',['yoga_ex_daywise_detail'=>$yoga_ex_daywise_detail]);
            }
            else{
                return redirect('trainer/yoga_exercise_list')->with('er_status','Workout Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_ex_daywise_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'title.required' => 'Title is required.',
                'daily_desc.required' => 'Description is required.',
                'sets.required' => 'sets field is required.',
                'hold_time.required' => 'Time is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'title' => 'required',
                'daily_desc' => 'required',
                'sets' => 'required',
                'hold_time' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $yoga_ex_daywise_id = $request->input('yoga_ex_daywise_id');
                $image1 = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $image1 = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/image');
                    $image->move($destinationPath, $image1);
                }
                else{
                    $trainerdetails = WorkoutDetail::where(['_id'=>$yoga_ex_daywise_id])->select('image')->first();
                    $image1 = $trainerdetails['image'];
                }
                $updateData = [
                    'day' => $request->input('day'),
                    'title' => $request->input('title'),
                    'image' => $image1,
                    'daily_desc' => $request->input('daily_desc'),
                    'sets'=>$request->input('sets'),
                    'hold_time'=>$request->input('hold_time'),
                    'updated_at'=> date('d-m-y h:i:s')
                ];
   
                $update =  WorkoutDetail::where(['_id'=>$yoga_ex_daywise_id])->update($updateData);

                if ($update) {
                     return redirect('trainer/yoga_exercise_list')->with('su_status', 'Exercise updated Sucessfully!');
                } else {
                    return redirect('trainer/yoga_exercise_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_ex_daywise_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $detail = WorkoutDetail::where(['_id'=>$id])->delete();
            if($detail){
                return redirect('trainer/yoga_exercise_list')->with('su_status','Exercise Deleted Successfully');
            }
            else{
                return redirect('trainer/yoga_exercise_list')->with('er_status','Exercise Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_yoga_wo_daywise(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/yoga/add_yoga_wo_daywise', ['id'=>$id]);
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_yoga_wo_daywise_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'title.required' => 'Title is required.',
                'daily_desc.required' => 'Description is required.',
                'sets.required' => 'sets field is required.',
                'hold_time.required' => 'Hold Time is required.',
                'image.required' => 'Image is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'title' => 'required',
                'daily_desc' => 'required',
                'sets' => 'required',
                'hold_time' => 'required',
                'image' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $image1 = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $image1 = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/image');
                    $image->move($destinationPath, $image1);
                }
                $insertData = [
                    'workout_id' => $request->input('yoga_workout_id'),
                    'day' => $request->input('day'),
                    'title' => $request->input('title'),
                    'image' => $image1,
                    'daily_desc' => $request->input('daily_desc'),
                    'sets'=>$request->input('sets'),
                    'hold_time'=>$request->input('hold_time'),
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  WorkoutDetail::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/yoga_workout_list')->with('su_status', 'Workout Added Sucessfully!');
                } else {
                    return redirect('trainer/yoga_workout_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function yoga_wo_daywise_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $yoga_wo_daywise_detail = WorkoutDetail::where(['_id'=>$id])->first();
            if($yoga_wo_daywise_detail){
                return view('trainer_panel/yoga/yoga_wo_daywise_edit',['yoga_wo_daywise_detail'=>$yoga_wo_daywise_detail]);
            }
            else{
                return redirect('trainer/yoga_workout_list')->with('er_status','Workout Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_wo_daywise_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'title.required' => 'Title is required.',
                'daily_desc.required' => 'Description is required.',
                'sets.required' => 'sets field is required.',
                'hold_time.required' => 'Time is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'title' => 'required',
                'daily_desc' => 'required',
                'sets' => 'required',
                'hold_time' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $yoga_wo_daywise_id = $request->input('yoga_wo_daywise_id');
                $image1 = "";
                if($request->hasFile('image')) {
                    $image = $request->file('image');
                    $image1 = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/image');
                    $image->move($destinationPath, $image1);
                }
                else{
                    $trainerdetails = WorkoutDetail::where(['_id'=>$yoga_wo_daywise_id])->select('image')->first();
                    $image1 = $trainerdetails['image'];
                }
                $updateData = [
                    'day' => $request->input('day'),
                    'title' => $request->input('title'),
                    'image' => $image1,
                    'daily_desc' => $request->input('daily_desc'),
                    'sets'=>$request->input('sets'),
                    'hold_time'=>$request->input('hold_time'),
                    'updated_at'=> date('d-m-y h:i:s')
                ];
   
                $update =  WorkoutDetail::where(['_id'=>$yoga_wo_daywise_id])->update($updateData);

                if ($update) {
                     return redirect('trainer/yoga_workout_list')->with('su_status', 'Workout updated Sucessfully!');
                } else {
                    return redirect('trainer/yoga_workout_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function yoga_wo_daywise_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $detail = WorkoutDetail::where(['_id'=>$id])->delete();
            if($detail){
                return redirect('trainer/yoga_workout_list')->with('su_status','Workout Deleted Successfully');
            }
            else{
                return redirect('trainer/yoga_workout_list')->with('er_status','Workout Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_diet_daywise(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            return view('trainer_panel/diet/add_diet_daywise', ['id'=>$id]);
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_diet_daywise_action(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'description.required' => 'Description is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'description' => 'required',
            ], $messages);
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $insertData = [
                    'diet_id' => $request->input('diet_id'),
                    'day' => $request->input('day'),
                    'daily_desc' => $request->input('description'),
                    'created_at' => date('Y-m-d h:i:s')
                ];
   
                $insert =  DietDetail::insertGetId($insertData);

                if ($insert) {
                     return redirect('trainer/diet_list')->with('su_status', 'Diet details Added Sucessfully!');
                } else {
                    return redirect('trainer/diet_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function diet_daywise_edit(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $diet_daywise_detail = DietDetail::where(['_id'=>$id])->first();
            if($diet_daywise_detail){
                return view('trainer_panel/diet/diet_daywise_edit',['diet_daywise_detail'=>$diet_daywise_detail]);
            }
            else{
                return redirect('trainer/diet_list')->with('er_status','Diet Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function diet_daywise_update(Request $request){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $messages = [
                'day.required' => 'Day is required.',
                'description.required' => 'Description is required.',
            ];

            $validator = Validator::make($request->all(), [
                'day' => 'required',
                'description' => 'required',
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $diet_daywise_id = $request->input('diet_daywise_id');
                $updateData = [
                    'day' => $request->input('day'),
                    'daily_desc' => $request->input('description'),
                    'updated_at'=> date('d-m-y h:i:s')
                ];
   
                $update =  DietDetail::where(['_id'=>$diet_daywise_id])->update($updateData);

                if ($update) {
                     return redirect('trainer/diet_list')->with('su_status', 'Diet Detail updated Sucessfully!');
                } else {
                    return redirect('trainer/diet_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function diet_daywise_delete(Request $request, $id){
        $user_id = $request->session()->get('trainer_user_id');
        if($user_id){
            $detail = DietDetail::where(['_id'=>$id])->delete();
            if($detail){
                return redirect('trainer/diet_list')->with('su_status','Diet Detail Deleted Successfully');
            }
            else{
                return redirect('trainer/diet_list')->with('er_status','Diet Detail Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }
}
