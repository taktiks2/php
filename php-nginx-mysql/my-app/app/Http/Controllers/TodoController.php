<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    /**
     * Todo一覧を表示
     */
    public function index()
    {
        // phpactorでの自動補完が効かない問題
        // https://github.com/phpactor/phpactor/issues/807
        $todos = Todo::orderBy('created_at', 'desc')->take(10)->get();

        return Inertia::render('Index', [
            'todos' => $todos
        ]);
    }

    /**
     * Todo作成フォームを表示
     */
    public function new()
    {
        return Inertia::render('New');
    }

    /**
     * 新しいTodoを保存
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        Todo::create($validated);

        return redirect()->route('todos.index');
    }

    /**
     * Todoを更新
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        $todo->update($validated);

        return redirect()->route('todos.index');
    }

    /**
     * Todoを削除
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect()->route('todos.index');
    }
}
