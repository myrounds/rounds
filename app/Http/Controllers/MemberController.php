<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Validator;

class MemberController extends Controller
{
    public function login()
    {
        $users = Member::where('email', request('email'))->get();
        $authed = null;
        foreach ($users as $user) {
            if (password_verify(request('password'), $user->password)) {
                $authed = $user;
                break;
            }
        }
        if ($authed !== null) {
            $response = $authed->toArray();
            $response['token'] =  $authed->createToken('Rounds')->accessToken;
            return response()->json(['data' => $response], 200);
        }
        else{
            return response()->json(['message' => 'Login unsuccessful'], 401);
        }
    }

    public function password(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'account_id' => 'required|integer',
            'email' => 'required|email',
            'old_password' => 'required',
            'new_password' => 'required',
            'repeat_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $accountId = request('account_id');
        $email = request('email');
        $oldPassword = request('old_password');
        $newPassword = request('new_password');

        $accounts = Member::where('account_id', $accountId)
            ->where('email', $email)
            ->get();

        $authed = null;
        foreach ($accounts as $account) {
            if (password_verify($oldPassword, $account->password)) {
                $authed = $account;
                break;
            }
        }
        if ($authed) {
            $authed->update([ "password" => bcrypt($newPassword) ]);
            $response['token'] =  $authed->createToken('Rounds')->accessToken;
            return response()->json(['data' => $response], 200);
        }
        else{
            return response()->json(['message' => 'Old password incorrect for email'], 401);
        }
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        // validate input
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'email',
            'phone' => 'numeric',
            'license_plate' => '',
            'notes' => '',
            'lat' => 'numeric',
            'lon' => 'numeric',
            'password' => 'required',
            'repeat_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        // check for existing member
        $email = request('email');
        $phone = request('phone');

        $existing = Member::where('account_id', $user['account_id'] || $user['id'])
            ->where(function($q) use ($email, $phone) {
                $q->where('email', $email)->orWhere('phone', $phone);
            })
            ->get()->toArray();
        if (!empty($existing)) {
            return response()->json(['message' => 'Phone number or email address are already in use.'], 401);
        }

        // store member
        $userType = $user->getType();
        $userData = [ $userType.'_id' => $user['id'] ];
        if ($userType !== 'account' && $user['account_id']) {
            $userData['account_id'] = $user['account_id'];
        }

        $input['password'] = bcrypt($input['password']);

        $member = Member::create(array_merge($input, $userData));
        if ($member) {
            return response()->json(['data' => $member->toArray()], 200);
        }
        else {
            return response()->json(['message' => 'Member could not be saved'], 401);
        }
    }

    public function find(Request $request, $id)
    {
        $user = Auth::user();
        $member = Member::find($id);
        if (!$user->hasAccessToModel($member)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }

        return response()->json(['data' => $member], 200);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $member = Member::find($id);
        if (!$user->hasAccessToModel($member)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => '',
            'email' => 'email',
            'phone' => 'numeric',
            'license_plate' => '',
            'notes' => '',
            'lat' => '',
            'lon' => '',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        if ($member->update($input)) {
            return response()->json(['data' => array_merge($member->toArray(), $input)], 200);
        }
        else {
            return response()->json(['message' => 'member could not be updated'], 401);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = Auth::user();
        $member = Member::find($id);
        if (!$user->hasAccessToModel($member)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }
        if ($member->delete()) {
            return response()->json(['message' => 'Member was deleted'], 200);
        }
        else {
            return response()->json(['message' => 'Member could not be deleted'], 401);
        }
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        if ($user->getType() !== 'account') {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }

        $name = $request->get('name');
        $members = Member::where('account_id', '=', $user->id);
        if ($name) {
            $members->whereRaw("concat(first_name, last_name) LIKE '$name%'");
        }
        $result = $members->get()->toArray();
        if ($result) {
            return response()->json(['data' => $result], 200);
        }
        else {
            return response()->json(['data' => []], 200);
        }
    }
}
