<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Validator;
use Socialite;

class AccountController extends Controller
{
    public function login()
    {
        $provider = request('provider');
        $email = request('email');
        $external = null;
        if ($provider) {
            $external = Socialite::driver($provider)->userFromToken(request('token'));
            if ($external) {
                $email = $external->email;
            }
        }

        $users = Account::where('category_id', request('category_id'))
            ->where('email', $email)
            ->get();
        $authed = null;
        foreach ($users as $user) {
            if ($external || password_verify(request('password'), $user->password)) {
                $authed = $user;
                break;
            }
        }
        if ($authed) {
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
            'category_id' => 'required|integer',
            'email' => 'required|email',
            'old_password' => 'required',
            'new_password' => 'required',
            'repeat_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $categoryId = request('category_id');
        $email = request('email');
        $oldPassword = request('old_password');
        $newPassword = request('new_password');

        $accounts = Account::where('category_id', $categoryId)
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
        $input = $request->all();

        // validate input
        $validator = Validator::make($input, [
            'category_id' => 'required|integer',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'password' => 'required',
            'repeat_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // check for existing account
        $categoryId = request('category_id');
        $email = request('email');
        $phone = request('phone');

        $existing = Account::where('category_id', $categoryId)
            ->where(function($q) use ($email, $phone) {
                $q->where('email', $email)->orWhere('phone', $phone);
            })
            ->get()->toArray();
        if (!empty($existing)) {
            return response()->json(['message' => 'Phone number or email address are already in use.'], 401);
        }

        // store account
        $input['password'] = bcrypt($input['password']);
        $user = Account::create($input);

        // return token
        $response['token'] =  $user->createToken('Rounds')->accessToken;
        $response['name'] =  $user->name;

        return response()->json(['data' => $response], 200);
    }

    public function find(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->getType() === 'account' && $user->id == $id) {
            return response()->json(['data' => $user], 200);
        }
        else {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->getType() === 'account' && $user->id == $id) {

            $input = $request->all();

            $validator = Validator::make($input, [
                'category_id' => 'integer',
                'name' => '',
                'email' => 'email',
                'password' => '',
                'repeat_password' => 'same:password',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 401);
            }

            $success = Account::where('id', $id)->update($input);
            if ($success) {
                return response()->json(['data' => array_merge($user->toArray(), $input)], 200);
            }
            else {
                return response()->json(['message' => 'Account could not be updated'], 401);
            }
        }
        else {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->getType() === 'account' && $user->id == $id) {

            $success = Account::where('id', $id)->delete();
            if ($success) {
                return response()->json(['message' => 'Account was deleted'], 200);
            }
            else {
                return response()->json(['message' => 'Error saving changes'], 401);
            }

        }
        else {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }
    }

}