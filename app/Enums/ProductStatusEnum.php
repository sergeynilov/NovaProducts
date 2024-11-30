<?php

namespace App\Enums;

enum ProductStatusEnum: string
{
    // These values are the same as enum values in db
    case DRAFT = 'D';
    case PENDING_REVIEW = 'P';
    case ACTIVE = 'A';
    case INACTIVE = 'I';

    /**
     * Cet product status selection items
     *
     * @return array<string, string>
     */
    public static function getStatusSelectionItems(bool $labelAsKey = false): array
    {
        $statusSelectionItems = [
            self::DRAFT->value => 'Draft', //  D => Draft
            self::PENDING_REVIEW->value => 'Pending Review',  // P=>Pending Review
            self::ACTIVE->value => 'Active',  // A-Active
            self::INACTIVE->value => 'Inactive',  // I-Inactive
        ];
        $resArray = [];
        foreach ($statusSelectionItems as $key => $label) {
            if($labelAsKey) {
                $resArray[$label] = $key;
            } else {
                $resArray[$key] = $label;
            }
        }

        return $resArray;
    }

    /**
     * Cet product status colors
     *
     * @return array<string, string>
     */
    public static function getStatusColors(bool $hexValue = false): array
    {
        $statusSelectionItems = [
            self::DRAFT->value => $hexValue ? '#ffff00' : 'text-yellow-500', //  D => Draft  OK
            self::PENDING_REVIEW->value => $hexValue ? '#ff0000' : 'text-red-600', // P=>Pending Review  OK
            self::ACTIVE->value => $hexValue ? '#00aa00' : 'text-green-600', // A-Active   OK
            self::INACTIVE->value => $hexValue ? '#53537d' : 'text-gray-500', // I-Inactive OK
        ];
        $resArray = [];
        foreach ($statusSelectionItems as $key => $label) {
            $resArray[$key] = $label;
        }

        return $resArray;
    }



    /**
     * Get product status label text by provided  value
     *
     * @param ProductStatusEnum
     *
     * @return string
     */
    public static function getLabel(ProductStatusEnum $status): ?string
    {
        $statusSelectionItems = self::getStatusSelectionItems();
        if (!blank($statusSelectionItems[$status->value])) {
            return $statusSelectionItems[$status->value];
        }

        return '';
    }

}
