<?php

namespace App\Enums;

enum ProductInStockEnum: int
{
    // These values are the same as enum values in db
    case IN_STOCK = 1;
    case NOT_IN_STOCK = 0;

    /**
     * Cet product inStock selection items
     *
     * @return array<string, string>
     */
    public static function getInStockSelectionItems(bool $labelAsKey = false): array
    {
        $inStockSelectionItems = [
            self::IN_STOCK->value => 'In stock', //  = true
            self::NOT_IN_STOCK->value => 'not in stock',  // false
        ];
        $resArray = [];
        foreach ($inStockSelectionItems as $key => $label) {
            if($labelAsKey) {
                $resArray[$label] = $key;
            } else {
                $resArray[$key] = $label;
            }
        }

        return $resArray;
    }

    /**
     * Get product inStock label text by provided product inStock value
     *
     * @param ProductInStockEnum
     *
     * @return string
     */
    public static function getLabel(ProductInStockEnum $inStock): ?string
    {
        return self::getInStockSelectionItems()[$inStock->value] ?? '';
    }

}

