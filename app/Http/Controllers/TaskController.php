<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Validator;

class TaskController extends Controller
{
    public function find(Request $request, $id)
    {
        $user = Auth::user();
        $group = Task::with('tasks')->find($id);
        if (!$user->hasAccessToModel($group)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }
        return response()->json(['data' => $group], 200);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        if (isset($input['completed_at'])) {
            unset($input['completed_at']);
        }

        $validator = Validator::make($input, [
            'member_id' => 'integer',
            'name' => 'required',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'time' => 'numeric',
            'address' => '',
            'repeat' => 'in:never,weekly,monthly,yearly',
            'lat' => 'numeric',
            'lon' => 'numeric',
            'email' => 'email',
            'phone' => 'numeric',
            'notes' => ''
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $userType = $user->getType();
        $userData = [ $userType.'_id' => $user['id'] ];
        if ($userType !== 'account' && $user['account_id']) {
            $userData['account_id'] = $user['account_id'];
        }

        $group = Task::create(array_merge($input, $userData));

        return response()->json(['data' => $group->toArray()], 200);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $group = Task::find($id);

        if (!$user->hasAccessToModel($group)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }

        $input = $request->all();

        if (isset($input['account_id'])) {
            unset($input['account_id']);
        }

        $validator = Validator::make($input, [
            'member_id' => 'integer',
            'name' => '',
            'day' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'time' => 'numeric',
            'repeat' => 'in:never,weekly,monthly,yearly',
            'address' => '',
            'lat' => 'numeric',
            'lon' => 'numeric',
            'email' => 'email',
            'phone' => 'numeric',
            'notes' => '',
            'completed_at' => 'datetime'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        if ($group->update($input)) {
            return response()->json(['data' => array_merge($group->toArray(), $input)], 200);
        }
        else {
            return response()->json(['message' => 'Group could not be updated'], 401);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = Auth::user();
        $group = Task::find($id);

        if (!$user->hasAccessToModel($group)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }

        if ($group->delete()) {
            return response()->json(['message' => 'Group was deleted'], 200);
        }
        else {
            return response()->json(['message' => 'Group could not be deleted'], 401);
        }
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $sDay = $request->get('s_day');
        $eDay = $request->get('e_day');
        $sTime = $request->get('s_time');
        $eTime = $request->get('e_time');

        $sIndex = array_search($sDay, $daysOfWeek);
        $eIndex = array_search($eDay, $daysOfWeek);
        if ($sIndex === false) {
            $sIndex = 0;
        }
        if ($eIndex === false) {
            $eIndex = count($daysOfWeek) - 1;
        }
        $days = array_slice($daysOfWeek, $sIndex, $eIndex - $sIndex + 1);

        $groups = Task::with("items")
            ->where($user->getType().'_id', '=', $user->id)
            ->whereIn('day', $days);

        if ($sTime && $eTime) {
            $groups->whereRaw("CASE WHEN day = '$sDay' THEN time > '$sTime' WHEN day = '$eDay' THEN time < '$eTime' ELSE TRUE END");
        }
        elseif ($sTime) {
            $groups->whereRaw("CASE WHEN day = '$sDay' THEN time > '$sTime' ELSE TRUE END");
        }
        elseif ($eTime) {
            $groups->whereRaw("CASE WHEN day = '$eDay' THEN time < '$eTime' ELSE TRUE END");
        }

        $groups->orderBy('day', 'asc')->orderBy('time', 'asc');

        $result = $groups->get()->toArray();

        return response()->json(['data' => $result], 200);
    }
}
