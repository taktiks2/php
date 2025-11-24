import { Link, useForm } from '@inertiajs/react';

interface Todo {
    id: number;
    title: string;
    description: string | null;
    completed: boolean;
    created_at: string;
    updated_at: string;
}

interface Props {
    todos: Todo[];
}

export default function Index({ todos }: Props) {
    const { delete: destroy, patch } = useForm();

    const handleDelete = (id: number) => {
        if (confirm('本当に削除しますか？')) {
            destroy(`/todos/${id}`);
        }
    };

    const handleToggleComplete = (todo: Todo) => {
        patch(`/todos/${todo.id}`, {
            data: {
                title: todo.title,
                description: todo.description,
                completed: !todo.completed,
            },
        });
    };

    return (
        <div>
            <h1>Todo一覧</h1>
            <Link href="/new">新規作成</Link>

            <ul>
                {todos.map((todo) => (
                    <li key={todo.id}>
                        <input
                            type="checkbox"
                            checked={todo.completed}
                            onChange={() => handleToggleComplete(todo)}
                        />
                        <span>{todo.title}</span>
                        {todo.description && <span> - {todo.description}</span>}
                        <button onClick={() => handleDelete(todo.id)}>削除</button>
                    </li>
                ))}
            </ul>
        </div>
    );
}
