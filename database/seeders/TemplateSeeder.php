<?php

namespace Database\Seeders;

use App\Models\Notifable;
use App\Models\NotifableLog;
use App\Models\Template;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class TemplateSeeder extends Seeder
{
    protected $arrTemplate = [
        [
            "category" => "proposal-submit",
            "template" => "Your proposal has been successfully submited. Check <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "proposal-need-to-review",
            "template" => "Has proposal need to review. Check <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "proposal-reviewed",
            "template" => "<span class='fw-bold'>{{\$reviewer}}</span> has reviewed your proposal: '{{\$title}}'. Check your status <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["reviewer", "title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "proposal-reviewed-submit",
            "template" => "<span class='fw-bold'>Your review</span> has submited: '{{\$title}}'. Check your status <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["reviewer", "title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "proposal-status-updated",
            "template" => "Your proposal (<span class='fw-bold'>{{\$title}}</span>) has been <span class='fw-bold'>{{\$status}}</span>. Check your status <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "status", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "report-submit",
            "template" => "Your Report has been successfully submitted. Check <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "report-need-to-review",
            "template" => "Has report need to review. Check <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "report-reviewed",
            "template" => "<span class='fw-bold'>{{\$reviewer}}</span> has reviewed your submission (<span class='fw-bold'>{{\$title}}</span>). Check your status <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["reviewer", "title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "report-reviewed-submit",
            "template" => "<span class='fw-bold'>Your Review</span> has submited (<span class='fw-bold'>{{\$title}}</span>). Check <span class='fw-bold'>here</span>",
            "options" => [
                "data_key" => ["reviewer", "title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "report-status-updated",
            "template" => "Your submission (<span class='fw-bold'>{{\$title}}</span>) has been <span class='fw-bold'>{{\$status}}</span>. Check your status <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "status", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "kpi-submit",
            "template" => "Your KPI has been successfully submitted. Check <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "kpi-need-to-review",
            "template" => "Has KPI need to review. Check <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "kpi-reviewed",
            "template" => "<span class='fw-bold'>{{\$reviewer}}</span> has reviewed your submission (<span class='fw-bold'>{{\$title}}</span>). Check your status <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["reviewer", "title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "kpi-reviewed-submit",
            "template" => "<span class='fw-bold'>Your Review</span> has submited (<span class='fw-bold'>{{\$title}}</span>). Check <span class='fw-bold'>here</span>",
            "options" => [
                "data_key" => ["reviewer", "title", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "kpi-status-updated",
            "template" => "Your submission (<span class='fw-bold'>{{\$title}}</span>) has been <span class='fw-bold'>{{\$status}}</span>. Check your status <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "status", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "new-documentation",
            "template" => "Has new documentation: (<span class='fw-bold'>{{\$title}}</span>). Check <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "status", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "proposal-division-info",
            "template" => "Proposal: (<span class='fw-bold'>{{\$title}}</span>) has been <span class='fw-bold'>{{\$status}}</span>. Check status <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "status", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "report-division-info",
            "template" => "Report: (<span class='fw-bold'>{{\$title}}</span>) has been <span class='fw-bold'>{{\$status}}</span>. Check status <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "status", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "kpi-division-info",
            "template" => "KPI: (<span class='fw-bold'>{{\$title}}</span>) has been <span class='fw-bold'>{{\$status}}</span>. Check status <a class='fw-bold' href='{{\$link}}'>here</a>",
            "options" => [
                "data_key" => ["title", "status", "link"]
            ],
            "status" => 1
        ],
        [
            "category" => "reminder",
            "template" => "I hope this message finds you well.
                As a gentle reminder, for submitting the
                <strong>{{\$reportCategory}}</strong>
                for <strong>{{\$referenceTitle}}</strong>.
                Kindly ensure that all relevant information is included
                and the report follows the provided guidelines.
                Should you encounter any unforeseen challenges
                or anticipate a delay, please communicate with us promptly.
                Your timely submission is crucial for the smooth
                functioning of our operations, and we appreciate
                your dedication to meeting deadlines.
                Thank you for your attention to this matter.
                <br /><br />
                {!!\$notes!!}
                ",
            "options" => [
                "data_key" => ["reportCategory", "referenceTitle", "notes"]
            ],
            "status" => 1
        ],

    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Template::truncate();
        // Notifable::truncate();
        // NotifableLog::truncate();

        foreach ($this->arrTemplate as $template) {
            Template::create([
                "category" => $template["category"],
                "template" => $template["template"],
                "options" => $template["options"],
                "status" => $template["status"]
            ]);
        }
    }
}
