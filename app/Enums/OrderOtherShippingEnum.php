<?php

namespace App\Enums;

enum OrderOtherShippingEnum: int
{
    // These values are the same as enum values in db
    case OTHER_SHIPPING = 1;
    case NOT_OTHER_SHIPPING = 0;

    /**
     * Cet order otherShipping selection items
     *
     * @return array<string, string>
     */
    public static function getOtherShippingSelectionItems(bool $labelAsKey = false): array
    {
        $otherShippingSelectionItems = [
            self::OTHER_SHIPPING->value => 'Other shipping', //  = true
            self::NOT_OTHER_SHIPPING->value => 'not other shipping',  // false
        ];
        $resArray = [];
        foreach ($otherShippingSelectionItems as $key => $label) {
            if($labelAsKey) {
                $resArray[$label] = $key;
            } else {
                $resArray[$key] = $label;
            }
        }

        return $resArray;
    }

    /**
     * Get order otherShipping label text by provided order otherShipping value
     *
     * @param OrderOtherShippingEnum
     *
     * @return string
     */
    public static function getLabel(OrderOtherShippingEnum $otherShipping): ?string
    {
        return self::getOtherShippingSelectionItems()[$otherShipping->value] ?? '';
    }

}

