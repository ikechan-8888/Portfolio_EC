<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\tel;
use App\Rules\zip;

class DeliveryController extends Controller
{
    public function delivery(Request $request, Delivery $delivery){
        $this->validator($request->all());

        $loginId = null;
        $data = [
            'delivery_name' => $request['name'],
            'postal_code' => $request['code'],
            'delivery_address' => $request['address'],
            'tel' => $request['tel']
        ];
        if(Auth::check()){
            $loginId = Auth::user()->login_id;
            $data['use'] = 0;
        }else{
            $loginId = session('_token');
            $data['use'] = 1;
        }
        $data['login_id'] = $loginId;

        $record = $delivery::create(
            $data
        );

        return $this->jsonResponse($record);
    }

    public function getDeliveryList(Delivery $delivery){
        $loginId = null;
        $getData = [];
        if(Auth::check()){
            $loginId = Auth::user()->login_id;
            $getData = $delivery->where("login_id", '=', $loginId)->get();
        }
        return $this->jsonResponse($getData);
    }

    public function getActuallyDelivery(Delivery $delivery){
        $loginId = null;
        if(Auth::check()){
            $loginId = Auth::user()->login_id;
        }else{
            $loginId = session('_token');
        }
        $getData = $delivery->where([["login_id", $loginId],["use", 1]])->get();

        return $this->jsonResponse($getData);
    }

    public function deliveryChange(Request $request, Delivery $delivery){
        $getData = [];
        if(Auth::check()){
            $loginId = Auth::user()->login_id;
            $delivery->where("login_id", "=", $loginId)->update(["use" => 0]);
            $getData = $delivery->where([["id", $request['id']], ["login_id", $loginId]])->update(["use" => 1]);
        }
        return $this->jsonResponse($getData);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rulus = [
            'name' => ['required', 'string', 'max:256'],
            'code' => ['required', 'max:8', 'min:8', new zip],
            'address' => ['required', 'string', 'max:256'],
            'tel' => ['required', 'min:10', 'max:11', new tel],
        ];

        $validator = Validator::make($data, $rulus)->validate();

        // if ($validator->fails()) {
        //     return redirect('/')
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        return $validator;
    }
}
