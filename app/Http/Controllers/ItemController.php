<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Validator;


class ItemController extends Controller
{
    public function find(Request $request, $id)
    {
        $user = Auth::user();
        $item = Item::with('task')->find($id);
        if (!$user->hasAccessToModel($item->task)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }

        return response()->json(['data' => $item->toArray()], 200);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        if (isset($input['completed_at'])) {
            unset($input['completed_at']);
        }

        $validator = Validator::make($input, [
            'task_id' => 'required|numeric',
            'name' => 'required',
            'quantity' => 'integer',
            'notes' => ''
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $task = Task::find($input->get('task_id'));
        if (!$user->hasAccessToModel($task)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }

        $item = Item::create($input);

        return response()->json(['data' => $item->toArray()], 200);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $item = Item::with('task')->find($id);
        if (!$user->hasAccessToModel($item->task)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }
        $input = $request->all();

        if (isset($input['task_id'])) {
            unset($input['task_id']);
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

        if ($item->update($input)) {
            return response()->json(['data' => array_merge($item->toArray(), $input)], 200);
        }
        else {
            return response()->json(['message' => 'Item could not be updated'], 401);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = Auth::user();
        $item = Item::with('task')->find($id);
        if (!$user->hasAccessToModel($item->task)) {
            return response()->json(['message' => 'Permission error. Access is denied.'], 401);
        }

        if ($item->delete()) {
            return response()->json(['message' => 'Item was deleted'], 200);
        }
        else {
            return response()->json(['message' => 'Item could not be deleted'], 401);
        }
    }
}
