<?php

namespace App\Http\Controllers;

use App\Actions\MyTask\ClearMyTaskCache;
use App\Actions\MyTask\GetMyTaskDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyTask\MyTaskSearchRequest;
use App\Http\Resources\MyTask\MyTaskTableResource;
use App\Models\Taskable;
use App\Models\User;
use App\Policies\MyTaskPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MyTaskController extends Controller
{
    protected $policy;

    public function __construct(MyTaskPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(GetMyTaskDatatables $datatables, MyTaskSearchRequest $request)
    {
        $user = User::find(Auth::id());
        if (!$this->policy->viewAny($user)) abort(403);

        $filters = $request->validated();
        $data = $datatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('MyTask/Index', [
            "title" => "My Task",
            "additional" => [
                "data" => MyTaskTableResource::collection($data),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "urlIndex" => route("panel.my-task.index")
            ]
        ]);
    }

    public function destory(Request $request, Taskable $task)
    {
        $user = User::find(Auth::id());
        if (
            !($task->code_type == Taskable::TARGET_TYPE_USER
                && $task->model_id == $user->id)
            &&
            !($task->code_type == Taskable::TARGET_TYPE_GROUP
                && in_array($task->model_id, $user->roles->pluck("id")->toArray()))
        ) {
            abort(403);
        }

        $task->delete();
        (new ClearMyTaskCache)->execute($user->id);

        $filters = $request->session()->get('filters');

        return redirect()->route("panel.my-task.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Remove Task Success!"
            ]);
    }
}
