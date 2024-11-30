<?php

namespace App\Enums;

enum UserMembershipMarkEnum: string
{
    // These values are the same as enum values in db
    case NO_MEMBERSHIP = 'N';
    case MEMBER = 'M';
    case SILVER_MEMBERSHIP = 'S';
    case GOLD_MEMBERSHIP = 'G';

    /**
     * Cet user membershipMark selection items
     *
     * @return array<string, string>
     */
    public static function getMembershipMarkSelectionItems(): array
    {
        $membershipMarkSelectionItems = [
            self::NO_MEMBERSHIP->value => 'No membership', //  N = No membership
            self::MEMBER->value => 'Member',  // M=>Member
            self::SILVER_MEMBERSHIP->value => 'Silver Membership',  // S-Silver Membership
            self::GOLD_MEMBERSHIP->value => 'Gold Membership',  // G=>Gold Membership
        ];
        $resArray = [];
        foreach ($membershipMarkSelectionItems as $key => $label) {
            $resArray[$key] = $label;
        }

        return $resArray;
    }

    /**
     * Get user membership mark label text by provided  value
     *
     * @param UserMembershipMarkEnum
     *
     * @return string
     */
    public static function getLabel(UserMembershipMarkEnum $membershipMark): ?string
    {
        return self::getMembershipMarkSelectionItems()[$membershipMark->value] ?? '';
    }

}
