<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Log;
use App\Models\Token;
use App\Models\Client;
use App\Models\BloodType;
use App\Mail\ResetPassword;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Api\v1\Controller;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
	  
	  /**
     * @throws BindingResolutionException
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = validator()->make($request->all(), [
            'name' => 'required',
            'city_id' => 'required|exists:cities,id',
            'phone' => 'required|digits:11',
            'last_donation_date' => 'required|date',
            'password' => 'required|confirmed',
            'email' => 'required|unique:clients',
            'blood_type_id' => 'required|exists:blood_types,id',
        ]);

        if ($validator->fails()) {
            return responseJson(401, $validator->errors()->first(), $validator->errors());
        }
        $request->merge(['password' => bcrypt($request->password)]);
        $client = Client::create($request->all());
        $client->api_token = Str::random(70);
        $client->save();
        $client->Governorates()->attach($request->governorate_id);
        $bloodClient = BloodType::where('id', $request->blood_type_id)->first();
        $client->blood_Types()->attach($bloodClient->id);

        return responseJson(200, 'it`s done', [
            'api_token' => $client->api_token,
            'client' => $client
        ]);
    }


    /**
     * @throws BindingResolutionException
     */
    public function registerToken(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = validator()->make($request->all(), [
            'token' => 'required',
            'platform' => 'required|in:android,ios',
        ]);

        if ($validator->fails()) {
            return responseJson(401, $validator->errors()->first(), $validator->errors());
        }


        Token::where('token', $request->token)->delete();

        $request->user()->Tokens()->create($request->all());

        return responseJson(200, 'تم تسجيل الدخول بنجاح ');
    }


    /**
     * @throws BindingResolutionException
     */
    public function removeToken(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = validator()->make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(401, $validator->errors()->first(), $validator->errors());
        }

        Token::where('token', $request->token)->delete();

        return responseJson(200, 'تم الحذف بنجاح');
    }


    /**
     * @throws BindingResolutionException
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $client = Client::where('phone', $request->phone)->first();
        if ($client) {
            if (Hash::check($request->password, $client->password)) {
                return responseJson(200, 'welcome back', [
                    'api_token' => $client->api_token,
                    'client' => $client
                ]);
            } else {
                return responseJson(401, 'not authorized', null);
            }
        } else {
            return responseJson(401, 'not authorized', null);
        }
    }








    public function profile(Request $request)
    {
        Log::create(['content' => $request->all(), 'service' => 'profile']);

        $validator = validator()->make($request->all(), [
            'password' => 'confirmed',
            'email' => Rule::unique('clients')->ignore($request->user()->id),
            'phone' => Rule::unique('clients')->ignore($request->user()->id),
        ]);

        if ($validator->fails()) {
            return responseJson(401, $validator->errors()->first(), $validator->errors());
        }

        $loginUser = $request->user();

        $loginUser->update($request->all());

        if ($request->has('password')) {
            $loginUser->$request = bcrypt($request->password);
        }

        $loginUser->save();

        if ($request->has('governorate_id')) {
            $loginUser->Governorates()->detach($request->governorate_id);
            $loginUser->Governorates()->attach($request->governorate_id);
        }

        if ($request->has('blood_type_id')) {
            $bloodUpdate = BloodType::where('id', $request->blood_type_id)->first();
            $loginUser->blood_Types()->detach($bloodUpdate->id);
            $loginUser->blood_Types()->attach($bloodUpdate->id);
        }

        return responseJson(200, 'تم تحديث البيانات بنجاح ', $loginUser);
    }








    public function newPassword(Request $request) //name
    {
        $validator = validator()->make($request->all(), [
            'code' => 'required',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $client = Client::where('pin_code', $request->code)->where('pin_code', '!=', 0)->first();

        if ($client) {
            $client->password = bcrypt($request->password);
            $client->pin_code = null;

            if ($client->save()) {

                return responseJson(200, 'your password updated');
            } else {

                return responseJson(500, 'error in server try aging');
            }
        } else {

            return responseJson(405, 'this code is expired');
        }
    }

    public function forgetPassword(Request $request) //name
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $client = Client::where('phone', $request->phone)->first();
        if ($client) {
            $code = rand(1111, 9999);
            $update = $client->update(['pin_code' => $code]);

            if ($update) {
                Mail::to($client->email)
                    ->bcc("mansobih200@outlook.com")
                    ->send(new ResetPassword($code));

                return responseJson(200, 'check your gmail messages', [
                    'your code to reset password is : ' => $code,
                ]);
            } else {

                return responseJson(500, 'error when update pin code');
            }
        }
    }
}
