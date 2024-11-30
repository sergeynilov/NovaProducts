<?php

namespace App\Enums;

enum BrandActiveEnum: int
{
    // These values are the same as enum values in db
    case ACTIVE = 1;
    case NOT_ACTIVE = 0;

    /**
     * Cet brand active selection items
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
     * Get brand active label text by provided brand active value
     *
     * @param BrandActiveEnum
     *
     * @return string
     */
    public static function getLabel(BrandActiveEnum $active): ?string
    {
        return self::getActiveSelectionItems()[$active->value] ?? '';
    }

}

