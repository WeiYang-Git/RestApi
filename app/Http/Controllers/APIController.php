<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\API;

class APIController extends Controller
{
    public function getData(){
    
        return API::all();
    }

    public function getDataValueByKey(Request $req){
        $api = API::select('value')->where('key_value', $req->key)->first();
        
        $timestamp = '';
        if(!empty($req->timestamp)){
            $timestamp = date('g:i a', strtotime($req->timestamp));
        }

        if(!empty($api->value)){
            $response = 'Response: '.$api->value.' '.$timestamp;
        }else{
            $response = 'Unable to find the key.';
        }

        return $response;
    }

    public function addData(Request $req){
        $api = new API;
        
        if (API::where('key_value', '=', $req->key)->exists()) { // Key Found
            $result = API::where('key_value', '=', $req->key)->update(['value' => $req->value]);
        }else{
            $api->key_value = $req->key;
            $api->value = $req->value;
            $result = $api->save();
        }

        if($result){
            return 'Data has been successfully saved.';
        }else{
            return 'Operation add failed.';
        }
    }

    public function updateData(Request $req){
        $api = API::find($req->id);
        
        $api->key_value = $req->key;
        $api->value = $req->value;

        $result = $api->save();

        if($result){
            return 'Data has been successfully updated.';
        }else{
            return 'Operation update failed.';
        }
    }
}