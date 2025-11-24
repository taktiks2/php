import { Link, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

export default function New() {
    const { data, setData, post, processing, errors } = useForm({
        title: "",
        description: "",
        completed: false,
    });

    const handleSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        post("/todos");
    };

    return (
        <div>
            <h1>Todo作成</h1>
            <Link href="/">一覧に戻る</Link>

            <form onSubmit={handleSubmit}>
                <div>
                    <label>
                        タイトル:
                        <input
                            type="text"
                            value={data.title}
                            onChange={(e) => setData("title", e.target.value)}
                            required
                        />
                    </label>
                    {errors.title && <div>{errors.title}</div>}
                </div>

                <div>
                    <label>
                        説明:
                        <textarea
                            value={data.description}
                            onChange={(e) =>
                                setData("description", e.target.value)
                            }
                        />
                    </label>
                    {errors.description && <div>{errors.description}</div>}
                </div>

                <div>
                    <label>
                        <input
                            type="checkbox"
                            checked={data.completed}
                            onChange={(e) =>
                                setData("completed", e.target.checked)
                            }
                        />
                        完了済み
                    </label>
                </div>

                <button type="submit" disabled={processing}>
                    作成
                </button>
            </form>
        </div>
    );
}
