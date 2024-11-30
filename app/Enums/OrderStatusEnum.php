<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    // These values are the same as enum values in db
    case DRAFT = 'D';
    case INVOICE = 'I';
    case CANCELLED = 'C';
    case PROCESSING = 'P';
    case COMPLETED = 'O';
    case REFUNDED = 'R';

    /**
     * Cet order status selection items
     *
     * @return array<string, string>
     */
    public static function getStatusSelectionItems(bool $labelAsKey = false): array
    {
        $statusSelectionItems = [
            self::DRAFT->value => 'Draft', //  D => Draft
            self::INVOICE->value => 'Invoice',  // I=>Invoice
            self::CANCELLED->value => 'Cancelled',  // C-Cancelled
            self::PROCESSING->value => 'Processing',  // P-Processing
            self::COMPLETED->value => 'Completed',  // O-Completed
            self::REFUNDED->value => 'Refunded',  // R-Refunded
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
     * Get order status label text by provided  value
     *
     * @param OrderStatusEnum
     *
     * @return string
     */
    public static function getLabel(OrderStatusEnum $status): ?string
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
            self::DRAFT->value => $hexValue ? '#ffff00' : 'text-yellow-500', //  D => Draft  OK
            self::PROCESSING->value => $hexValue ? '#ff0000' : 'text-red-600', // P=>PROCESSING  OK
            self::COMPLETED->value => $hexValue ? '#00aa00' : 'text-green-600', // O-COMPLETED   OK
            self::CANCELLED->value => $hexValue ? '#53537d' : 'text-gray-500', // C-CANCELLED OK


            self::INVOICE->value => $hexValue ? '#32CD32' : 'text-lime-500', // I-INVOICE OK
            self::REFUNDED->value => $hexValue ? '#7F00FF' : 'text-violet-500', // R-REFUNDED OK
        ];
        $resArray = [];
        foreach ($statusSelectionItems as $key => $label) {
            $resArray[$key] = $label;
        }

        return $resArray;
    }



}
