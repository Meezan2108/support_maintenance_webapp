<?php

namespace App\Helpers;

class DatatablesHelper
{
    public static function getSearchableField($columns)
    {
        $searchField = collect($columns)->filter(function ($item) {
            return $item['searchable'] ?? false;
        })->map(function ($item) {
            return $item['name'];
        })->toArray();

        return $searchField;
    }

    public static function getOrderAbleField($columns)
    {
        $searchField = collect($columns)->filter(function ($item) {
            return $item['orderable'] ?? false;
        })->map(function ($item) {
            return $item['name'];
        })->toArray();

        return $searchField;
    }

    public static function getSelectableField($columns)
    {
        $searchField = collect($columns)->filter(function ($item) {
            return isset($item['searchtype'])
                ? $item['searchtype'] == "select"
                : false;
        })->map(function ($item) {
            return $item['name'];
        })->toArray();

        return $searchField;
    }
}
