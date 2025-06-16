<?php

namespace App\Actions\DataMigration\Update;

use App\Models\User;

class FindUserByName
{
    public function execute($name)
    {
        $name = $this->removeDegree($name);
        $name = trim($name, " .");
        $searchName = str_replace(" ", "%", $name);

        $users = User::query()
            ->where('name', 'like', '%' . $searchName . '%')
            ->get();

        if ($users->count() == 1) {
            return $users->first();
        }

        if ($users->count() == 0) {
            $name = $this->takeOnlyFirstLastName($name);
            $searchName = str_replace(" ", "%", $name);
            $users = User::query()
                ->where('name', 'like', '%' . $searchName . '%')
                ->get();
        }

        if ($users->count() == 1) {
            return $users->first();
        }

        return null;
    }

    protected function removeDegree($name)
    {
        $degree_regex = "/\b(?:Dr|Prof|PhD|MD|Esq)\b/i";
        return preg_replace($degree_regex, '', $name);
    }

    protected function takeOnlyFirstLastName($name)
    {
        $arrName = explode(" ", $name);
        return $arrName[0] . " " . $arrName[count($arrName) - 1];
    }
}
