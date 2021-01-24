<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as User;
use App\Post as Post;
use App\News as News;
use App\Diet as Diet;
use App\Like as Like;
use App\Block as Block;
use App\Comment as Comment;
use App\Workout as Workout;
use App\Follow as Follow;
use App\Trainer as Trainer;
use App\Favorite as Favorite;
use App\Category as Category;
use App\Report as Report;
use App\Goal as Goal;
use App\UserDetail as UserDetail;
use App\DietDetail as DietDetail;
use App\WorkoutDetail as WorkoutDetail;
use App\TrainingDetail as TrainingDetail;
use App\MyDiet as MyDiet;
use App\Helpers; // include the helper 
use Validator;
use Hash;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
	private $badRequestCode = 400;
	private $successCode = 200;

    public function register(Request $request){
    	$saveArray = $request->all();
    	$validator = Validator::make($request->all(), [ 
    		'first_name' => 'required',
    		'last_name' => 'required',
			'email' => 'required|email|unique:users', 
			'password' => 'required', 
			'c_password' => 'required|same:password',
    		'location' => 'required',
    		//'profile_image' => 'required',
    		'device_type'=> 'required',
    		'device_token'=> 'required',
    		'lat'=> 'required',
    		'long'=> 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->successCode);            
		}
		else{
			$first_name = $request->input('first_name');
			$last_name = $request->input('last_name');
			$email = $request->input('email');
			$password = $request->input('password');
			$location = $request->input('location');
			$device_type = $request->input('device_type');
			$device_token = $request->input('device_token');
			$lat = $request->input('lat');
			$long = $request->input('long');
		//	$verification_code = mt_rand(10000, 99999);
			$verification_code = '54321';
			$en_password = Hash::make($password);
			$profile_image = "";
			if($request->hasFile('profile_image')) {
				$image = $request->file('profile_image');
				$profile_image = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/images');
				$image->move($destinationPath, $profile_image);
			}

			$insertData = [
				'login_type'=>'N',
				'social_id'=>'',
				'first_name'=>$first_name,
				'last_name'=>$last_name,
				'email'=>$email,
				'password'=>$en_password,
				'location'=>$location,
				'profile_image'=>$profile_image,
				'verification_code'=>$verification_code,
				'verified'=>'N',
				'status'=>'Y',
				'created_at'=>date('Y-m-d H:i:s'),
				'device_type'=>$device_type,
				'device_token'=>$device_token,
				'age'=>'',
				'bio'=>'',
				'body_mass'=>'',
				'goal1'=>'',
				'goal2'=>'',
				'goal3'=>'',
				'height'=>'',
				'weight'=>'',
				'account_type'=>'public',
				'lat'=>$lat,
				'long'=>$long
			];

			$user_id = User::insertGetId($insertData);
			if($user_id){
				
				
				$insertData = [
				'user_id'=>(String)$user_id,
				'height'=>'',
				'weight'=>'',
				'body_mass_index'=>'',
				'percent_body_fat'=>'',
				'neck_circumference'=>'',
				'chest_circumference'=>'',
				'waist_circumference'=>'',
				'hip_circumference'=>'',
				'shoulder_circumference'=>'',
				'arm_circumference'=>'',
				'calf_circumference'=>'',
				];
				
				$UserDetail_id = UserDetail::insertGetId($insertData);
				
				
				$msg = [
					"first_name"=>$first_name,
					"last_name"=>$last_name,
                    "verification_code" => $verification_code
                ];
                Mail::send('welcome_mail', $msg, function($message) use ($email) {
                    $message->to($email);
                    $message->subject('Verification - Fitneb');
                });
                $goal = array(
						'',
						'',
						'',
					);
				return response()->json([ 
					'status' => true,
					'message' => 'User Registered Successfully',
					'data'=>[
						'user_id'=>(String)$user_id,
						'first_name'=>$first_name,
						'last_name'=>$last_name,
						'email'=>$email,
						'location'=>$location,
						'profile_image'=>url('public/images',$profile_image),
						'verification_code'=>$verification_code,
						'verified'=>'N',
						'device_type'=>$device_type,
						'device_token'=>$device_token,
						'age'=>"",
						'bio'=>"",
						'body_mass'=>"",
						'goal'=>$goal,
						'height'=>"",
						'weight'=>"",
						'account_type'=>'public',
						'lat'=>$lat,
						'long'=>$long
					],
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'User Registered Failed',
				], $this->successCode);
			}
		}
    }

    public function login(Request $request){ 
		$saveArray = $request->all();
		$validator = Validator::make($request->all(), [ 
    		'email' => 'required',
    		'password' => 'required',
			'device_type' => 'required', 
			'device_token' => 'required',
			'location' => 'required'
		]);
		if ($validator->fails()) {
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			$userExist = User::where(['email'=> $saveArray['email']])->first() ;
	        if($userExist){ 
		        if(Hash::check($saveArray['password'],$userExist['password'])){
		        	$update = User::where(['_id'=>$userExist->id])->update(['location'=>$saveArray['location'],'lat'=>$saveArray['lat'],'long'=>$saveArray['long'],'device_type'=>$saveArray['device_type'], 'device_token'=>$saveArray['device_token']]);
		        	$goal = array(
						$userExist->goal1?$userExist->goal1:'',
						$userExist->goal2?$userExist->goal2:'',
						$userExist->goal3?$userExist->goal3:'',
					);
					
					$followerCount = Follow::where(['other_user_id'=> $userExist->id])->count();
					$followingCount = Follow::where(['user_id'=> $userExist->id])->count();
					
				    return response()->json([
				        'status' => true,
				        'message'=>'Logged in successfully',
				        'data'=>[
				        	'user_id'=>$userExist->id,
				        	'first_name'=>$userExist->first_name,
				        	'last_name'=>$userExist->last_name,
				        	'email'=>$userExist->email,
				        	'location'=>$userExist->location,
				        	'verified'=>$userExist->verified,
				        	'device_type'=>$saveArray['device_type'],
				        	'device_token'=>$saveArray['device_token'],
				        	'profile_image'=>url('public/images',$userExist->profile_image),
				        	'age'=>$userExist->age?$userExist->age:'',
							'bio'=>$userExist->bio?$userExist->bio:'',
							'body_mass'=>$userExist->body_mass?$userExist->body_mass:'',
							'goal'=>$goal,
							'height'=>$userExist->height?$userExist->height:'',
							'weight'=>$userExist->weight?$userExist->weight:'',
							'followerCount'=>$followerCount,
							'followingCount'=>$followingCount,
							'account_type'=>$userExist->account_type,
							'lat'=>$saveArray['lat'],
							'long'=>$saveArray['long'],
				       	]
				    ], $this->successCode); 

				}
				else{
					return response()->json(['message'=>'User and Password is incorrect','status'=>false], $this->successCode); 
				}
	        } 
	        else{ 
	            return response()->json(['message'=>'User and Password is incorrect','status'=>false], $this->successCode); 
	        }
		}
    }

    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function forgot_password(Request $request)
	{
		$saveArray = $request->all();
		$user = User::where(['email'=>$saveArray['email']])->select('first_name','last_name')->first();
		if($user){
			$new_password = ApiController::randomPassword();
			$new_password_en = Hash::make($new_password);
			$update_password = [
                'password' => $new_password_en,
                'updated_at' => date('Y-m-d h:i:s')
            ];
            $update = User::where(['email'=>$saveArray['email']])->update($update_password);
            if($update){
            	$msg = [
            		'first_name'=>$user['first_name'],
            		'last_name'=>$user['last_name'],
                    "new_password" => $new_password
                ];
                $email = $saveArray['email'];
                Mail::send('mail', $msg, function($message) use ($email) {
                    $message->to($email);
                    $message->subject('New Password - Fitneb');
                });
                return response()->json(['status'=>true,'message'=>'New Password has been mailed to you. Please check your email'], $this->successCode);
            }
		}
		else{
			return response()->json(['message'=>'User does not exist','status'=>false], $this->successCode); 
		}
	}

	public function resend_verify_code(Request $request){
		$saveArray = $request->all();
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required'
		]);
		if($validator->fails()) {
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			$userdetails = User::where(['_id'=>$saveArray['user_id']])->select('first_name','last_name','email')->first();
			if($userdetails){
				//$verification_code = mt_rand(10000, 99999);
				$verification_code ='54321';
				$updateData = [
	                'verification_code' => $verification_code,
	                'updated_at' => date('Y-m-d h:i:s')
	            ];
	            $update = User::where(['_id'=>$saveArray['user_id']])->update($updateData);
	            if($update){
	            	$msg = [
						"first_name"=>$userdetails['first_name'],
						"last_name"=>$userdetails['last_name'],
	                    "verification_code" => $verification_code
	                ];
	                $email = $userdetails['email'];
	                Mail::send('welcome_mail', $msg, function($message) use ($email) {
	                    $message->to($email);
	                    $message->subject('Verification - Fitneb');
	                });
	                return response()->json(['status'=>true,'message'=>'Verification code has been mailed to you. Please check your email'], $this->successCode);
	            }
	            else{
	            	return response()->json(['message'=>'Something went wrong','status'=>false], $this->successCode); 
	            }
			}
			else{
				return response()->json(['message'=>'User does not exist','status'=>false], $this->successCode); 
			}
		}
	}

	public function social_login(Request $request){
		$saveArray = $request->all();
		$validator = Validator::make($request->all(), [ 
			'login_type' => 'required',
			'social_id' => 'required',
    		'first_name' => 'required',
    		'last_name' => 'required',
			'email' => 'required',
    		'location' => 'required',
    		'profile_image'=>'required',
    		'device_type'=>'required',
    		'device_token'=>'required',
		]);
		if($validator->fails()) {
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			$login_type = $request->input('login_type');
			$social_id = $request->input('social_id');
			$first_name = $request->input('first_name');
			$last_name = $request->input('last_name');
			$email = $request->input('email');
			$location = $request->input('location');
			$profile_image = $request->input('profile_image');
			$device_type = $request->input('device_type');
			$device_token = $request->input('device_token');

			$user_exist = User::where(['email'=>$email])->first();
			if($user_exist){
				$update = User::where(['_id'=>$user_exist['_id']])->update(['device_type'=>$device_type,'lat'=>$saveArray['lat'],'long'=>$saveArray['long'],'device_token'=>$device_token]);
				$goal = array(
						$user_exist->goal1?$user_exist->goal1:'',
						$user_exist->goal2?$user_exist->goal2:'',
						$user_exist->goal3?$user_exist->goal3:'',
					);
					
					$followerCount = Follow::where(['other_user_id'=> $user_exist['_id']])->count();
					$followingCount = Follow::where(['user_id'=> $user_exist['_id']])->count();
					
				return response()->json([ 
					'status' => true,
					'message' => 'User Logged Successfully',
					'data'=>[
						'user_id'=>(String)$user_exist['_id'],
						'login_type'=>$user_exist['login_type'],
						'social_id'=>$user_exist['social_id']?$user_exist['social_id']:'',
						'first_name'=>$user_exist['first_name'],
						'last_name'=>$user_exist['last_name'],
						'email'=>$user_exist['email'],
						'location'=>$user_exist['location'],
						'profile_image'=>url('public/images',$user_exist['profile_image']),
						'verified'=>'Y',
						'device_type'=>$device_type,
						'device_token'=>$device_token,
						'age'=>$user_exist->age?$user_exist->age:'',
						'bio'=>$user_exist->bio?$user_exist->bio:'',
						'body_mass'=>$user_exist->body_mass?$user_exist->body_mass:'',
						'goal'=>$goal,
						'height'=>$user_exist->height?$user_exist->height:'',
						'weight'=>$user_exist->weight?$user_exist->weight:'',
						'followerCount'=>$followerCount,
						'followingCount'=>$followingCount,
						'account_type'=>$user_exist->account_type,
						'lat'=>$saveArray['lat'],
						'long'=>$saveArray['long'],
					],
				], $this->successCode);
			}
			else{
				$insertData = [
					'login_type'=>$login_type,
					'social_id'=>$social_id,
					'first_name'=>$first_name,
					'last_name'=>$last_name,
					'email'=>$email,
					'password'=>'',
					'location'=>$location,
					'profile_image'=>$profile_image,
					'verification_code'=>'',
					'verified'=>'Y',
					'status'=>'Y',
					'created_at'=>date('Y-m-d H:i:s'),
					'device_type'=>$device_type,
					'device_token'=>$device_token,
					'age'=>'',
					'bio'=>'',
					'body_mass'=>'',
					'goal1'=>'',
					'goal2'=>'',
					'goal3'=>'',
					'height'=>'',
					'weight'=>'',
					'account_type'=>'public',
					'lat'=>$saveArray['lat'],
					'long'=>$saveArray['long'],
				];
				$user_id = User::insertGetId($insertData);
				if($user_id){
					$goal = array(
						'goal1'=>'',
						'goal2'=>'',
						'goal3'=>'',
					);
					
					$followerCount = Follow::where(['other_user_id'=> $user_id])->count();
					$followingCount = Follow::where(['user_id'=> $user_id])->count();
					
					
					return response()->json([ 
						'status' => true,
						'message' => 'User Registered Successfully',
						'data'=>[
							'user_id'=>(String)$user_id,
							'login_type'=>$login_type,
							'social_id'=>$social_id,
							'first_name'=>$first_name,
							'last_name'=>$last_name,
							'email'=>$email,
							'location'=>$location,
							'profile_image'=>$profile_image,
							'verified'=>'Y',
							'device_type'=>$device_type,
							'device_token'=>$device_token,
							'age'=>'',
							'bio'=>'',
							'body_mass'=>'',
							'goal'=>$goal,
							'height'=>'',
							'weight'=>'',
							'followerCount'=>$followerCount,
							'followingCount'=>$followingCount,
							'account_type'=>'public',
							'lat'=>$saveArray['lat'],
							'long'=>$saveArray['long'],
						],
					], $this->successCode);
				}
				else{
					return response()->json([ 
						'status' => false,
						'message' => 'User Registered Failed',
					], $this->successCode);
				}
			}
		}
	}

	public function verify_code(Request $request){
		$saveArray = $request->all();
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',
			'verification_code' => 'required'
		]);
		if($validator->fails()) {
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			$user_id = $request->input('user_id');
			$verification_code = $request->input('verification_code');
			$userDetails = User::where(['_id'=>$user_id])->select('verification_code','verified')->first();
			$v_code = $userDetails['verification_code'];
			$v_status = $userDetails['verified'];
			if($v_status == "N"){
				if($v_code == $verification_code){
					$updateData = [
						'verified'=>'Y',
						'updated_at'=>date('Y-m-d H:i:s')
					];
					$update = User::where(['_id'=>$user_id])->update($updateData);
					if($update){
						return response()->json([ 
							'status' => true,
							'message' => 'User Verified Successfully'
						], $this->successCode);
					}
				}
				else{
					return response()->json([ 
						'status' => false,
						'message' => 'Wrong Verification Code'
					], $this->successCode);
				}
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'User Already Verified'
				], $this->successCode);
			}
		}
	}

	public function get_profile(Request $request){
		$saveArray = $request->all();
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required'
		]);
		if($validator->fails()) {
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$followerCount = Follow::where(['other_user_id'=> $saveArray['user_id']])->count();
			$followingCount = Follow::where(['user_id'=> $saveArray['user_id']])->count();
			
			$text = Post::where(['user_id'=>$saveArray['user_id'],'post_type'=>'text'])->count();
			$image = Post::where(['user_id'=>$saveArray['user_id'],'post_type'=>'image'])->count();
			$video = Post::where(['user_id'=>$saveArray['user_id'],'post_type'=>'video'])->count();

			
			
			
			
			$user_exist = User::where(['_id'=>$saveArray['user_id']])->first();
			if($user_exist){
				$profile_image = "";
				if(strpos($user_exist['profile_image'], "http://") !== false){
					$profile_image = $user_exist['profile_image'];
				}
				else if(strpos($user_exist['profile_image'], "https://") !== false){
					$profile_image = $user_exist['profile_image'];
				}
				else{
					$profile_image = url('public/images',$user_exist['profile_image']);
				}
				$goal = array(
						$user_exist->goal1?$user_exist->goal1:'',
						$user_exist->goal2?$user_exist->goal2:'',
						$user_exist->goal3?$user_exist->goal3:'',
					);
					
					
					
				return response()->json([ 
					'status' => true,
					'message' => 'User Profile',
					'data'=>[
						'user_id'=>(String)$user_exist['_id'],
						'login_type'=>$user_exist['login_type'],
						'social_id'=>$user_exist['social_id']?$user_exist['social_id']:'',
						'first_name'=>$user_exist['first_name'],
						'last_name'=>$user_exist['last_name'],
						'email'=>$user_exist['email'],
						'location'=>$user_exist['location'],
						'profile_image'=>$profile_image,
						'verified'=>'Y',
						'age'=>$user_exist->age?$user_exist->age:'',
						'bio'=>$user_exist->bio?$user_exist->bio:'',
						'body_mass'=>$user_exist->body_mass?$user_exist->body_mass:'',
						'goal'=>$goal,
						'height'=>$user_exist->height?$user_exist->height:'',
						'weight'=>$user_exist->weight?$user_exist->weight:'',
						'followerCount'=>$followerCount,
						'followingCount'=>$followingCount,
						'text'=>$text,
						'image'=>$image,
						'video'=>$video,
						'lat'=>$user_exist['lat'],
						'long'=>$user_exist['long'],
						'account_type'=>$user_exist['account_type'],
					],
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'User not found',
				], $this->successCode);
			}
		}
	}

	public function edit_profile(Request $request){
		$saveArray = $request->all();
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required'
		]);
		if($validator->fails()) {
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			$user_exist = User::where(['_id'=>$saveArray['user_id']])->first();
			$first_name = $request->input('first_name');
			$last_name = $request->input('last_name');
			$location = $request->input('location');
			$bio = $request->input('bio');
			$weight = $request->input('weight');
			$height = $request->input('height');
			$age = $request->input('age');
			$goal1 = $request->input('goal1');
			$goal2 = $request->input('goal2');
			$goal3 = $request->input('goal3');
			$lat = $request->input('lat');
			$long = $request->input('long');
			$body_mass = $request->input('body_mass');

			$profile_image = "";
			if($request->hasFile('profile_image')) {
				$image = $request->file('profile_image');
				$profile_image = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/images');
				$image->move($destinationPath, $profile_image);
			}
			else{
				$profile_image = $user_exist['profile_image'];
			}

			$updateData = [
				'first_name'=>$first_name?$first_name:$user_exist['first_name'],
				'last_name'=>$last_name?$last_name:$user_exist['last_name'],
				'location'=>$location?$location:$user_exist['location'],
				'profile_image'=>$profile_image?$profile_image:$user_exist['profile_image'],
				'bio'=>$bio?$bio:'',
				'weight'=>$weight?$weight:'',
				'height'=>$height?$height:'',
				'age'=>$age?$age:'',
				'goal1'=>$goal1?$goal1:'',
				'goal2'=>$goal2?$goal2:'',
				'goal3'=>$goal3?$goal3:'',
				'body_mass'=>$body_mass?$body_mass:'',
				'lat'=>$lat?$lat:$user_exist['lat'],
				'long'=>$long?$long:$user_exist['long'],
			];

			$update = User::where(['_id'=>$saveArray['user_id']])->update($updateData);
			if($update){
				$user_exist = User::where(['_id'=>$saveArray['user_id']])->first();
				$profile_image = "";
				if(strpos($user_exist['profile_image'], "http://") !== false){
					$profile_image = $user_exist['profile_image'];
				}
				else{
					$profile_image = url('public/images',$user_exist['profile_image']);
				}
				$goal = array(
					$user_exist['goal1'],
					$user_exist['goal2'],
					$user_exist['goal3'],
				);
				$data = [
					'first_name'=>$user_exist['first_name'],
					'last_name'=>$user_exist['last_name'],
					'location'=>$user_exist['location'],
					'profile_image'=>$profile_image,
					'bio'=>$user_exist['bio'],
					'weight'=>$user_exist['weight'],
					'height'=>$user_exist['height'],
					'goal'=>$goal,
					'body_mass'=>$user_exist['body_mass'],
					'age'=>$user_exist['age'],
					'lat'=>$user_exist['lat'],
					'long'=>$user_exist['long'],
				];
				return response()->json([ 
					'status' => true,
					'message' => 'User profile updated',
					'data'=>$data
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Error on profile updation',
				], $this->successCode);
			}
		}
	}
	
	
#************************************************************************************#


	public function addPost(Request $request){
		
		$saveArray = $request->all();
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',
			//'post_type' => 'required'
		]);
		if($validator->fails()) {
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			//$hash_tag=explode(',',$saveArray['hash_tag']);
		//	$saveArray['hash_tag']=json_encode($hash_tag);
			$saveArray['post_type']='text';
			$image = "";
			$video = "";
			
			if($request->hasFile('image')) {
				$saveArray['post_type']='image';
				$post_image = $request->file('image');
				$image = time().'.'.$post_image->getClientOriginalExtension();
				$destinationPath = public_path('/images/image');
				$post_image->move($destinationPath, $image);
			}
			if($request->hasFile('video')) {
				$saveArray['post_type']='video';
				$post_video = $request->file('video');
				$video = time().'.'.$post_video->getClientOriginalExtension();
				$destinationPath = public_path('/images/video');
				$post_video->move($destinationPath, $video);
			}

			$insertData = [
				'user_id'=>$saveArray['user_id'],
				'post_type'=>$saveArray['post_type'],
				'description'=>$saveArray['description'],
				'image'=>$image,
				'video'=>$video,
				'hash_tag'=>$saveArray['hash_tag'],
				'location'=>$saveArray['location'],
				'created_at'=>date('Y-m-d H:i:s')
			];
			$post_id = Post::insertGetId($insertData);
			if($post_id){
				return response()->json([ 
					'status' => true,
					'message' =>'Post added',
					'post_id'=>(String)$post_id,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Error on adding post',
				], $this->successCode);
			}
		}	
	}
	
#************************************************************************************#	

	public function get_posts(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',
			'post_type' => 'required',
			'tagged'	=>'required'
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			$search=$saveArray['user_id'];
			if($saveArray['post_type'] =='text'){
				if($saveArray['tagged'] == 'N'){
					$posts = Post::where(['user_id'=>$saveArray['user_id']])->where('description', '<>',"")->get()->toarray();
				}
				else{
					$posts = Post::where('hash_tag','like','%'.$saveArray['user_id'].'%')->where('description', '<>',"")->get()->toarray();
				}
			}
			
			if($saveArray['post_type'] =='image'){
				if($saveArray['tagged'] == 'N'){
					$posts = Post::where(['user_id'=>$saveArray['user_id']])->where('image', '<>',"")->get()->toarray();
				}
				else{
					$posts = Post::where('hash_tag','like',$saveArray['user_id'])->where('image', '<>',"")->get()->toarray();
				}
			}
			
			if($saveArray['post_type'] =='video'){
				if($saveArray['tagged'] == 'N'){
					$posts = Post::where(['user_id'=>$saveArray['user_id']])->where('video', '<>',"")->get()->toarray();
				}
				else{
					$posts = Post::where('hash_tag','like',$saveArray['user_id'])->where('video', '<>',"")->get()->toarray();
				}
			}
			
			
			
		
			if($posts){
				
				foreach($posts as $posts){
					
					if($posts['description']==null){
						$posts['description']="";
					}
					if($posts['hash_tag']==null){
						$posts['hash_tag']="";
					}
					if($posts['location']==null){
						$posts['location']="";
					}
					
					if(!empty($posts['image'])){
						
						$posts['image']=url('public/images/image',$posts['image']);
					}else{
						$posts['image']="";
					}
					if(!empty($posts['video'])){
						
						$posts['video']=url('public/images/video',$posts['video']);
					}else{
						$posts['video']="";
					}
				
					$helper = new Helpers();
					
							$likes = Like::where(['user_id'=> $saveArray['user_id'],'post_id'=> $posts['_id']])->first() ;
			
							if($likes){
								$like_status=$likes->like_status;
							}else{
								
								$like_status='unlike';
							}
							$totalLikes = Like::where(['post_id'=> $posts['_id']])->where(['like_status'=>'like'])->count();
							$totalComment = Comment::where(['post_id'=> $posts['_id']])->count();
						
					$records[]=array(
						'post_id'=>$posts['_id'],
						'user_id'=>$posts['user_id'],
						//'post_type'=>$posts['post_type'],
						'description'=>$posts['description'],
						'hash_tag'=>$posts['hash_tag'],
						'location'=>$posts['location'],
						'image'=>$posts['image'],
						'video'=>$posts['video'],
						'created_at'=>$helper->time_elapsed_string($posts['created_at']),
						'like_status'=>$like_status,
						'totalLikes'=>$totalLikes,
						'totalComment'=>$totalComment,
					);
				
				}
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}

	}

	public function get_all_posts(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',
			//'post_type' => 'required'
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$posts = Post::/*where(['user_id'=>$saveArray['user_id'],'post_type'=>$saveArray['post_type']])->*/orderBy('_id','DESC')->get()->toarray();
	
			if($posts){
				
				foreach($posts as $posts){
					
					if($posts['description']==null){
						$posts['description']="";
					}
					if($posts['hash_tag']==null){
						$posts['hash_tag']="";
					}
					if($posts['location']==null){
						$posts['location']="";
					}
					
					if(!empty($posts['image'])){
						
						$posts['image']=url('public/images/image',$posts['image']);
					}else{
						$posts['image']="";
					}
					if(!empty($posts['video'])){
						
						$posts['video']=url('public/images/video',$posts['video']);
					}else{
						$posts['video']="";
					}
				
					$helper = new Helpers();
					$userDetails = User::where(['_id'=>$posts['user_id']])->select('first_name','last_name','profile_image')->first();
					
							$likes = Like::where(['user_id'=> $saveArray['user_id'],'post_id'=> $posts['_id']])->first() ;
			
							if($likes){
								$like_status=$likes->like_status;
							}else{
								
								$like_status='unlike';
							}
							$totalLikes = Like::where(['post_id'=> $posts['_id']])->where(['like_status'=>'like'])->count();
							$totalComment = Comment::where(['post_id'=> $posts['_id']])->count();
							
					$records[]=array(
						'post_id'=>$posts['_id'],
						'user_id'=>$posts['user_id'],
						'first_name'=>$userDetails['first_name']?$userDetails['first_name']:'',
						'last_name'=>$userDetails['last_name']?$userDetails['last_name']:'',
						'profile_image' => ($userDetails['profile_image'])?url('public/images',$userDetails['profile_image']):'',
						'post_type'=>$posts['post_type'],
						'description'=>$posts['description'],
						'hash_tag'=>$posts['hash_tag'],
						'location'=>$posts['location'],
						'image'=>$posts['image'],
						'video'=>$posts['video'],
						'created_at'=>$helper->time_elapsed_string($posts['created_at']),
						'like_status'=>$like_status,
						'totalLikes'=>$totalLikes,
						'totalComment'=>$totalComment,
					);
				
				}
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}

	}

#************************************************************************************#	

	public function get_news(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			//$news = News::where(['user_id'=>$saveArray['user_id']])->get()->toarray();
			$news = News::get()->toarray();
	

			if($news){
				
				foreach($news as $news){
					
					$image = url('public/images',$news['image']);
					
					$helper = new Helpers();
						
					$records[]=array(
					'news_id'=>$news['_id'],
					'user_id'=>$news['user_id'],
					'title'=>$news['title'],
					'description'=>$news['description'],
					'image'=>$image,
					'created_at'=>$helper->time_elapsed_string($news['created_at']),
					);
				
				}
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}

	}
	
#*****************************************************************************#

	public function getNewsDetail(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'news_id' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$news = News::where(['_id'=>$saveArray['news_id']])->first()->toarray();

			if($news){
				
					$image = url('public/images',$news['image']);
					
					$helper = new Helpers();
						
					$records[]=array(
					'news_id'=>$news['_id'],
					'user_id'=>$news['user_id'],
					'title'=>$news['title'],
					'description'=>$news['description'],
					'image'=>$image,
					'created_at'=>$helper->time_elapsed_string($news['created_at']),
					);
				
				
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}

	}
	
#*****************************************************************************#	
	

	public function follow_user(Request $request){
		
		$saveArray = $request->all();
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',
			'other_user_id' => 'required',
			'follow_status' => 'required'
		]);
		if($validator->fails()) {
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			if($saveArray['follow_status'] == "Y"){
				$already_follow = Follow::where(['user_id'=>$saveArray['user_id']])->where(['other_user_id'=>$saveArray['other_user_id']])->first();
				if(!$already_follow){
					$insertData = [
						'user_id'=>$saveArray['user_id'],
						'other_user_id'=>$saveArray['other_user_id'],
						'created_at'=>date('Y-m-d H:i:s')
					];

					$insert = Follow::insertGetId($insertData);
					if($insert){
						return response()->json([ 
							'status' => true,
							'message' => 'Your are following this user successfully',
						], $this->successCode);
					}
					else{
						return response()->json([ 
							'status' => false,
							'message' => 'Something went wrong',
						], $this->successCode);
					}
				}
				else{
					return response()->json([ 
						'status' => false,
						'message' => 'Your already following this user',
					], $this->successCode);
				}
			}
			else if($saveArray['follow_status'] == "N"){
				$delete = Follow::where(['user_id'=>$saveArray['user_id']])->where(['other_user_id'=>$saveArray['other_user_id']])->delete();
				if($delete){
					return response()->json([ 
						'status' => true,
						'message' => 'Your are no longer following this user',
					], $this->successCode);
				}
				else{
					return response()->json([ 
						'status' => false,
						'message' => 'Something went wrong',
					], $this->successCode);
				}
			}
		}	
	}

	public function get_follow_list(Request $request){
		
		$saveArray = $request->all();
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required'
		]);
		if($validator->fails()) {
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			$followings = Follow::where(['user_id'=> $saveArray['user_id']])->get();
			$following = array();
			foreach($followings as $followings){
				
				$user_ids = $followings->other_user_id;
				
				$isFollowStatus = Follow::where(['user_id'=> $user_ids,'other_user_id'=> $saveArray['user_id']])->first();
				if($isFollowStatus){
					
					$is_follow="Y";
				}else{
					
					$is_follow="N";
				}
				
				$users = User::where(['_id'=>$user_ids])->first();
				$following[]=array(
					'user_id'=>$users->_id?$users->_id:'',
					'first_name'=>$users->first_name?$users->first_name:'',
					'last_name'=>$users->last_name?$users->last_name:'',
					'email'=>$users->email?$users->email:'',
					'profile_image' => ($users)?($users->profile_image?url('public/images',$users->profile_image):''):'',
					'is_follow' => $is_follow,
				);
			}

			$followers = Follow::where(['other_user_id'=> $saveArray['user_id']])->get();
			$follower=array();
			foreach($followers as $followers){
				$user_ids=$followers->user_id;
				
				$isFollowStatus = Follow::where(['user_id'=> $saveArray['user_id'],'other_user_id'=> $user_ids])->first();
				if($isFollowStatus){
					
					$is_follow="Y";
				}else{
					
					$is_follow="N";
				}
				
				
				$users=User::where(['_id'=>$user_ids])->first() ;
				$follower[]=array(
					'user_id'=>$users->_id,
					'first_name'=>$users->first_name,
					'last_name'=>$users->last_name,
					'email'=>$users->email,
					'profile_image' => ($users)?($users->profile_image?url('public/images',$users->profile_image):''):'',
					'is_follow' => $is_follow,
				);
			}
			return response()->json([ 
				'status' => true,
				'message' => 'followers and following list',
				'following'=>$following,
				'follower'=>$follower
			], $this->successCode);
		}	
	}





#************************************************************************************#	

	public function addDiet(Request $request){
		
		$saveArray = $request->all();
		
			$insertData = [
				'user_id'=>$saveArray['user_id'],
				'name'=>$saveArray['name'],
				'description'=>$saveArray['description'],
				'image'=>'default.png',
				'period'=>$saveArray['period'],
				'amount'=>$saveArray['amount'],
				'created_at'=>date('Y-m-d H:i:s')
			];
			$diet_id = Diet::insertGetId($insertData);
			echo  $diet_id;
			
		
	}


#************************************************************************************#	

	public function get_diets(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'post_type' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			if($saveArray['post_type']=='my_diets'){
				$diets = Diet::where(['user_id'=>$saveArray['user_id']])->get()->toarray();
			}else{
				$diets = Diet::get()->toarray();
			}
			
	
			if($diets){
				
				foreach($diets as $diets){
					

					$helper = new Helpers();
						
					$records[]=array(
					'diet_id'=>$diets['_id'],
					'user_id'=>$diets['user_id'],
					'name'=>$diets['name'],
					'description'=>$diets['description'],
					'image'=>url('public/images/diet',$diets['image']),
					'period'=>$diets['period'],
					'amount'=>$diets['amount'].' Cal/Day',
					'rating'=>$diets['rating'],
					'created_at'=>$helper->time_elapsed_string($diets['created_at']),
					);
				
				}
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}

	}
	
#************************************************************************************#	

	public function my_diets(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$diets = Diet::where(['user_id'=>$saveArray['user_id']])->get()->toarray();
			//$diets = Diet::get()->toarray();
	
			if($diets){
				
				foreach($diets as $diets){
					

					$helper = new Helpers();
						
					$records[]=array(
					'diet_id'=>$diets['_id'],
					'user_id'=>$diets['user_id'],
					'name'=>$diets['name'],
					'description'=>$diets['description'],
					'image'=>url('public/images/diet',$diets['image']),
					'period'=>$diets['period'],
					'amount'=>$diets['amount'].' Cal/Day',
					'rating'=>$diets['rating'],
					'created_at'=>$helper->time_elapsed_string($diets['created_at']),
					);
				
				}
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}

	}


#************************************************************************************#	

	public function diet_detail(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'diet_id' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$diets = Diet::where(['_id'=>$saveArray['diet_id']])->get()->toarray();
			//$diets = Diet::get()->toarray();
	
			if($diets){
				
				foreach($diets as $diets){
					

					$helper = new Helpers();
						
					$records=array(
					'diet_id'=>$diets['_id'],
					'user_id'=>$diets['user_id'],
					'name'=>$diets['name'],
					'description'=>$diets['description'],
					'image'=>url('public/images/diet',$diets['image']),
					'period'=>$diets['period'],
					'amount'=>$diets['amount'].' Cal/Day',
					'rating'=>$diets['rating'],
					'comment_count'=>'0',
					'created_at'=>$helper->time_elapsed_string($diets['created_at']),
					);
				
				}
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}

	}


#************************************************************************************#	

	public function addComment(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'post_id' => 'required',	
			'comment' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
		
		$saveArray = $request->all();
		
		if(empty($saveArray['parent_id'])){
			
			$saveArray['parent_id']="";
		}
		
			$insertData = [
				'user_id'=>$saveArray['user_id'],
				'parent_id'=>$saveArray['parent_id'],
				'post_id'=>$saveArray['post_id'],
				'comment'=>$saveArray['comment'],
				'delete_status'=>'1',
				'created_at'=>date('Y-m-d H:i:s')
			];
			$comment_id = Comment::insertGetId($insertData);
			//echo  $comment_id;
			
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
				], $this->successCode);
			
		}
	}


#************************************************************************************#	

		public function addLike(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'post_id' => 'required',	
			'like_status' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$likes = Like::where(['user_id'=> $saveArray['user_id'],'post_id'=> $saveArray['post_id']])->first() ;
			
				if($likes){ 
			
					Like::where(['user_id'=> $saveArray['user_id'],'post_id'=> $saveArray['post_id']])->update(['like_status'=>$saveArray['like_status']]);
		        	
	
				}else{
					
					$saveArray = $request->all();
		
					$insertData = [
						'user_id'=>$saveArray['user_id'],
						'post_id'=>$saveArray['post_id'],
						'like_status'=>$saveArray['like_status'],
						'created_at'=>date('Y-m-d H:i:s')
					];
					$like_id = Like::insertGetId($insertData);
			
				}
		
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'like_status' => $saveArray['like_status'],
				], $this->successCode);
			
		}
	}


#************************************************************************************#	

	public function getComment(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'post_id' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			//$comments = Comment::where(['user_id'=>$saveArray['user_id'],'post_id'=>$saveArray['post_id'],'parent_id'=>""])->get()->toarray();
			$comments = Comment::where(['post_id'=>$saveArray['post_id'],'parent_id'=>""])->get()->toarray();
	
			if($comments){
				
				$records=array();
				foreach($comments as $comments){
					
					$helper = new Helpers();
					
						$commentUserExist = User::where(['_id'=>$comments['user_id']])->first();
			
						$commentUserImage = "";
						if(strpos($commentUserExist['profile_image'], "http://") !== false){
							$commentUserImage = $commentUserExist['profile_image'];
						}
						else{
							$commentUserImage = url('public/images',$commentUserExist['profile_image']);
						}
					
					
					$reply = Comment::where(['parent_id'=>$comments['_id']])->get()->toarray();
					
					$records1=array();
					
					foreach($reply as $reply){
						
						
							$replyUserExist = User::where(['_id'=>$reply['user_id']])->first();
				
							$replyUserImage = "";
							if(strpos($replyUserExist['profile_image'], "http://") !== false){
								$replyUserImage = $replyUserExist['profile_image'];
							}
							else{
								$replyUserImage = url('public/images',$replyUserExist['profile_image']);
							}
						
						$records1[]=array(
						'comment_id'=>$reply['_id'],
						//'user_id'=>$reply['user_id'],
						//'post_id'=>$reply['post_id'],
						'userName'=>ucwords($replyUserExist['first_name'].' '.$replyUserExist['last_name']),
						'userImage'=>$replyUserImage,
						'comment'=>$reply['comment'],
						'created_at'=>$helper->time_elapsed_string($reply['created_at']),
						);
						
					}
					
					
						
					$records[]=array(
					'comment_id'=>$comments['_id'],
					//'user_id'=>$comments['user_id'],
					//'post_id'=>$comments['post_id'],
					'userName'=>ucwords($commentUserExist['first_name'].' '.$commentUserExist['last_name']),
					'userImage'=>$commentUserImage,
					'comment'=>$comments['comment'],
					'created_at'=>$helper->time_elapsed_string($comments['created_at']),
					'reply'=>$records1,
					);
				
				}//die;
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}

	}


#************************************************************************************#	

	public function addWorkout(Request $request){
		
		$saveArray = $request->all();

					$insertData = [
						'user_id'=>$saveArray['user_id'],
						'title'=>$saveArray['title'],
						'description'=>$saveArray['description'],
						'type'=>$saveArray['type'],
						'page_for'=>$saveArray['page_for'],
						'image'=>$saveArray['image'],
						'exercises'=>$saveArray['exercises'],
						'time'=>$saveArray['time'],
						'rating'=>'',
						'created_at'=>date('Y-m-d H:i:s')
					];
					
				//	$wordout_id = Workout::insertGetId($insertData);
					
		///echo $wordout_id;
		//	echo "only for backend use.";
				
	}


#************************************************************************************#	


	public function getForYoga(Request $request){
		
			$saveArray = $request->all();
		
			$validator = Validator::make($request->all(), [ 
				'user_id' => 'required',	
				'type' => 'required',	
				//'page_for' => 'required',	
			]);
			
			if($validator->fails()) {
				
				return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);     
				
			}else{
			
					$saveArray['page_for']='yoga';

					if($saveArray['type'] =='exercise'){
						
						$category = Category::get()->toarray();
						
						//$category=array('1','2');
						
						$records=array();
						
						if($category){

							foreach($category as $category){
								
								$workouts = Workout::where(['page_for'=>$saveArray['page_for'],'type'=>$saveArray['type'],'category'=>$category['_id']])->get()->toarray();
								//$workouts = Workout::where(['type'=>$saveArray['type'],'category'=>$category['_id']])->get()->toarray();

								$record1=array();
								
								foreach($workouts as $workouts){
									
									$trainerDetails = Trainer::where(['_id'=>$workouts['user_id']])->first();
									
									$image = url('public/images',$workouts['image']);

									$helper = new Helpers();
										
									$records[]=array(
									//'category'=>$workouts['category'],
									'cat_name'=>$category['name'],
									'workout_id'=>$workouts['_id'],
									'user_id'=>$workouts['user_id'],
									'by'=>ucwords($trainerDetails->name),
									'title'=>ucwords($workouts['title']),
									'description'=>$workouts['description'],
									'image'=>$image,
									'exercises'=>"",
									'time'=>"",
									'created_at'=>$workouts['created_at'],
									'rating'=>$workouts['rating'],
									'ago'=>$helper->time_elapsed_string($workouts['created_at']),
									);
								
								}

								/*$records[]=array(
								'cat_id'=>$category['_id'],
								'cat_name'=>$category['name'],
								'records'=>$record1,
								
								);*/
						
							}
						
						}else{
							
							return response()->json([ 
								'status' => false,
								'message' => 'Not found',
							], $this->successCode);
						}
					}
		
					if( $saveArray['type'] == 'workout' || $saveArray['type'] =='myworkout'){
						
						if($saveArray['type'] =='workout'){	

							$workouts = Workout::where(['page_for'=>$saveArray['page_for'],'type'=>$saveArray['type']])->get()->toarray();

						}
						
						if($saveArray['type'] =='myworkout'){	
							
							$saveArray['type']='workout';
								
							$workouts = Workout::where(['page_for'=>$saveArray['page_for'],'type'=>$saveArray['type'],'user_id'=>$saveArray['user_id']])->get()->toarray();
							
						}
					
						if($workouts){
							
							foreach($workouts as $workouts){
								
								$trainerDetails = Trainer::where(['_id'=>$workouts['user_id']])->first();
								
								$image = url('public/images',$workouts['image']);

								$helper = new Helpers();
									
								$records[]=array(
								'workout_id'=>$workouts['_id'],
								'user_id'=>$workouts['user_id'],
								'by'=>ucwords($trainerDetails->name),
								'title'=>ucwords($workouts['title']),
								'description'=>$workouts['description'],
								'image'=>$image,
								'exercises'=>$workouts['exercises'],
								'time'=>$workouts['time'],
								'created_at'=>$workouts['created_at'],
								'rating'=>$workouts['rating'],
								'ago'=>$helper->time_elapsed_string($workouts['created_at']),
								);
								
							}
							
						}else{
							return response()->json([ 
								'status' => false,
								'message' => 'Not found',
							], $this->successCode);
						}	
					}
			
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
	
		}

	}




#************************************************************************************#	





	public function getForWeightLifting(Request $request){
		
			$saveArray = $request->all();
		
			$validator = Validator::make($request->all(), [ 
				'user_id' => 'required',	
				'type' => 'required',	
				//'page_for' => 'required',	
			]);
			
			if($validator->fails()) {
				
				return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);     
				
			}else{
			
					$saveArray['page_for']='weightlifting';

					if($saveArray['type'] =='exercise'){

						$category = Category::get()->toarray();
						
						$records=array();
						
						if($category){

							foreach($category as $category){
								
								$workouts = Workout::where(['page_for'=>$saveArray['page_for'],'type'=>$saveArray['type'],'category'=>$category['_id']])->get()->toarray();
								//$workouts = Workout::where(['type'=>$saveArray['type'],'category'=>$category['_id']])->get()->toarray();

								$record1=array();
								$records=array();
								
								foreach($workouts as $workouts){
									
									$trainerDetails = Trainer::where(['_id'=>$workouts['user_id']])->first();
									
									$image = url('public/images',$workouts['image']);

									$helper = new Helpers();
										
									$records[]=array(
									//'category'=>$workouts['category'],
									'cat_name'=>$category['name'],
									'workout_id'=>$workouts['_id'],
									'user_id'=>$workouts['user_id'],
									'by'=>ucwords($trainerDetails->name),
									'title'=>ucwords($workouts['title']),
									'description'=>$workouts['description'],
									'image'=>$image,
									'exercises'=>"",
									'time'=>"",
									'created_at'=>$workouts['created_at'],
									'rating'=>$workouts['rating'],
									'ago'=>$helper->time_elapsed_string($workouts['created_at']),
									);
								
								}

								/*$records[]=array(
								'cat_id'=>$category['_id'],
								'cat_name'=>$category['name'],
								'records'=>$record1,
								
								);*/
						
							}
						
						}else{
							
							return response()->json([ 
								'status' => false,
								'message' => 'Not found',
							], $this->successCode);
						}
					}
		
					if( $saveArray['type'] == 'workout' || $saveArray['type'] =='myworkout'){
						
						if($saveArray['type'] =='workout'){	

							$workouts = Workout::where(['page_for'=>$saveArray['page_for'],'type'=>$saveArray['type']])->get()->toarray();

						}
						
						if($saveArray['type'] =='myworkout'){	
							
							$saveArray['type']='workout';
								
							$workouts = Workout::where(['page_for'=>$saveArray['page_for'],'type'=>$saveArray['type'],'user_id'=>$saveArray['user_id']])->get()->toarray();
							
						}
					
						if($workouts){
							
							foreach($workouts as $workouts){
								
								$trainerDetails = Trainer::where(['_id'=>$workouts['user_id']])->first();
								
								$image = url('public/images',$workouts['image']);

								$helper = new Helpers();
									
								$records[]=array(
								'workout_id'=>$workouts['_id'],
								'user_id'=>$workouts['user_id'],
								'by'=>ucwords($trainerDetails->name),
								'title'=>ucwords($workouts['title']),
								'description'=>$workouts['description'],
								'image'=>$image,
								'exercises'=>$workouts['exercises'],
								'time'=>$workouts['time'],
								'created_at'=>$workouts['created_at'],
								'rating'=>$workouts['rating'],
								'ago'=>$helper->time_elapsed_string($workouts['created_at']),
								);
								
							}
							
						}else{
							return response()->json([ 
								'status' => false,
								'message' => 'Not found',
							], $this->successCode);
						}	
					}
			
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
	
		}

	}


#************************************************************************************#	

	public function changeAccountType(Request $request){
		$saveArray = $request->all();
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',
			'account_type' => 'required'
		]);
		if($validator->fails()) {
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			$user_exist = User::where(['_id'=>$saveArray['user_id']])->first();
			
			if($user_exist){
				$updateData = [
					'account_type'=>$saveArray['account_type'],
				];

				$update = User::where(['_id'=>$saveArray['user_id']])->update($updateData);
			

			if($update){

				return response()->json([ 
					'status' => true,
					'message' => 'Updated Successfully.',
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Error on profile updation',
				], $this->successCode); 
			}
			}else{
				return response()->json([ 
					'status' => false,
					'message' => 'Invalid User.',
				], $this->successCode); 
				
			}
		}
	}




#************************************************************************************#	

	public function AddBlockUnblock(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'other_user_id' => 'required',	
			'block_status' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$block = Block::where(['user_id'=> $saveArray['user_id'],'other_user_id'=> $saveArray['other_user_id']])->first() ;
			
				if($block){ 
			
					Block::where(['user_id'=> $saveArray['user_id'],'other_user_id'=> $saveArray['other_user_id']])->update(['block_status'=>$saveArray['block_status']]);
		        	
	
				}else{
					
					$saveArray = $request->all();
		
					$insertData = [
						'user_id'=>$saveArray['user_id'],
						'other_user_id'=>$saveArray['other_user_id'],
						'block_status'=>$saveArray['block_status'],
						'created_at'=>date('Y-m-d H:i:s')
					];
					$block_id = Block::insertGetId($insertData);
			
				}
		
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'block_status' => $saveArray['block_status'],
				], $this->successCode);
			
		}
	}



#************************************************************************************#	


	public function searchUser(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'name' => 'required',	
		//	'other_user_id' => 'required',	
			//'block_status' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$user_exist = User::where('first_name', 'like', $saveArray["name"].'%')->get()->toarray();
			
			//print_R($users);die;
		
				if($user_exist){ 
				
				foreach($user_exist as $user_exist){
					
					
					$followerCount = Follow::where(['other_user_id'=> $user_exist['_id']])->count();
					$followingCount = Follow::where(['user_id'=> $user_exist['_id']])->count();

					$profile_image = "";
					if(strpos($user_exist['profile_image'], "http://") !== false){
						$profile_image = $user_exist['profile_image'];
					}
					else{
						$profile_image = url('public/images',$user_exist['profile_image']);
					}
			
					$records[]=array(
						'user_id'=>(String)$user_exist['_id'],
						'login_type'=>$user_exist['login_type'],
						'social_id'=>$user_exist['social_id']?$user_exist['social_id']:'',
						'first_name'=>$user_exist['first_name'],
						'last_name'=>$user_exist['last_name'],
						'email'=>$user_exist['email'],
						'location'=>$user_exist['location'],
						'profile_image'=>$profile_image,
						'verified'=>'Y',
						//'age'=>$user_exist->age?$user_exist->age:'',
						//'bio'=>$user_exist->bio?$user_exist->bio:'',
						//'body_mass'=>$user_exist->body_mass?$user_exist->body_mass:'',
						//'goal'=>$goal,
						//'height'=>$user_exist['height']?$user_exist['height']:'',
						//'weight'=>$user_exist['weight']?$user_exist['weight']:'',
						'followerCount'=>$followerCount,
						'followingCount'=>$followingCount,
					);
					
					
				
				}
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		
				
			
		}
	}
	
#************************************************************************************#	


	public function addFavorite(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'other_user_id' => 'required',	
			'favorite_status' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$favorites = Favorite::where(['user_id'=> $saveArray['user_id'],'other_user_id'=> $saveArray['other_user_id']])->first() ;
			
				if($favorites){ 
			
					Favorite::where(['user_id'=> $saveArray['user_id'],'other_user_id'=> $saveArray['other_user_id']])->update(['favorite_status'=>$saveArray['favorite_status']]);
		        	
	
				}else{
					
					$saveArray = $request->all();
		
					$insertData = [
						'user_id'=>$saveArray['user_id'],
						'other_user_id'=>$saveArray['other_user_id'],
						'favorite_status'=>$saveArray['favorite_status'],
						'created_at'=>date('Y-m-d H:i:s')
					];
					$favorite_id = Favorite::insertGetId($insertData);
			
				}
		
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'favorite_status' => $saveArray['favorite_status'],
				], $this->successCode);
			
		}
	}


#************************************************************************************#	


	public function myFavoriteList(Request $request){
	
	$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$favorites = Favorite::where(['user_id'=>$saveArray['user_id'],'favorite_status'=>'favorite'])->get()->toarray();
			//$diets = Diet::get()->toarray();
	
			if($favorites){
				
				foreach($favorites as $favorites){
					
					$user_exist = User::where(['_id'=>$favorites['other_user_id']])->first()->toarray();
					
					//print_R($user_exist);die;
					$profile_image = "";
					if(strpos($user_exist['profile_image'], "http://") !== false){
						$profile_image = $user_exist['profile_image'];
					}
					else{
						$profile_image = url('public/images',$user_exist['profile_image']);
					}
			
					$records[]=array(
						'user_id'=>(String)$user_exist['_id'],
						//'login_type'=>$user_exist['login_type'],
						//'social_id'=>$user_exist['social_id']?$user_exist['social_id']:'',
						'first_name'=>$user_exist['first_name'],
						'last_name'=>$user_exist['last_name'],
						'email'=>$user_exist['email'],
						'location'=>$user_exist['location'],
						'profile_image'=>$profile_image,
						'verified'=>'Y',
						//'age'=>$user_exist->age?$user_exist->age:'',
						//'bio'=>$user_exist->bio?$user_exist->bio:'',
						//'body_mass'=>$user_exist->body_mass?$user_exist->body_mass:'',
						//'goal'=>$goal,
						//'height'=>$user_exist['height']?$user_exist['height']:'',
						//'weight'=>$user_exist['weight']?$user_exist['weight']:'',
						//'followerCount'=>$followerCount,
						//'followingCount'=>$followingCount,
					);
				
				}
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}
	}


#************************************************************************************#	

	 public function changePassword(Request $request){
		 
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'old_password' => 'required',	
			'new_password' => 'required',	
		]);
		
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode); 
			
		}else{
			
			$userExist = User::where(['_id'=> $saveArray['user_id']])->first();
			
	        if($userExist){ 
			
				if(Hash::check($saveArray['old_password'],$userExist['password'])){
		
					$new_password = Hash::make($saveArray['new_password']);
				
					$update_password = [
					'password' => $new_password,
					'updated_at' => date('Y-m-d h:i:s')];
				
				
					$update = User::where(['_id'=>$saveArray['user_id']])->update($update_password);
					
					if($update){

						return response()->json(['status'=>true,'message'=>'Password Changed Successfully.'], $this->successCode);
					}
			
				}else{
					return response()->json(['message'=>'User and Password is incorrect','status'=>false], $this->successCode); 
				}
			}else{
				return response()->json(['message'=>'Invalid user id','status'=>false], $this->successCode); 
			}

		}
	}

#************************************************************************************#	


	public function getBlockUserList(Request $request){
	
	$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$block = Block::where(['user_id'=>$saveArray['user_id'],'block_status'=>'block'])->get()->toarray();
			//$diets = Diet::get()->toarray();
	
			if($block){
				
				foreach($block as $block){
					
					$user_exist = User::where(['_id'=>$block['other_user_id']])->first()->toarray();
					
					//print_R($user_exist);die;
					$profile_image = "";
					if(strpos($user_exist['profile_image'], "http://") !== false){
						$profile_image = $user_exist['profile_image'];
					}
					else{
						$profile_image = url('public/images',$user_exist['profile_image']);
					}
			
					$records[]=array(
						'user_id'=>(String)$user_exist['_id'],
						//'login_type'=>$user_exist['login_type'],
						//'social_id'=>$user_exist['social_id']?$user_exist['social_id']:'',
						'first_name'=>$user_exist['first_name'],
						'last_name'=>$user_exist['last_name'],
						'email'=>$user_exist['email'],
						'location'=>$user_exist['location'],
						'profile_image'=>$profile_image,
						'verified'=>'Y',
						//'age'=>$user_exist->age?$user_exist->age:'',
						//'bio'=>$user_exist->bio?$user_exist->bio:'',
						//'body_mass'=>$user_exist->body_mass?$user_exist->body_mass:'',
						//'goal'=>$goal,
						//'height'=>$user_exist['height']?$user_exist['height']:'',
						//'weight'=>$user_exist['weight']?$user_exist['weight']:'',
						//'followerCount'=>$followerCount,
						//'followingCount'=>$followingCount,
					);
				
				}
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}
	}


#************************************************************************************#	


	public function getWorkoutDeatils(Request $request){
		
			$saveArray = $request->all();
		
			$validator = Validator::make($request->all(), [ 
				'user_id' => 'required',	
				'workout_id' => 'required',		
			]);
			
			if($validator->fails()) {
				
				return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);     
				
			}else{
			

					$workouts = Workout::where(['_id'=>$saveArray['workout_id']])->first()->toarray();
					
					//print_R($workouts);die;

						if($workouts){
							
								
								$trainerDetails = Trainer::where(['_id'=>$workouts['user_id']])->first();
								
								$image = url('public/images',$workouts['image']);

								$helper = new Helpers();
									
								$records=array(
								'workout_id'=>$workouts['_id'],
								'user_id'=>$workouts['user_id'],
								'by'=>ucwords($trainerDetails->name),
								'title'=>ucwords($workouts['title']),
								'description'=>$workouts['description'],
								'image'=>$image,
								'exercises'=>$workouts['exercises'],
								'time'=>$workouts['time'],
								'created_at'=>$workouts['created_at'],
								'rating'=>$workouts['rating'],
								'ago'=>$helper->time_elapsed_string($workouts['created_at']),
								);
								
							return response()->json([ 
							'status' => true,
							'message' => 'Success',
							'data'=>$records,
							], $this->successCode);
							
						}else{
							return response()->json([ 
								'status' => false,
								'message' => 'Not found',
							], $this->successCode);
						}	
					}
			
	}

	
#************************************************************************************#	

	public function getExcerciseDetails(Request $request){
		
			$saveArray = $request->all();
		
			$validator = Validator::make($request->all(), [ 
				'user_id' => 'required',	
				'exercise_id' => 'required',		
			]);
			
			if($validator->fails()) {
				
				return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);     
				
			}else{
			

					$workouts = Workout::where(['_id'=>$saveArray['exercise_id']])->first()->toarray();
					
					//print_R($workouts);die;

						if($workouts){
							
								//$category = Category::get()->toarray();
								
								$category = Category::where(['_id'=>$workouts['category']])->first()->toarray();;
								
								$trainerDetails = Trainer::where(['_id'=>$workouts['user_id']])->first();
								
								$image = url('public/images',$workouts['image']);

								$helper = new Helpers();
									
									$records=array(
									'category'=>$category['name'],
									'workout_id'=>$workouts['_id'],
									'user_id'=>$workouts['user_id'],
									'by'=>ucwords($trainerDetails->name),
									'title'=>ucwords($workouts['title']),
									'description'=>$workouts['description'],
									'image'=>$image,
									'exercises'=>"",
									'time'=>"",
									'created_at'=>$workouts['created_at'],
									'rating'=>$workouts['rating'],
									'ago'=>$helper->time_elapsed_string($workouts['created_at']),
									);
								
							return response()->json([ 
							'status' => true,
							'message' => 'Success',
							'data'=>$records,
							], $this->successCode);
							
						}else{
							return response()->json([ 
								'status' => false,
								'message' => 'Not found',
							], $this->successCode);
						}	
					}
			
	}
	
#************************************************************************************#	


	public function reportUserPost(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'post_id' => 'required',		
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$report = Report::where(['user_id'=> $saveArray['user_id'],'post_id'=> $saveArray['post_id']])->first() ;
			
				if($report){ 
			
							return response()->json([ 
								'status' => false,
								'message' => 'Already Reported',
							], $this->successCode);
	
				}else{
					
					$saveArray = $request->all();
		
					$insertData = [
						'user_id'=>$saveArray['user_id'],
						'post_id'=>$saveArray['post_id'],
						'created_at'=>date('Y-m-d H:i:s')
					];
					$report_id = Report::insertGetId($insertData);
			
				}
		
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
				], $this->successCode);
			
		}
	}


#************************************************************************************#	


	 public function makeGymPartnerOnOff(Request $request){
		 
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'gym_partner_status' => 'required',	
		]);
		
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode); 
			
		}else{
			
			$userExist = User::where(['_id'=> $saveArray['user_id']])->first();
			
	        if($userExist){ 
			
	
					$updateData = [
					'gym_partner_status' => $saveArray['gym_partner_status'],
					'updated_at' => date('Y-m-d h:i:s')];
				
				
					$update = User::where(['_id'=>$saveArray['user_id']])->update($updateData);
					
					if($update){

						return response()->json(['status'=>true,'message'=>'Success'], $this->successCode);
					}
			
				
			}else{
				return response()->json(['message'=>'Invalid User.','status'=>false], $this->successCode); 
			}

		}
	}


#************************************************************************************#	


	public function getGoals(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$goals = Goal::where(['status'=>'1'])->get()->toarray();
	
			if($goals){
				
				foreach($goals as $goals){
					
						
					$records[]=array(
						'goal_id'=>$goals['_id'],
						'goal_name'=>$goals['goal'],
					);
				
				}
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}

	}
	
	
#************************************************************************************#	


		public function addStat(Request $request){
		
		$saveArray = $request->all();
		
		
		   /*   "workout_id" : "5c729c1c052fe764fe298b74",
        "day" : "2",
        "daily_desc" : "Sample description will be shown here",
        "sets" : "4",
        "image" : "1.png",
        "hold_stretch_for" : "35",
        "title" : "Demo title"
 */
		
		$insertData = [
				'workout_id'=>'5c729c1c052fe764fe298b74',
				'day'=>'2',
				'daily_desc'=>'Sample description will be shown here',
				'sets'=>'4',
				'image'=>'1.gif',
				'hold_stretch_for'=>'35',
				'title'=>'Demo title',

			];
		
		$UserDetail_id = WorkoutDetail::insertGetId($insertData);
		echo $UserDetail_id;die;
		

			/* $insertData = [
				'user_id'=>$saveArray['user_id'],
				'height'=>'5',
				'weight'=>'180',
				'body_mass_index'=>'25.1',
				'percent_body_fat'=>'9',
				'neck_circumference'=>'9',
				'chest_circumference'=>'40',
				'waist_circumference'=>'40',
				'hip_circumference'=>'40',
				'shoulder_circumference'=>'40',
				'arm_circumference'=>'18',
				'calf_circumference'=>'20',

			]; */
			//$UserDetail_id = UserDetail::insertGetId($insertData);
				//echo $UserDetail_id;die;
				//echo "only for backend use";
		}	
	


#************************************************************************************#	


	public function getUserStat(Request $request){
		 
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
		]);
		
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode); 
			
		}else{
			
			//$userExist = User::where(['_id'=> $saveArray['user_id']])->first();
			
	      //  if($userExist){ 
			
				$userDetails = UserDetail::where(['user_id'=> $saveArray['user_id']])->first();
				
				$workouts = Workout::where(['user_id'=>$saveArray['user_id']])->orderBy('created_at','desc')->limit(2)->get()->toarray();
					//print_R($workouts);die;
					
					
								$records=array();
								
								foreach($workouts as $workouts){
									
									$trainerDetails = Trainer::where(['_id'=>$workouts['user_id']])->first();
									
									
								
									
									$image = url('public/images',$workouts['image']);

									$helper = new Helpers();
										
									$records[]=array(
									//'category'=>$workouts['category'],
									//'cat_name'=>$category['name'],
									'workout_id'=>$workouts['_id'],
									'user_id'=>$workouts['user_id'],
									'by'=>ucwords($trainerDetails->name),
									'title'=>ucwords($workouts['title']),
									'description'=>$workouts['description'],
									'image'=>$image,
									'exercises'=>"",
									'time'=>"",
									'created_at'=>$workouts['created_at'],
									'rating'=>$workouts['rating'],
									'ago'=>$helper->time_elapsed_string($workouts['created_at']),
									);
								
								}

					
				return response()->json([ 
						'status' => true,
						'data'=>[
							'user_id'=>$userDetails['user_id']?$userDetails['user_id']:$saveArray['user_id'],
							'height'=>$userDetails['height']?$userDetails['height']:'',
							'weight'=>$userDetails['weight']?$userDetails['weight']:'',
							'body_mass_index'=>$userDetails['body_mass_index']?$userDetails['body_mass_index']:'',
							'percent_body_fat'=>$userDetails['percent_body_fat']?$userDetails['percent_body_fat']:'',
							'neck_circumference'=>$userDetails['neck_circumference']?$userDetails['neck_circumference']:'',
							'chest_circumference'=>$userDetails['chest_circumference']?$userDetails['chest_circumference']:'',
							'waist_circumference'=>$userDetails['waist_circumference']?$userDetails['waist_circumference']:'',
							'hip_circumference'=>$userDetails['hip_circumference']?$userDetails['hip_circumference']:'',
							'shoulder_circumference'=>$userDetails['shoulder_circumference']?$userDetails['shoulder_circumference']:'',
							'arm_circumference'=>$userDetails['arm_circumference']?$userDetails['arm_circumference']:'',
							'calf_circumference'=>$userDetails['calf_circumference']?$userDetails['calf_circumference']:'',
						],
						'latest_workouts'=>$records,
					], $this->successCode);
			
				
			//}else{
			//	return response()->json(['message'=>'Invalid User.','status'=>false], $this->successCode); 
		//	}

		}
	}

#************************************************************************************#	

	
	public function getForRunning(Request $request){
		
			$saveArray = $request->all();
		
			$validator = Validator::make($request->all(), [ 
				'user_id' => 'required',	
				'type' => 'required',	
				//'page_for' => 'required',	
			]);
			
			if($validator->fails()) {
				
				return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);     
				
			}else{
			
					$saveArray['page_for']='running';

					$records=array();
		
					if( $saveArray['type'] == 'workout' || $saveArray['type'] =='myworkout'){
						
						if($saveArray['type'] =='workout'){	

							$workouts = Workout::where(['page_for'=>$saveArray['page_for'],'type'=>$saveArray['type']])->get()->toarray();

						}
						
						if($saveArray['type'] =='myworkout'){	
							
							$saveArray['type']='workout';
								
							$workouts = Workout::where(['page_for'=>$saveArray['page_for'],'type'=>$saveArray['type'],'user_id'=>$saveArray['user_id']])->get()->toarray();
							
						}
					
						if($workouts){
							
							foreach($workouts as $workouts){
								
								$trainerDetails = Trainer::where(['_id'=>$workouts['user_id']])->first();
								
								$image = url('public/images',$workouts['image']);

								$helper = new Helpers();
									
								$records[]=array(
								'workout_id'=>$workouts['_id'],
								'user_id'=>$workouts['user_id'],
								'by'=>ucwords($trainerDetails->name)?$trainerDetails->name:'',
								'title'=>ucwords($workouts['title']),
								'description'=>$workouts['description'],
								'image'=>$image,
								'exercises'=>$workouts['exercises'],
								'time'=>$workouts['time'],
								'created_at'=>$workouts['created_at'],
								'rating'=>$workouts['rating']?$workouts['rating']:'',
								'ago'=>$helper->time_elapsed_string($workouts['created_at']),
								);
								
							}
							
						}else{
							return response()->json([ 
								'status' => false,
								'message' => 'Not found',
							], $this->successCode);
						}	
					}
			
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
	
		}

	}

	public function dietDailyDetails(Request $request){
		
			$saveArray = $request->all();
		
			$validator = Validator::make($request->all(), [ 
				'diet_id' => 'required',	
				'day' => 'required'
			]);
			
			if($validator->fails()) {
				
				return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);     
				
			}else{
			
				$details = DietDetail::where(['diet_id'=>$saveArray['diet_id']])->where(['day'=>$saveArray['day']])->first();
				if($details){
					return response()->json([ 
							'status' => true,
							'message' => 'Success',
							'description'=>$details['daily_desc'],
						], $this->successCode);
				}
				else{
					return response()->json([ 
								'status' => false,
								'message' => 'Not found',
							], $this->successCode);
				}
	
		}

	}

	public function getAllWorkoutDetail(Request $request){
		
			$saveArray = $request->all();
		
			$validator = Validator::make($request->all(), [ 
				'workout_id' => 'required',	
				'day' => 'required'
			]);
			
			if($validator->fails()) {
				
				return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);     
				
			}else{
			
				$details = WorkoutDetail::where(['workout_id'=>$saveArray['workout_id']])->where(['day'=>$saveArray['day']])->first();
				if($details){
					return response()->json([ 
							'status' => true,
							'message' => 'Success',
							'description'=>$details['daily_desc'],
						], $this->successCode);
				}
				else{
					return response()->json([ 
								'status' => false,
								'message' => 'Not found',
							], $this->successCode);
				}
	
		}

	}

#************************************************************************************#	


	public function WorkoutDayDetail(Request $request){
		
			$saveArray = $request->all();
		
			$validator = Validator::make($request->all(), [ 
				'workout_id' => 'required',	
				'day' => 'required'
			]);
			
			if($validator->fails()) {
				
				return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);     
				
			}else{
			
				$details = WorkoutDetail::where(['workout_id'=>$saveArray['workout_id']])->where(['day'=>$saveArray['day']])->get()->toarray();
				
				//print_R($details);die;
				if($details){
					foreach($details as $details){
						$workout = Workout::where(['_id'=>$details['workout_id']])->select('time')->first();
						$record[]=array(
						'workout_id'=>$details['workout_id'],
						'day'=>$details['day'],
						'title'=>$details['title'],
						'image'=>url('public/images/image',$details['image']),
						'sets'=>$details['sets'],
						'hold_stretch_for'=>$details['hold_stretch_for'],
						'time'=>$workout['time']
						);
						
					}
					return response()->json([ 
							'status' => true,
							'message' => 'Success',
							'record'=>$record,
						], $this->successCode);
				}
				else{
					return response()->json([ 
								'status' => false,
								'message' => 'Not found',
							], $this->successCode);
				}
	
		}

	}



#*************************************************************************************#	


	public function TrainingDayDetail(Request $request){
		
			$saveArray = $request->all();
		
			$validator = Validator::make($request->all(), [ 
				'training_id' => 'required',	
				'day' => 'required'
			]);
			
			if($validator->fails()) {
				
				return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);     
				
			}else{
			
				$details = TrainingDetail::where(['training_id'=>$saveArray['training_id']])->where(['day'=>$saveArray['day']])->get()->toarray();
				
				//print_R($details);die;
				if($details){
					foreach($details as $details){

						$record[]=array(
						'training_id'=>$details['training_id'],
						'day'=>$details['day'],
						'title'=>$details['title'],
						'image'=>url('public/images/image',$details['image']),
						'subtitle'=>$details['subtitle'],
						'time'=>$details['time'],
						'miles'=>$details['miles'],
						);
						
					}
					return response()->json([ 
							'status' => true,
							'message' => 'Success',
							'record'=>$record,
						], $this->successCode);
				}
				else{
					return response()->json([ 
								'status' => false,
								'message' => 'Not found',
							], $this->successCode);
				}
	
		}

	}



#*************************************************************************************#	


	public function addToMyDiet(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
			'diet_id' => 'required',	
			//'diet_status' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
			$diets = MyDiet::where(['user_id'=> $saveArray['user_id'],'diet_id'=> $saveArray['diet_id']])->first() ;
			
				if($diets){ 
			
					//MyDiet::where(['user_id'=> $saveArray['user_id'],'diet_id'=> $saveArray['diet_id']])->update(['diet_status'=>$saveArray['diet_status']]);
					
					return response()->json([ 
					'status' => false,
					'message' => 'Diet Alredy Added',
				], $this->successCode);
		        	
	
				}else{
					
					$saveArray = $request->all();
					
					$saveArray['diet_status']=1;
					
					$insertData = [
						'user_id'=>$saveArray['user_id'],
						'diet_id'=>$saveArray['diet_id'],
						'diet_status'=>$saveArray['diet_status'],
						'created_at'=>date('Y-m-d H:i:s')
					];
					$lastInsertId = MyDiet::insertGetId($insertData);
			
				}
		
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					//'diet_status' => $saveArray['diet_status'],
				], $this->successCode);
			
		}
	}



#*************************************************************************************#	

	public function myDiet(Request $request){
		
		$saveArray = $request->all();
		
		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required',	
		]);
		if($validator->fails()) {
			
			return response()->json(['message'=>$validator->errors()->first(),'status'=>false], $this->badRequestCode);            
		}
		else{
			
		
			$diet = MyDiet::where(['user_id'=> $saveArray['user_id']])->get()->toarray();
	
			if($diet){
				
				foreach($diet as $diet){
					
					$diets = Diet::where(['_id'=> $diet['diet_id']])->first() ;
					
					$helper = new Helpers();
						
					$records[]=array(
					'diet_id'=>$diets['_id'],
					'user_id'=>$diets['user_id'],
					'name'=>$diets['name'],
					'description'=>$diets['description'],
					'image'=>url('public/images/diet',$diets['image']),
					'period'=>$diets['period'],
					'amount'=>$diets['amount'].' Cal/Day',
					'rating'=>$diets['rating'],
					'created_at'=>$helper->time_elapsed_string($diets['created_at']),
					);
				
				}
				
				return response()->json([ 
					'status' => true,
					'message' => 'Success',
					'data'=>$records,
				], $this->successCode);
			}
			else{
				return response()->json([ 
					'status' => false,
					'message' => 'Not found',
				], $this->successCode);
			}
		}

	}

#*************************************************************************************#	
	
	
}
