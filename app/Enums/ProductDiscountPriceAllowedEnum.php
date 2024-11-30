<?php

namespace App\Enums;

enum ProductDiscountPriceAllowedEnum: int
{
    // These values are the same as enum values in db
    case DISCOUNT_PRICE_ALLOWED = 1;
    case NOT_DISCOUNT_PRICE_ALLOWED = 0;

    /**
     * Cet product inStock selection items
     *
     * @return array<string, string>
     */
    public static function getDiscountPriceAllowedSelectionItems(bool $labelAsKey = false): array
    {
        $inStockSelectionItems = [
            self::DISCOUNT_PRICE_ALLOWED->value => 'Discount price allowed', //  = true
            self::NOT_DISCOUNT_PRICE_ALLOWED->value => 'Discount price not allowed',  // false
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
     * @param ProductDiscountPriceAllowedEnum
     *
     * @return string
     */
    public static function getLabel(ProductDiscountPriceAllowedEnum $inStock): ?string
    {
        return self::getDiscountPriceAllowedSelectionItems()[$inStock->value] ?? '';
    }

}

