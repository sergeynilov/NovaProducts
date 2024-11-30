<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportProducts implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        \Log::info(varDump($row, ' -1 $row::'));
        return new Product([
            'user_id' => $row[1],
  'title' => $row[2],
  'status' => $row[3],
  'slug' => $row[4],
  'sku' => $row[5],
  'brand_id' => $row[6],
  'regular_price' => $row[7],
  'sale_price'  => $row[8],
  'in_stock' => $row[9],
  'stock_qty' => $row[10],
  'discount_price_allowed' => $row[11],
  'is_featured' => $row[12],
  'short_description' => $row[13],
  'description' => $row[14],
  'published_at' => $row[15],
        ]);
        /* CREATE TABLE 'products' (
  'id' bigint unsigned NOT NULL AUTO_INCREMENT,
  'user_id' bigint unsigned NOT NULL,
  'title' varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  'status' enum('D','P','A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'D' COMMENT ' D => Draft, P=>Pending Review, A=>Active, I=>Inactive',
  'slug' varchar(260) COLLATE utf8mb4_unicode_ci NOT NULL,
  'sku' varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  'brand_id' smallint unsigned NOT NULL,
  'regular_price' int unsigned DEFAULT NULL,
  'sale_price' int unsigned DEFAULT NULL,
  'in_stock' tinyint(1) NOT NULL DEFAULT '0',
  'stock_qty' mediumint unsigned NOT NULL DEFAULT '0',
  'discount_price_allowed' tinyint(1) NOT NULL DEFAULT '0',
  'is_featured' tinyint(1) NOT NULL DEFAULT '0',
  'short_description' mediumtext COLLATE utf8mb4_unicode_ci,
  'description' longtext COLLATE utf8mb4_unicode_ci,
  'published_at' datetime DEFAULT NULL,
  'created_at' timestamp NULL DEFAULT NULL,
  'updated_at' timestamp NULL DEFAULT NULL,
  PRIMARY KEY ('id'), */
    }

    public function startRow(): int
    {
        \Log::info(varDump(-99, 'startRow'));
        return 2;
    }
}
