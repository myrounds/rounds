<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Validator;


class TaskController extends Controller
{
    public function find(Request $request, $id)
    {
        $user = Auth::user();
        $task = Task::with('group')->find($id);
        if (!$user->hasAccessToModel($task->group)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }

        return response()->json(['data' => $task->toArray()], 200);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        if (isset($input['completed_at'])) {
            unset($input['completed_at']);
        }

        $validator = Validator::make($input, [
            'group_id' => 'required|numeric',
            'name' => 'required',
            'quantity' => 'integer',
            'notes' => ''
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $group = Group::find($input->get('group_id'));
        if (!$user->hasAccessToModel($group)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }

        $task = Task::create($input);

        return response()->json(['data' => $task->toArray()], 200);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $task = Task::with('group')->find($id);
        if (!$user->hasAccessToModel($task->group)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }
        $input = $request->all();

        if (isset($input['group_id'])) {
            unset($input['group_id']);
        }

        $validator = Validator::make($input, [
            'name' => '',
            'quantity' => 'integer',
            'notes' => '',
            'completed_at' => 'datetime'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        if ($task->update($input)) {
            return response()->json(['data' => array_merge($task->toArray(), $input)], 200);
        }
        else {
            return response()->json(['message' => 'Task could not be updated'], 401);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = Auth::user();
        $task = Task::with('group')->find($id);
        if (!$user->hasAccessToModel($task->group)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }

        if ($task->delete()) {
            return response()->json(['message' => 'Task was deleted'], 200);
        }
        else {
            return response()->json(['message' => 'Task could not be deleted'], 401);
        }
    }
}
