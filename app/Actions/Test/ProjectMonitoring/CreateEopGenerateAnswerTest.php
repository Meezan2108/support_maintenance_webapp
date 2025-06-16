<?php

namespace App\Actions\Test\ProjectMonitoring;

use App\Models\RefReportEopBenefitsQuestion;

class CreateEopGenerateAnswerTest
{
    public function execute($proposal)
    {
        $questions = RefReportEopBenefitsQuestion::all();

        $arrAnswers = [];
        foreach ($questions as $question) {
            $keyId = 'q_' . $question->id;

            $answer = null;
            if ($question->type == 'multitext') {
                $answer = [$question->options[0]];
            }

            if ($question->type == 'multi') {
                $answer = [$question->options[0]];
            }

            if ($question->type == 'multivalue') {
                $answer = [
                    [
                        "value" => $question->options["options"][0],
                        "data" => "test"
                    ]
                ];
            }

            if ($question->type == 'multitextvalue') {
                $answer = [
                    [
                        "value" => $question->options["options"][0],
                        "data" => "test"
                    ]
                ];
            }

            if ($question->type == 'multitextvalue2') {
                $answer = [
                    [
                        "value" => $question->options["options"][0],
                        "data" => ["test", "text2"]
                    ]
                ];
            }

            if ($question->type == 'table') {
                $answer = [
                    collect($question->options)->map(function ($item, $index) {
                        return "text " . $index;
                    })->toArray()
                ];
            }

            $arrAnswers[$keyId] = $answer;
        }

        return $arrAnswers;
    }
}
