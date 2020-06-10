<?php

namespace App\Http\Controllers;


use App\User;
use App\Profile;
use App\APIError;
use Illuminate\Http\Request;
use App\ProfileUpdate;
use App\Http\Controllers\Controller;

class ProfileUpdateController extends Controller{


    public function get(Request $request){

        $s = $request->s;
        $page = $request->page;
        $limit = null;

        if ($request->limit && $request->limit > 0) {
            $limit = $request->limit;
        }

        if ($s) {
            if ($limit || $page) {
                return ProfileUpdate::where('old_value', 'like', "%$s%")->orWhere('new_value', 'like', "%$s%")->orWhere('raison', 'like', "%$s%")->paginate($limit);
            } else {
                return ProfileUpdate::where('old_value', 'like', "%$s%")->orWhere('new_value', 'like', "%$s%")->orWhere('raison', 'like', "%$s%")->get();
            }
        } else {
            if ($limit || $page) {
                return ProfileUpdate::paginate($limit);
            } else {
                return ProfileUpdate::all();
            }
        }
    }



    public function find($id){

        $profileUpdate = ProfileUpdate::find($id);
        abort_if($profileUpdate == null, 404, "ProfileUpdate not found.");
        return response()->json($profileUpdate);
    }

    public function delete($id){
        $profileUpdate = ProfileUpdate::find($id);
        abort_if($profileUpdate == null, 404, "ProfileUpdate not found.");
        $profileUpdate->delete();
        return response()->json([]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request->all(), [
            'user_id' => 'required|numeric',
            'profile_id' => 'required|numeric',
            'old_value' => 'present',   // the field must be there, even if it's value is null
            'new_value' => 'present',    // the field must be there, even if it's value is null
            'raison' => 'nullable'
        ]);

        //We find the user
        $user = User::find($request->user_id);
        if ($user == null) {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("USER_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! No user found");
            return response()->json($apiError, 400);
        }

        //we find the profile
        $profile = Profile::find($request->profile_id);
        if ($profile == null) {
            $apiError = new APIError;
            $apiError->setStatus("400");
            $apiError->setCode("PROFILE_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! No profile found");
            return response()->json($apiError, 400);
        }

        // les données de la requête sont valides
        $profileupdate = ProfileUpdate::create([
            'user_id' => $request->user_id,
            'profile_id' => $request->profile_id,
            'old_value' => $request->old_value,
            'new_value' => $request->new_value,
            'is_accepted' => false,
            'raison' => $request->raison,
        ]);
        return response()->json($profileupdate, 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request->all(), [
            'user_id' => 'required|numeric|exists:App\User,id',
            'profile_id' => 'required|numeric|exists:App\Profile,id',
        ]);

        $profileupdate = ProfileUpdate::find($id);

        $datas = $request->only([
            'user_id',
            'profile_id',
            'old_value',
            'new_value',
            'is_accepted',
            'raison',
        ]);

        if ($profileupdate == null) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("PROFILE_UPDATE_NOT_FOUND");
            $apiError->setMessage("Something wrong with your request! This profileUpdate doesn't exist");
            return response()->json($apiError, 400);
        }
        // les données de la requête sont valides
        $profileupdate->update($datas);
        return response()->json($profileupdate, 200);
    }
}
