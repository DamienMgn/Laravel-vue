<?php

namespace App\Http\Controllers;

use App\Cards;
use App\Categories;
use App\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    /**
     * Return card
     */
    public function addTask(Request $request, Cards $card) {

        $validatedData = $request->validate([
            'taskName' => 'required|max:100',
            'taskColor' => 'required|min:7|max:7'
        ]);

        $task = new Tasks;

        $task->name = $request->taskName;

        $task->card_id = $card->id;

        $task->status = false;

        $task->color = $request->taskColor;

        $task->save();

        $card['tasks'] = Tasks::where('card_id', $card->id)->orderBy('order', 'asc')->get();

        return response()->json([
            'card' => $card,
        ]);
    }

    /**
    * Return card
    */
    public function deleteTask(Tasks $task) {

        $card = $task->card;

        $taskToDelete =  Tasks::find($task->id);

        $taskToDelete->delete();

        $card['tasks'] = Tasks::where('card_id', $card->id)->orderBy('order', 'asc')->get();

        return response()->json([
            'card' => $card,
        ]);
    }

    /**
     * Return card
     */
    public function updateTasksOrder(Request $request, Cards $card) {

        $requestTasks = $request->tasks;

        foreach ($requestTasks as $newTask) {
                $task = Tasks::find($newTask['id']);
                $task->order = $newTask['order'];
                $task->save();
            }

        $card['tasks'] = Tasks::where('card_id', $card->id)->orderBy('order', 'asc')->get();

        return response()->json([
            'card' => $card,
        ]);
    }

    /**
     * Return card
     */
    public function updateTaskCard(Request $request, Tasks $task) {

        $oldCard = Cards::find($task->card_id);
        $newCard = Cards::find($request->card);

        $task->card_id = $request->card;

        $task->save();

        $oldCard['tasks'] = Tasks::where('card_id', $oldCard->id)->orderBy('order', 'asc')->get();
        $newCard['tasks'] = Tasks::where('card_id', $newCard->id)->orderBy('order', 'asc')->get();

        return response()->json([
            'oldCard' => $oldCard,
            'newCard' => $newCard
        ]);
    }

    /**
     * Return card
     */
    public function updateTask(Tasks $task, Request $request) {

        $card = $task->card;

        $validatedData = $request->validate([
            'taskName' => 'nullable|max:100',
            'taskColor' => 'nullable|max:7|min:7',
            'status' => 'nullable|boolean'
        ]);

        if (isset($request->status)) {
            $task->status = $request->status;
        }

        if (!empty($request->taskColor)) {
            $task->color = $request->taskColor;
        }

        if (!empty($request->taskName)) {
            $task->name = $request->taskName;
        }
        if (!empty($request->taskDeadline)) {
            $newDate = new \DateTime($request->taskDeadline);
            $task->deadline = $newDate;
        }

        $task->save();

        $card['tasks'] = Tasks::where('card_id', $card->id)->orderBy('order', 'asc')->get();

        return response()->json([
            'card' => $card,
        ]);
    }

    /**
     * Return tasks
     */
    public function loadTasks(Request $request) {

        $userId = $request->user()->id;

        $today  = date("Y/m/d");
        $tomorrow = new \DateTime('tomorrow');

        $allTasks = [];

        $allTasks['today'] = $this->getTasksFromDate($request->user()->id, $today, '=');
        $allTasks['past'] = $this->getTasksFromDate($request->user()->id, $today, '<');
        $allTasks['tomorrow'] = $this->getTasksFromDate($request->user()->id, $tomorrow, '=');
        $allTasks['next'] = $this->getTasksFromDate($request->user()->id, $tomorrow, '>');


        return response()->json([
            'tasks' => $allTasks,
        ]);

    }

    private function getTasksFromDate($userId, $date, $operator) {
        $tasks = DB::table('categories')
            ->where('user_id', $userId)
            ->join('cards', 'categories.id', '=', 'cards.category_id')
                 ->join('tasks', 'cards.id', '=', 'tasks.card_id')
                 ->where('tasks.deadline', $operator, $date)
                 ->where('tasks.status', 0)
                 ->select('cards.*', 'tasks.*')
                 ->get();

        return $tasks;
    }
}
