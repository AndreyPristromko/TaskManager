<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:todo,in_progress,done',
            'deadline' => 'nullable|date|date_format:Y-m-d'
        ]);
        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    public function show(string $id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string', 
            'status' => 'sometimes|required|in:todo,in_progress,done',
            'deadline' => 'nullable|date|date_format:Y-m-d'
        ]);
        $task = Task::findOrFail($id);
        $task->update($validated);
        return response()->json($task);
    }

    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->noContent();
    }

    public function updateStatus(Task $task, Request $request)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,done'
        ]);

        $task->status = $request->status;
        $task->save();

        return response()->json($task);
    }
}
