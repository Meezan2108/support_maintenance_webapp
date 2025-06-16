<?php

namespace Database\Seeders;

use App\Models\RefTargetKpiCategory;
use Illuminate\Database\Seeder;

class RefTargetKpiCategorySeeder extends Seeder
{
    protected $data = [
        [
            "code" => "publication",
            "description" => "Publication",
            "type" => "half_year",
            "sub" => [
                [
                    "code" => "01",
                    "description" => "Article"
                ],
                [
                    "code" => "02",
                    "description" => "Poster"
                ],
                [
                    "code" => "03",
                    "description" => "Audio Video"
                ],
                [
                    "code" => "04",
                    "description" => "Brochure"
                ],
                [
                    "code" => "05",
                    "description" => "Bulletin"
                ],
                [
                    "code" => "06",
                    "description" => "Book"
                ],
                [
                    "code" => "07",
                    "description" => "Journal"
                ],
                [
                    "code" => "08",
                    "description" => "Magazine"
                ],
                [
                    "code" => "09",
                    "description" => "Proceeding"
                ],
                [
                    "code" => "10",
                    "description" => "Working Paper"
                ],
                [
                    "code" => "11",
                    "description" => "Thesis"
                ],
            ]
        ],
        [
            "code" => "output-rnd",
            "description" => "Output R&D",
            "type" => "half_year",
            "sub" => [
                [
                    "code" => "01",
                    "description" => "Product"
                ],
                [
                    "code" => "02",
                    "description" => "Technology"
                ],
            ]
        ],
        [
            "code" => "ipr",
            "description" => "IPR",
            "type" => "half_year",
        ],
        [
            "code" => "commercialization",
            "description" => "Commercialization",
            "type" => "half_year",
        ],
        [
            "code" => "analytical_service_lab",
            "description" => "Analytical Service Lab",
            "type" => "quarter",
            "sub" => [
                [
                    "code" => "01",
                    "description" => "Number of Sample"
                ],
                [
                    "code" => "02",
                    "description" => "Number of Analysis"
                ],
                [
                    "code" => "03",
                    "description" => "Number of Analysis Protocol"
                ],
            ]
        ],
        [
            "code" => "imported_germplasm",
            "description" => "Imported Germplasm",
            "type" => "quarter",
        ],
        [
            "code" => "recognition",
            "description" => "Recognition",
            "type" => "half_year",
        ],
        [
            "code" => "type_of_fund",
            "description" => "Type of Fund",
            "type" => "year",
            "sub" => [
                [
                    "code" => "01",
                    "description" => "Fundamental Research Grant Scheme (FRGS)"
                ],
                [
                    "code" => "02",
                    "description" => "Malaysia Social Innovation (MySI) fund"
                ],
                [
                    "code" => "03",
                    "description" => "Tabung Penyelidikan Sementara (TRF)"
                ],
                [
                    "code" => "04",
                    "description" => "Pembangunan"
                ],
                [
                    "code" => "04",
                    "description" => "Antarabangsa"
                ],
                [
                    "code" => "04",
                    "description" => "Tempatan"
                ],
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        RefTargetKpiCategory::truncate();
        info("START SEED " . __CLASS__);

        foreach ($this->data as $item) {
            $arrSubCategory = $item["sub"] ?? [];
            unset($item["sub"]);
            $category = RefTargetKpiCategory::create($item);

            foreach ($arrSubCategory as $subItem) {
                $subItem["code"] = $category->code . "-" . $subItem["code"];
                $subItem["parent_id"] = $category->id;
                $subItem["type"] = $category->type;
                $subCategory = RefTargetKpiCategory::create($subItem);
            }
        }

        info("FINISH SEED " . __CLASS__);
    }
}
