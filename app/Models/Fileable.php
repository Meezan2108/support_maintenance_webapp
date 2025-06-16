<?php

namespace App\Models;

use App\Models\Concerns\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Fileable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "fileable";

    protected $guarded = ["id", "created_at", "updated_at", "deleted_at"];

    protected $hidden = ["file"];

    protected $appends = ["url"];

    public function referenceTable(): MorphTo
    {
        return $this->morphTo('fileable');
    }

    public static function prepareForDB($requestFile)
    {
        $pictureFile = file_get_contents($requestFile);
        $unpack = '0x' . bin2hex($pictureFile);

        return [
            "file_name" => $requestFile->getClientOriginalName(),
            "file_type" => $requestFile->getClientMimeType(),
            "file_size" => $requestFile->getSize(),
            "file" => DB::raw($unpack)
        ];
    }

    public function getUrlAttribute($value)
    {
        return route('resources.fileable.show', [
            "fileable" => $this->id,
            "access_key" => $this->access_key
        ]);
    }
}
