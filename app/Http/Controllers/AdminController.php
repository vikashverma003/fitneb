<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin as Admin;
use App\User as User;
use App\Trainer as Trainer;
use App\Workout as Workout;
use App\TrainerRequest as TrainerRequest;
use App\Goal as Goal;
use Validator;
use Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function adminlogin(Request $request){
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
            $users = Admin::where('email','=',$request->input('email'))->first();
            
            if($users){
            	$hashedPassword = $users['password'];
            	if (Hash::check($request->input('password'), $hashedPassword)) {
            		$request->session()->put('admin_user_id', $users['_id']);
                	return redirect('dashboard');
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
    	$user_id = $request->session()->get('admin_user_id');
    	if($user_id){
    		return view('dashboard');
    	}
    	else{
    		return redirect('/')->with('er_status','Session Expired. Please Login again.');
    	}
    }

    public function logout(Request $request){
    	$request->session()->forget('admin_user_id');
    	return redirect('/');
    }

    public function users_list(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $users = User::orderBy('_id','desc')->get();
            if($users){
                return view('users/list',['users' => $users]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function user_delete(Request $request, $id){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $user = User::where(['_id'=>$id])->delete();
            if($user){
                return redirect('users_list')->with('su_status','User deleted successfully');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function user_profile(Request $request, $id){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $user = User::where(['_id'=>$id])->first();
            if($user){
                return view('users/user_profile',['user' => $user]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function user_edit(Request $request, $id){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $user = User::where(['_id'=>$id])->first();
            if($user){
                return view('users/edit',['user' => $user]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function user_update(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $messages = [
                'first_name.required' => 'First Name is required',
                'age.required' => 'Age is required',
                'bio.required' => 'Bio is required',
                'body_mass.required' => 'Body Mass is required',
                'goal1.required' => 'Goal is required',
                'height.required' => 'Height is required',
                'weight.required' => 'Weight is required',
            ];

            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'age' => 'required',
                'bio' => 'required',
                'body_mass' => 'required',
                'goal1' => 'required',
                'height' => 'required',
                'weight' => 'required'
            ], $messages);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{
                $user_id = $request->input('user_id');
                $profile_image = "";
                if($request->hasFile('profile_image')) {
                    $image = $request->file('profile_image');
                    $profile_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $profile_image);
                }
                else{
                    $userdetails = User::where(['_id'=>$user_id])->select('profile_image')->first();
                    $profile_image = $userdetails['profile_image'];
                }
                $updateData =[
                    'first_name'=>$request->input('first_name'),
                    'last_name'=>$request->input('last_name'),
                    'profile_image'=>$profile_image,
                    'age'=>$request->input('age'),
                    'bio'=>$request->input('bio'),
                    'body_mass'=>$request->input('body_mass'),
                    'goal1'=>$request->input('goal1'),
                    'goal2'=>$request->input('goal2'),
                    'goal3'=>$request->input('goal3'),
                    'height'=>$request->input('height'),
                    'weight'=>$request->input('weight'),
                    'updated_at'=>date('Y-m-d h:i:s')
                ];

                $update = User::where(['_id'=>$user_id])->update($updateData);
                if($update){
                    return redirect('users_list')->with('su_status','User updated successfully');
                }
                else{
                    return redirect('users_list')->with('er_status','Error occurred on updating user');
                }
            }

        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function trainers_list(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $trainers = Trainer::orderBy('_id','desc')->get();
            if($trainers){
                return view('trainers/list',['trainers' => $trainers]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function trainer_delete(Request $request, $id){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $trainer = Trainer::where(['_id'=>$id])->delete();
            if($trainer){
                return redirect('trainers_list')->with('su_status','Trainer deleted successfully');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function trainer_profile(Request $request, $id){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $trainer = Trainer::where(['_id'=>$id])->first();
            if($trainer){
                return view('trainers/trainer_profile',['trainer' => $trainer]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function trainer_edit(Request $request, $id){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $trainer = Trainer::where(['_id'=>$id])->first();
            if($trainer){
                return view('trainers/edit',['trainer' => $trainer]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function trainer_update(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $messages = [
                'name.required' => 'Name is required',
                'mobile.required' => 'Mobile is required',
            ];

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'mobile' => 'required'
            ], $messages);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{
                $trainer_id = $request->input('trainer_id');
                $profile_image = "";
                if($request->hasFile('profile_image')) {
                    $image = $request->file('profile_image');
                    $profile_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $profile_image);
                }
                else{
                    $trainerdetails = Trainer::where(['_id'=>$trainer_id])->select('profile_image')->first();
                    $profile_image = $trainerdetails['profile_image'];
                }
                $updateData =[
                    'name'=>$request->input('name'),
                    'mobile'=>$request->input('mobile'),
                    'profile_image'=>$profile_image,
                    'updated_at'=>date('Y-m-d h:i:s')
                ];

                $update = Trainer::where(['_id'=>$trainer_id])->update($updateData);
                if($update){
                    return redirect('trainers_list')->with('su_status','Trainer updated successfully');
                }
                else{
                    return redirect('trainers_list')->with('er_status','Error occurred on updating trainer');
                }
            }

        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function we_yoga(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $yoga = Workout::where(['page_for'=>'yoga'])->orderBy('_id','desc')->get();
            if($yoga){
                return view('workouts/we_yoga',['yoga' => $yoga]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function we_weightlift(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $weightlifting = Workout::where(['page_for'=>'weightlifting'])->orderBy('_id','desc')->get();
            if($weightlifting){
                return view('workouts/we_weightlift',['weightlifting' => $weightlifting]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_goals(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            return view('goals/add');
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    } 

    public function add_goals_action(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $messages = [
                'goal.required' => 'Goal is required. ',
            ];

            $validator = Validator::make($request->all(), [
                'goal' => 'required'
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $insertGoal = [
                    'goal' => $request->input('goal'),
                    'status' => '1',
                    'created_at' => date('d-m-y h:i:s'), 
                    'updated_at'=> date('d-m-y h:i:s')
                ];
   
                $insert =  Goal::insertGetId($insertGoal);

                if ($insert) {
                     return redirect('goals_list')->with('su_status', 'Goal Added Sucessfully!');
                } else {
                    return redirect('add_goals')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        } else {
            return redirect('/')->with('er_status', 'Your Session is Expired Please Login Again');
        }
    }

    public function goals_list(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $goals = Goal::where(['status'=>'1'])->orderBy('_id','desc')->paginate(10);
            if($goals){
                return view('goals/list',['goals' => $goals]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function goals_edit(Request $request, $id){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $goal = Goal::where(['_id'=>$id])->where(['status'=>'1'])->first();
            if($goal){
                return view('goals/edit',['goal'=>$goal]);
            }
            else{
                return redirect('goals_list')->with('er_status','Goal Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function goals_update(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $messages = [
                'goal.required' => 'Goal is required.'
            ];

            $validator = Validator::make($request->all(), [
                'goal' => 'required'
            ], $messages);
      
            if ($validator->fails()) {
                  return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $id = $request->input('id');
                $updateData = [
                    'goal' => $request->input('goal'),
                    'status' => '1', 
                    'updated_at'=> date('d-m-y h:i:s')
                ];
   
                $update =  Goal::where(['_id'=>$id])->update($updateData);

                if ($update) {
                     return redirect('goals_list')->with('su_status', 'Goal updated Sucessfully!');
                } else {
                    return redirect('goals_list')->with('er_status', 'Something Went Wrong Please Check Your Inputs!');
                }
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function goals_delete(Request $request, $id){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $goal = Goal::where(['_id'=>$id])->where(['status'=>'1'])->delete();
            if($goal){
                return redirect('goals_list')->with('su_status','Goal Deleted Successfully');
            }
            else{
                return redirect('goals_list')->with('er_status','Goal Not Found');
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function add_trainer(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            return view('trainers/add');
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    } 

    public function random_strings($length_of_string) 
    { 
      
        // String of all alphanumeric character 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
      
        // Shufle the $str_result and returns substring 
        // of specified length 
        return substr(str_shuffle($str_result),  
                           0, $length_of_string); 
    }

    public function add_trainer_action(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $messages = [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'mobile.required' => 'Mobile is required',
                'profile_image.required' => 'Profile Image is required'
            ];

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:trainers',
                'mobile' => 'required',
                'profile_image' => 'required'
            ], $messages);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{
                $user_id = $request->input('user_id');
                $profile_image = "";
                $password = AdminController::random_strings(8);
                $en_password = Hash::make($password);
                if($request->hasFile('profile_image')) {
                    $image = $request->file('profile_image');
                    $profile_image = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images');
                    $image->move($destinationPath, $profile_image);
                }
                $insertData =[
                    'name'=>$request->input('name'),
                    'email'=>$request->input('email'),
                    'password'=>$en_password,
                    'mobile'=>$request->input('mobile'),
                    'profile_image'=>$profile_image,
                    'status'=>'1',
                    'created_at'=>date('Y-m-d h:i:s')
                ];

                $insert = Trainer::insertGetId($insertData);
                if($insert){
                    $msg = [
                        "name"=>$request->input('name'),
                        "email"=>$request->input('email'),
                        "password" => $password
                    ];
                    $email = $request->input('email');
                    Mail::send('welcome_trainer', $msg, function($message) use ($email) {
                        $message->to($email);
                        $message->subject('Welcome to Fitneb');
                    });
                    return redirect('trainers_list')->with('su_status','Trainer added successfully');
                }
                else{
                    return redirect('trainers_list')->with('er_status','Error occurred on adding trainer');
                }
            }

        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function trainer_request_list(Request $request){
        $user_id = $request->session()->get('admin_user_id');
        if($user_id){
            $trainer_requests = TrainerRequest::with('user')->orderBy('_id','desc')->get();
            if($trainer_requests){
                return view('trainers/trainer_request_list',['trainer_requests' => $trainer_requests]);
            }
        }
        else{
            return redirect('/')->with('er_status','Session Expired. Please Login again.');
        }
    }

    public function respond_trainer_request(Request $request){
        $messages = [
                'status.required' => 'Name is required',
            ];

            $validator = Validator::make($request->all(), [
                'status' => 'required',
            ], $messages);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{
                $update = TrainerRequest::where(['_id'=>$request->input('request_id')])->update(['status'=>$request->input('status')]);
                if($update){
                    if($request->input('status') == "1"){
                        $requestDetail = TrainerRequest::where(['_id'=>$request->input('request_id')])->select('user_id')->first();
                        if($requestDetail){
                            $user_id = $requestDetail['user_id'];
                            $userDetail = User::where(['_id'=>$user_id])->first();
                            if($userDetail){
                                $insertData =[
                                    'name'=>$userDetail['first_name']." ".$userDetail['last_name'],
                                    'email'=>$userDetail['email'],
                                    'password'=>$userDetail['password'],
                                    'mobile'=>"9876543210",
                                    'profile_image'=>"default.jpg",
                                    'status'=>'1',
                                    'created_at'=>date('Y-m-d h:i:s')
                                ];

                                $insert = Trainer::insertGetId($insertData);
                                if($insert){
                                    $msg = [
                                        "name"=>$userDetail['first_name']." ".$userDetail['last_name'],
                                        "email"=>$userDetail['email'],
                                        "password" => ""
                                    ];
                                    $email = $userDetail['email'];
                                    Mail::send('welcome_trainer', $msg, function($message) use ($email) {
                                        $message->to($email);
                                        $message->subject('Welcome to Fitneb');
                                    });
                                    return redirect('trainers_list')->with('su_status','Trainer added successfully');
                                }
                            }
                        }
                    }
                }
                else{
                    return redirect('trainer_request_list')->with('er_status','something went wrong. Please try again later.');
                }
            }
    }
}
