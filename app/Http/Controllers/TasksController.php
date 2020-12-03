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
        $data = [];
       
        $tasks = task::all();
        
        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            $task = new Task;
            return view('tasks.create', [
                'task' => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = \Auth::user();
        $request->validate([
            'status' => 'required|max:10',
        ]);
        $request->validate([
            'content' => 'required',
        ]);
        $task = new Task;
        $task->status = $request->status;
        $task->user_id = $user->id;
        $task->content = $request->content;
        $task->save();
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = \Auth::user();
        $task = Task::findOrFail($id);

            return view('tasks.show', [
                'task' => $task,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = \Auth::user();
        $task = Task::findOrFail($id);
        if($user->id == $task->user_id ){
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }else{
            return redirect('/');
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
        $request->validate([
            'status' => 'required|max:10',
        ]);
        $request->validate([
            'content' => 'required',
        ]);
        
        $user = \Auth::user();
        $task = Task::findOrFail($id);
        // メッセージを更新
        if($user->id == $task->user_id ){
            $task->status = $request->status;    // 追加
            $task->content = $request->content;
            $task->save();
        }
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = \Auth::user();
        $task = Task::findOrFail($id);

            // メッセージを削除
            $task->delete();


        // トップページへリダイレクトさせる
        return redirect('/');
    }
}