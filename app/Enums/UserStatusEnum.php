<?php

namespace App\Enums;

enum UserStatusEnum: string
{
    // These values are the same as enum values in db
    case NEW = 'N';
    case ACTIVE = 'A';
    case INACTIVE = 'I';
    case BANNED = 'B';

    /**
     * Cet user status selection items
     *
     * @return array<string, string>
     */
    public static function getStatusSelectionItems(bool $labelAsKey = false): array
    {
        $statusSelectionItems = [
            self::NEW->value => 'New', //  N = New(Waiting activation)
            self::ACTIVE->value => 'Active',  // A=>Active
            self::INACTIVE->value => 'Inactive',  // I-Inactive
            self::BANNED->value => 'Banned',  // B=>Banned
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
     * Get user status label text by provided  value
     *
     * @param UserStatusEnum
     *
     * @return string
     */
    public static function getLabel(UserStatusEnum $status): ?string
    {
        $statusSelectionItems = self::getStatusSelectionItems();
        if (!blank($statusSelectionItems[$status->value])) {
            return $statusSelectionItems[$status->value];
        }

        return '';
    }


    /**
     * Cet product status colors
     *
     * @return array<string, string>
     */
    public static function getStatusColors(bool $hexValue = false): array
    {
        $statusSelectionItems = [
            /*             self::NEW->value => 'New', //  N = New(Waiting activation)
            self::ACTIVE->value => 'Active',  // A=>Active
            self::INACTIVE->value => 'Inactive',  // I-Inactive
            self::BANNED->value => 'Banned',  // B=>Banned
 */
            self::NEW->value => $hexValue ? '#ffff00' : 'text-yellow-500', //  D => Draft  OK
            self::BANNED->value => $hexValue ? '#ff0000' : 'text-red-600', // P=>Pending Review  OK
            self::ACTIVE->value => $hexValue ? '#00aa00' : 'text-green-600', // A-Active   OK
            self::INACTIVE->value => $hexValue ? '#53537d' : 'text-gray-500', // I-Inactive OK
        ];
        $resArray = [];
        foreach ($statusSelectionItems as $key => $label) {
            $resArray[$key] = $label;
        }

        return $resArray;
    }

}
