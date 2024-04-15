<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\Controller;
use App\Models\BloodType;
use App\Models\Category;
use App\Models\City;
use App\Models\Contact;
use App\Models\DonationRequest;
use App\Models\Post;
use App\Models\Governorate;
use App\Models\Log;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Token;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function governorates()
    {
        $governorates = Governorate::All();

        return responseJson(200, "true", $governorates);
    }



    public function cities(Request $request)
    {
        $cities = City::where(function ($query) use ($request) {
            if ($request->has('governorate_id')) {
                $query->where('governorate_id', $request->governorate_id);
            }
        })->get();

        return responseJson(200, "true", $cities);
    }



    public function logs()
    {
        $requests = Log::latest()->paginate(30);
        return $requests;
    }



    public function posts()
    {
        $posts = Post::with('Category')->paginate(10);
        return responseJson(200, 'this are posts', $posts);
    }



    public function post(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'post_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return responseJson(401, $validator->errors()->first(), $validator->errors());
        }

        $post = Post::find($request->post_id);

        if ($post) {
            return responseJson(200, 'this is post', [
                'title' => $post->title,
                'image' => $post->image,
                'content' => $post->content,
            ]);
        } else {

            return responseJson(404, 'not found');
        }
    }



    public function myPosts(Request $request)
    {
        $posts = $request->user()->Posts()->latest()->paginate(10);

        return responseJson(200, 'Favourite', $posts);
    }



    public function postFavourite(Request $request)
    {
        // Log::create(['content' => $request->all(), 'service' => 'post toggle favourite']);

        $validator = validator()->make($request->all(), [
            'post_id' => 'required|exists:posts,id',
        ]);

        if ($validator->fails()) {
            return responseJson(401, $validator->errors()->first(), $validator->errors());
        }

        $toggle = $request->user()->Posts()->toggle($request->post_id);

        return responseJson(200, 'post is favourite successful', $toggle);
    }



    public function categories()
    {
        $categories = Category::get();
        return responseJson(200, 'This are all categories', $categories);
    }



    public function BloodTypes()
    {
        $bloodTypes = BloodType::all();

        return responseJson(200, 'This are all blood types', $bloodTypes);
    }



    public function contacts(Request $request)
    {
        // Log::create(['content' => $request->all(), 'service' => 'post toggle favourite']);

        $validator = validator()->make($request->all(), [
            'supject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(401, $validator->errors()->first(), $validator->errors());
        }

        $contacts = $request->user()->Contents()->create($request->all());


        return responseJson(200, 'This are all contacts', $contacts);
    }



    // notifications controllers

    public function notificationsCount(Request $request)
    {
        return responseJson(200, 'notifications', [
            'notifications_content' => $request->user()->Notifications()->count()
        ]);
    }



    public function notifications(Request $request)
    {
        $notifications = $request->user()->Notifications()->latest()->paginate(10);

        return responseJson(200, 'Notifications', $notifications);
    }



    public function notificationSettings(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'blood_type_id' => 'required|exists:blood_types,id',
            'governorate_id' => 'required|exists:governorates,id',
        ]);

        if ($validator->fails()) {
            return responseJson(401, $validator->errors()->first(), $validator->errors());
        }

            $sync1 = $request->user()->Governorates()->sync($request->governorate_id);

            $sync2 = $request->user()->blood_Types()->sync($request->blood_type_id);

            dd($sync1);
        // return responseJson(200, 'update success',[$sync1,$sync2]);
    }



    public function testNotification(Request $request)
    {
        $tokens = $request->ids;
        $title = $request->title;
        $body = $request->body;
        $data = Notification::first();
        $send = notifyByFirebase($title, $body, $tokens, $data, true);
        info("firebase result: " . $send);

        return responseJson(
            1,
            'تم الارسال بنجاح',
            json_decode($send)
        );
    }



    //end notification


    public function settings()
    {
        $setting = Setting::get();

        return responseJson(200, 'Settings', $setting);
    }



    //donation request controllers



    public function donationRequestCreate(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'patient_name' => 'required',
            'patient_age' => 'required|integer',
            'blood_type_id' => 'required|exists:blood_types,id',
            'bags_num' => 'required:digits',
            'hospital_name' => 'required',
            'hospital_address' => 'required',
            'city_id' => 'required|exists:cities,id',
            'patient_phone' => 'required|digits:11',

        ]);

        if ($validator->fails()) {
            return responseJson(401, $validator->errors()->first(), $validator->errors());
        }

        // create donation request

        $donationRequest = $request->user()->Donation_Requests()->create($request->all());


        // find clients where choses same governorate for patient

        $clientsIds = $donationRequest->city->governorate
            ->Clients()->whereHas('blood_Types', function ($q) use ($request) {      //
                $q->where('blood_Types.id', $request->blood_Types_id);               //
            })->pluck('Clients.id')->toArray();                                      //

        $send = " ";
        if (count($clientsIds)) {
            $notification = $donationRequest->Notifications()->create([
                'title' => 'احتاج متبرع للفصيلة ',
                'content' => $donationRequest->blood_Type->name . 'محتاج مترع للفصيلة ',
            ]);

            $notification->Clients()->attach($clientsIds);


            $tokens = Token::whereIn('client_id', $clientsIds)->where('token', '!=', null)->pluck('token')->toArray();
            if (count($tokens)) {

                $title = $notification->title;
                $body = $notification->content;
                $data = [

                    'donation_request_id' => $donationRequest->id
                ];


                $send = notifyByFirebase($title, $body, $tokens, $data);
            }
        }

        return responseJson(200, 'تم الاضافة بنجاح', $donationRequest);
    }



    public function donationRequest(Request $request)
    {
        $donation = DonationRequest::with('City', 'Client')->find($request->donation_id);

        if (!$donation) {
            return responseJson(404, 'not donation found');
        }

        return responseJson(200, 'success', $donation);
    }


    public function donationRequests()
    {
        $donations = DonationRequest::latest()->paginate(10);

        return responseJson(200, 'Donation Requests', $donations);
    }
}
