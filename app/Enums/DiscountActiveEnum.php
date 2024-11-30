<?php

namespace App\Enums;

enum DiscountActiveEnum: int
{
    // These values are the same as enum values in db
    case ACTIVE = 1;
    case NOT_ACTIVE = 0;

    /**
     * Cet discount active selection items
     *
     * @return array<string, string>
     */
    public static function getActiveSelectionItems(bool $labelAsKey = false): array
    {
        $activeSelectionItems = [
            self::ACTIVE->value => 'Is Active', //  = true
            self::NOT_ACTIVE->value => 'Is Not Active',  // false
        ];
        $resArray = [];
        foreach ($activeSelectionItems as $key => $label) {
            if($labelAsKey) {
                $resArray[$label] = $key;
            } else {
                $resArray[$key] = $label;
            }
        }

        return $resArray;
    }

    /**
     * Get discount active label text by provided discount active value
     *
     * @param DiscountActiveEnum
     *
     * @return string
     */
    public static function getLabel(DiscountActiveEnum $active): ?string
    {
        return self::getActiveSelectionItems()[$active->value] ?? '';
    }

}

