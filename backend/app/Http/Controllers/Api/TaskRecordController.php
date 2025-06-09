<?php

namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskRecord;
class TaskRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = TaskRecord::all();
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_name' => 'required|string',
            'task_description' => 'required|string',
            'date' => 'required|date',
            'hours_spent' => 'required|numeric|min:0.1',
            'hourly_rate' => 'required|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
        ]);
        $task = TaskRecord::create($data);
        $this->calculateProratedRemuneration($task->task_description, $task->date);

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $task = TaskRecord::findOrFail($id);
        return response()->json($task);
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
         $task = TaskRecord::findOrFail($id);

        $data = $request->validate([
            'employee_name' => 'sometimes|required|string',
            'task_description' => 'sometimes|required|string',
            'date' => 'sometimes|required|date',
            'hours_spent' => 'sometimes|required|numeric|min:0.1',
            'hourly_rate' => 'sometimes|required|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
        ]);

        $task->update($data);
        $this->calculateProratedRemuneration($task->task_description, $task->date);

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = TaskRecord::findOrFail($id);
        $taskDescription = $task->task_description;
        $taskDate = $task->date;
        $task->delete();

        // Rehitung remunerasi setelah hapus
        $this->calculateProratedRemuneration($taskDescription, $taskDate);

        return response()->json(null, 204);
    }
    private function calculateProratedRemuneration($taskDescription, $date)
    {
        $tasks = TaskRecord::where('task_description', $taskDescription)
            ->where('date', $date)
            ->get();

        $totalHours = $tasks->sum('hours_spent');

        if ($totalHours == 0) {
            return;
        }

        // Total biaya untuk seluruh pekerjaan pada task dan tanggal tersebut
        $totalCost = $tasks->reduce(function ($carry, $item) {
            return $carry + ($item->hours_spent * $item->hourly_rate) + $item->additional_charges;
        }, 0);

        // Update total_remuneration untuk setiap record secara prorata
        foreach ($tasks as $task) {
            $prorated = ($task->hours_spent / $totalHours) * $totalCost;
            $task->total_remuneration = round($prorated, 2);
            $task->save();
        }
    }
}
