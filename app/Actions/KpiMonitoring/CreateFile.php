<?php

namespace App\Actions\KpiMonitoring;

use App\Models\Fileable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateFile
{
    /**
     * Execute the action
     *
     * @param  array  $data
     * @return any
     */
    public function execute(Model $model, array $requestFiles, array $arrData, string $fileCode)
    {
        $arrIdNew = [];

        foreach ($requestFiles as $file) {
            $fileableFormat = Fileable::prepareForDB($file);

            $fileable = $model->fileable()->create(array_merge([
                "code_type" => $fileCode,
                "access_key" => Str::random(64)
            ], $fileableFormat));

            $arrIdNew[] = $fileable->id;
        }

        $arrIdOld = isset($arrData["old_files"])
            ? collect($arrData["old_files"])->pluck('id')->toArray()
            : [];

        $model->fileable()
            ->whereNotIn("id", array_merge($arrIdNew, $arrIdOld))
            ->delete();

        return $model;
    }
}
