<?php

namespace App\Enums;

enum ProductIsFeaturedEnum: int
{
    // These values are the same as enum values in db
    case IS_FEATURED = 1;
    case NOT_IS_FEATURED = 0;

    /**
     * Cet product inStock selection items
     *
     * @return array<string, string>
     */
    public static function getIsFeaturedSelectionItems(bool $labelAsKey = false): array
    {
        $inStockSelectionItems = [
            self::IS_FEATURED->value => 'Is featured', //  = true
            self::NOT_IS_FEATURED->value => 'Is featured',  // false
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
     * @param ProductIsFeaturedEnum
     *
     * @return string
     */
    public static function getLabel(ProductIsFeaturedEnum $inStock): ?string
    {
        return self::getIsFeaturedSelectionItems()[$inStock->value] ?? '';
    }

}

