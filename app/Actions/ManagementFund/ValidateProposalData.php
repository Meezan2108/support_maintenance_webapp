<?php

namespace App\Actions\ManagementFund;

use App\Helpers\DateHelper;
use App\Http\Requests\ManagementFund\Form1Request;
use App\Http\Requests\ManagementFund\Form2Request;
use App\Http\Requests\ManagementFund\Form3Request;
use App\Http\Requests\ManagementFund\Form4Request;
use App\Http\Requests\ManagementFund\Form6Request;
use App\Http\Requests\ManagementFund\Form7Request;
use App\Http\Requests\ManagementFund\Form8Request;
use App\Http\Requests\ManagementFund\Form9Request;
use App\Http\Resources\ManagementFund\FormResource;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidateProposalData
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Proposal $proposal)
    {
        $arrData = (new FormResource($proposal))->toArray(new Request());

        $status = true;
        $arrError = [];
        $result = $this->validateForm1($arrData["identification"]);
        $arrError["identification"] = $result["errors"];
        $status = $status && $result["status"];

        $result = $this->validateForm2($arrData["objectives"], $proposal);
        $arrError["objectives"] = $result["errors"];
        $status = $status && $result["status"];

        $result = $this->validateForm3($arrData["research_background"], $proposal);
        $arrError["research_background"] = $result["errors"];
        $status = $status && $result["status"];

        $result = $this->validateForm4($arrData["research_approach"], $proposal);
        $arrError["research_approach"] = $result["errors"];
        $status = $status && $result["status"];

        // form5 skipped no data;

        $result = $this->validateForm6($arrData["benefits"], $proposal);
        $arrError["benefits"] = $result["errors"];
        $status = $status && $result["status"];

        $result = $this->validateForm7($arrData["research_collabration"], $proposal);
        $arrError["research_collabration"] = $result["errors"];
        $status = $status && $result["status"];

        $result = $this->validateForm8($arrData["expenses_estimation"], $proposal);
        $arrError["expenses_estimation"] = $result["errors"];
        $status = $status && $result["status"];

        $result = $this->validateForm9($arrData["expenses_estimation"], $proposal);
        $arrError["expenses_estimation"] = $result["errors"];
        $status = $status && $result["status"];

        return [
            "status" => $status,
            "errors" => $arrError,
            "tabs" => array_keys(array_filter($arrError))
        ];
    }

    public function validateForm1(?array $arrData)
    {
        $arrData["researcher"] = optional($arrData["researcher"])->toArray();

        $formRequest = new Form1Request();

        $formRequest->replace($arrData);

        $status = true;

        try {
            $formRequest->validate($formRequest->rules());
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $status = false;
        }

        return [
            "status" => $status,
            "errors" => $errors ?? []
        ];
    }

    public function validateForm2(?array $arrData, Proposal $proposal)
    {
        $arrData["project_leader_type"] = $proposal->project_leader_type;

        $arrData["objectives"] = optional($arrData["objectives"])->toArray();
        $arrData["for_primary"] = optional($arrData["for_primary"])->toArray();
        $arrData["for_secondary"] = optional($arrData["for_secondary"])->toArray();

        $formRequest = new Form2Request();

        $formRequest->replace($arrData);

        $status = true;

        try {
            $formRequest->validate($formRequest->rules());
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $status = false;
        }

        return [
            "status" => $status,
            "errors" => $errors ?? []
        ];
    }

    public function validateForm3(?array $arrData, Proposal $proposal)
    {
        $arrData["project_leader_type"] = $proposal->project_leader_type;

        $formRequest = new Form3Request();

        $formRequest->replace($arrData);

        $status = true;

        try {
            $formRequest->validate($formRequest->rules());
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $status = false;
        }

        return [
            "status" => $status,
            "errors" => $errors ?? []
        ];
    }

    public function validateForm4(?array $arrData, Proposal $proposal)
    {
        $arrData["project_leader_type"] = $proposal->project_leader_type;
        $arrData["activities"] = optional($arrData["activities"])->toArray();
        $arrData["milestones"] = optional($arrData["milestones"])->toArray();

        $formRequest = new Form4Request();

        $formRequest->replace($arrData);

        $status = true;

        try {
            $formRequest->validate($formRequest->rules());
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $status = false;
        }

        return [
            "status" => $status,
            "errors" => $errors ?? []
        ];
    }

    public function validateForm6(?array $arrData, Proposal $proposal)
    {
        $arrData["project_leader_type"] = $proposal->project_leader_type;
        $arrData["economic_contributions"] = optional($arrData["economic_contributions"])->toArray();
        $arrData["output_expected"] = optional($arrData["output_expected"])->toArray();
        $arrData["human_capital"] = optional($arrData["human_capital"])->toArray();

        $formRequest = new Form6Request();

        $formRequest->replace($arrData);

        $status = true;

        try {
            $formRequest->validate($formRequest->rules());
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $status = false;
        }

        return [
            "status" => $status,
            "errors" => $errors ?? []
        ];
    }

    public function validateForm7(?array $arrData, Proposal $proposal)
    {
        $arrData["project_leader_type"] = $proposal->project_leader_type;

        $formRequest = new Form7Request();

        $formRequest->replace($arrData);

        $status = true;

        try {
            $formRequest->validate($formRequest->rules());
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $status = false;
        }

        return [
            "status" => $status,
            "errors" => $errors ?? []
        ];
    }

    public function validateForm8(?array $arrData, Proposal $proposal)
    {
        $arrData["project_leader_type"] = $proposal->project_leader_type;
        $arrData["years"] = DateHelper::generateArrYear($proposal->schedule_start_date, $proposal->schedule_duration);

        $formRequest = new Form8Request();

        $formRequest->replace($arrData);

        $status = true;

        try {
            $formRequest->validate($formRequest->rules());
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $status = false;
        }

        return [
            "status" => $status,
            "errors" => $errors ?? []
        ];
    }

    public function validateForm9(?array $arrData, Proposal $proposal)
    {
        $newArrData = [];
        $newArrData["years"] = DateHelper::generateArrYear($proposal->schedule_start_date, $proposal->schedule_duration);
        $newArrData["cost_salaried"] = $arrData["V11000"][0] ?? [];

        $formRequest = new Form9Request();

        $formRequest->replace($newArrData);

        $status = true;

        try {
            $formRequest->validate($formRequest->rules());
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $status = false;
        }

        return [
            "status" => $status,
            "errors" => $errors ?? []
        ];
    }
}
