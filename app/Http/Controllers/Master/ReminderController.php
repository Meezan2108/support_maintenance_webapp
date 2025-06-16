<?php

namespace App\Http\Controllers\Master;

use App\Actions\Administrator\Reminder\GetReminderDatatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\ReminderRequest;
use App\Http\Requests\Administrator\ReminderSearchRequest;
use App\Http\Resources\Administrator\ReminderResource;
use App\Http\Resources\Administrator\ReminderTableResource;
use App\Jobs\CreateReminderSendLog;
use App\Models\RefReminderCategory;
use App\Models\Reminder;
use App\Models\User;
use App\Policies\ReminderPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReminderController extends Controller
{

    protected $policy;

    public function __construct(ReminderPolicy $policy)
    {
        $this->policy = $policy;
    }

    public function index(GetReminderDatatables $datatables, ReminderSearchRequest $request)
    {
        $user = Auth::user();
        if (!$this->policy->viewAny($user)) {
            abort(403);
        }

        $filters = $request->validated();
        $list = $datatables->execute($filters);

        $request->session()->put('filters', $filters);

        return Inertia::render('Master/Reminder/Index', [
            "title" => "List Reminder",
            "additional" => [
                "list" => ReminderTableResource::collection($list),
                "filters" => $filters,
                "columns" => $datatables->getColumns(),
                "canCreate" => $this->policy->create($user),
                "urlCreate" => route("panel.reminder.create"),
                "urlIndex" => route("panel.reminder.index")
            ]

        ]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        if (!$this->policy->create($user)) {
            abort(403);
        }

        $arrCategory = RefReminderCategory::all();

        $filters = $request->session()->get('filters');
        return Inertia::render('Master/Reminder/Create', [
            "title" => "Add Reminder",
            "additional" => [
                "filters" => $filters,

                "arrCategory" => $arrCategory,
                "arrRepeatType" => array_values(Reminder::ARR_REPEAT_TYPE),

                "urlStore" => route("panel.reminder.store"),
                "urlIndex" => route("panel.reminder.index")
            ]
        ]);
    }

    public function store(ReminderRequest $request)
    {
        $user = Auth::user();
        if (!$this->policy->create($user)) {
            abort(403);
        }

        DB::transaction(function () use ($request) {
            $arrData = $request->validated();

            if (isset($arrData["is_now"]) && $arrData["is_now"] == 1) {
                $arrData["scheduled_at"] = now();
            }

            if (isset($arrData["is_now"]) && $arrData["is_now"] == 0 && $arrData["scheduled_at"] ?? false) {
                list($date, $time) = explode("T", $arrData["scheduled_at"]);

                $formatedDate = $date . " " . $time . ":00";
                $arrData["scheduled_at"] = $formatedDate;
            }

            $arrData["status"] = 1;

            $reminder = Reminder::create($arrData);

            if ($arrData["is_manual"]) {
                $delay = $arrData["is_now"]
                    ? Carbon::now()->addSecond(0)
                    : Carbon::now()->diffAsCarbonInterval($reminder->scheduled_at);

                CreateReminderSendLog::dispatch($reminder->id)
                    ->delay($delay);
            }
        });

        $filters = $request->session()->get('filters');
        return redirect()->route("panel.reminder.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Create Reminder Success!"
            ]);
    }

    public function show(Request $request, Reminder $reminder)
    {
        /**
         * @var User
         */
        $user = Auth::user();
        if (!$this->policy->view($user, $reminder)) {
            abort(403);
        }

        $filters = $request->session()->get('filters');
        return Inertia::render('Master/Reminder/Show', [
            "title" => "Detail Reminder",
            "additional" => [
                "data" => (new ReminderResource($reminder->load("category")))->toArray($request),
                "filters" => $filters,
                "arrRepeatType" => array_values(Reminder::ARR_REPEAT_TYPE),
                "urlIndex" => route("panel.reminder.index"),
            ]
        ]);
    }

    public function edit(Request $request, Reminder $reminder)
    {
        /**
         * @var User
         */
        $user = Auth::user();
        if (!$this->policy->update($user, $reminder)) {
            abort(403);
        }

        $arrCategory = RefReminderCategory::all();

        $filters = $request->session()->get('filters');
        return Inertia::render('Master/Reminder/Edit', [
            "title" => "Edit Reminder",
            "additional" => [
                "data" => $reminder,
                "filters" => $filters,

                "arrCategory" => $arrCategory,
                "arrRepeatType" => array_values(Reminder::ARR_REPEAT_TYPE),

                "urlUpdate" => route("panel.reminder.update", $reminder),
                "urlIndex" => route("panel.reminder.index"),
            ]
        ]);
    }

    public function update(ReminderRequest $request, Reminder $reminder)
    {
        /**
         * @var User
         */
        $user = Auth::user();
        if (!$this->policy->update($user, $reminder)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $reminder) {
            $arrData = $request->validated();

            if (isset($arrData["is_now"]) && $arrData["is_now"] == 1) {
                $arrData["scheduled_at"] = now();
            }

            if (
                isset($arrData["is_now"]) && $arrData["is_now"] == 0
                && $arrData["scheduled_at"] ?? false
            ) {
                list($date, $time) = explode("T", $arrData["scheduled_at"]);

                $formatedDate = $date . " " . $time . ":00";
                $arrData["scheduled_at"] = $formatedDate;
            }

            $arrData["updated_by"] = Auth::id();

            $reminder->update($arrData);
        });

        $filters = $request->session()->get('filters');
        return redirect()->route("panel.reminder.index", $filters)
            ->with("message", [
                "status" => "success",
                "message" => "Update Reminder Success!"
            ]);
    }

    public function destroy(Reminder $reminder)
    {
        $user = Auth::user();
        if (!$this->policy->delete($user, $reminder)) {
            abort(403);
        }

        DB::transaction(function () use ($reminder) {
            $reminder->deleted_by = Auth::id();
            $reminder->save();
            $reminder->delete();
        });

        return redirect()->back()
            ->with("message", [
                "status" => "success",
                "message" => "Delete Reminder Success!"
            ]);
    }
}
