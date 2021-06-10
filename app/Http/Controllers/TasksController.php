<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->get();
        
                return view("tasks.index",['tasks' => $tasks,]);
                
        }else{

            // 認証していない場合はwelcomeへ遷移
            return view('welcome');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::check()) {

            $task = new Task;
            return view('tasks.create', ['task' => $task,]);
        }else{
            return view('welcome');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::check()) {
            $request->validate([
                'content' => 'required',
                'status' => 'required|max:10',
            ]);
            
            $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
            ]);        
            
            return redirect('/');
            
        }else{
            return view('welcome');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (\Auth::check()) {
            $task = Task::findOrFail($id);
            
            if (\Auth::id() === $task->user_id) {
                return view('tasks.show', ['task' => $task,]);
            }else{
                $user = \Auth::user();
                $tasks = $user->tasks()->get();
                return view("tasks.index",['tasks' => $tasks,]);
            }
            
        }else{
            return view('welcome');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
            
            $task = Task::findOrFail($id);
            
            if (\Auth::id() === $task->user_id) {
                return view('tasks.edit', ['task' => $task, ]);
            }else{
                $user = \Auth::user();
                $tasks = $user->tasks()->get();
                return view("tasks.index",['tasks' => $tasks,]);
            }
            
        }else{
            return view('welcome');
        }
        
        
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
        if (\Auth::check()) {
            
            $request->validate([
               'content' => 'required',
               'status' => 'required|max:10',
            ]);
            
    
            $task = Task::findOrFail($id);
            $task->content = $request->content;
            $task->status = $request->status;
            
            if (\Auth::id() === $task->user_id) {
                $task->save();
            }
            
            return redirect('/');
        }else{

            return view('welcome');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::check()) {
            
            $task = Task::findOrFail($id);
            
            if (\Auth::id() === $task->user_id) {
                $task->delete();
            }
            
            return redirect('/');
        }else{
            return view('welcome');
        }
    }
}
