<?php

namespace Database\Seeders;

use App\Helpers\EtlHelper;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    protected $headers = [];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        DB::table("model_has_roles")->truncate();

        echo storage_path("csv/crims-db-v1/userinfo.csv");
        die();  // This will stop the script and print the path


        $hasHeader = true;

        $isHeaderSkiped = false;
        $arrInsert = [];
        while (($row = fgetcsv($file)) !== FALSE) {
            if ($hasHeader && !$isHeaderSkiped) {
                $this->headers = $row;
                $isHeaderSkiped = true;
                continue;
            }
            $arrInsert = $this->transform($row);

            if (!$arrInsert) continue;

            $role = $arrInsert["role"];
            unset($arrInsert["role"]);

            if ($this->isExists($arrInsert["email"])) {
                continue;
            }

            $user = User::create($arrInsert);
            $user->assignRole($role);
        }

        fclose($file);

        info("FINISH SEED " . __CLASS__);
    }

    protected function transform($oldRow)
    {
        $oldRow = collect($oldRow);

        $dataKey = $oldRow->map(function ($item, $index) {
            return [
                "key" => EtlHelper::transformNull($this->headers[$index]),
                "data" => EtlHelper::transformNull($item)
            ];
        });

        $originalData = [];
        foreach ($dataKey as $data) {
            $originalData[$data["key"]] = $data["data"];
        }

        return [
            "name" => $originalData["Nama"],
            "code" => $originalData["Nokt"],
            "staf_id" => $originalData["Nokt"],
            "ref_division_id" => null,
            "ref_position_id" => null,
            "tel_no" => $originalData["tel"],
            "fax_no" => null,
            "email" => $originalData["emel"],
            "password" => Hash::make("abc12345"),
            "created_at" => now(),
            "updated_at" => now(),
            "role" => $this->getRole($originalData["Status"]),
            "original_id" => $originalData["Bil"],
            "original_data" => $originalData
        ];
    }

    protected function isExists($email)
    {
        return User::where('email', $email)->exists();
    }

    protected function getRole($code)
    {
        $arrRoleTranslate = [
            "PL" => "Researcher",
            "RM" => "Division Director",
            "GS" => "R&D Coordinator",
            "DR" => "LKM Director",
        ];

        return $arrRoleTranslate[$code] ?? "Researcher";
    }

    protected function removeDuplicate()
    {
        //
    }
}
