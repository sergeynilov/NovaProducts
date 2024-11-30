<?php

namespace App\Enums;

enum ActionNoteTypeEnum: string
{
    case SET_USER_STATUS_NEW = 'set_user_status_new';
    case SET_USER_STATUS_ACTIVE = 'set_user_status_active';
    case SET_USER_STATUS_INACTIVE = 'set_user_status_inactive';

    case SET_USER_IS_BANNED = 'set_user_is_banned';
    case SET_USER_IS_UNBANNED = 'set_user_is_unbanned';

    case SET_BRAND_ACTIVE = 'set_brand_active';
    case SET_BRAND_INACTIVE = 'set_brand_inactive';

    case SET_PRODUCT_DRAFT = 'set_product_draft';
    case SET_PENDING_REVIEW = 'set_pending_review';
    case SET_PRODUCT_ACTIVE = 'set_product_active';
    case SET_PRODUCT_INACTIVE = 'set_product_inactive';
    case SET_USER_NEW_PASSWORD = 'set_user_new_password';

    /**
     * Cet user note  selection items
     *
     * @return array<string, string>
     */
/*    public static function getTypeSelectionItems(bool $labelAsKey = false): array
    {
        $actionNoteSelectionItems = [
            self::SET_USER_STATUS_NEW->value => 'Set user status new', //  D => Draft
        ];
        $resArray = [];
        foreach ($actionNoteSelectionItems as $key => $label) {
            if($labelAsKey) {
                $resArray[$label] = $key;
            } else {
                $resArray[$key] = $label;
            }
        }

        return $resArray;
    }*/

    /**
     * Get user note actionNote label text by provided  value
     *
     * @param ActionNoteTypeEnum
     *
     * @return string
     */
    public static function getLabel(ActionNoteTypeEnum $actionNote): ?string
    {
        $actionNoteSelectionItems = self::getTypeSelectionItems();
        if (!blank($actionNoteSelectionItems[$actionNote->value])) {
            return $actionNoteSelectionItems[$actionNote->value];
        }

        return '';
    }

}
