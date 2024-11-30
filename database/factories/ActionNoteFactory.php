<?php

namespace Database\Factories;

use App\Enums\NovaSettingsParamEnum;
use App\Enums\ActionNoteTypeEnum;
use App\Models\Brand;
use App\Models\Product;
use App\Models\StaffUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActionNote>
 */
class ActionNoteFactory extends Factory
{
    public function definition()
    {
        $staffUserId = $this->faker->randomElement(StaffUser::all()->select('id'))['id'];
        $typeSelectionItem = $this->faker->randomElement( ActionNoteTypeEnum::cases() );

        if(in_array($typeSelectionItem, [
            ActionNoteTypeEnum::SET_USER_STATUS_NEW,
            ActionNoteTypeEnum::SET_USER_STATUS_ACTIVE,
            ActionNoteTypeEnum::SET_USER_STATUS_INACTIVE,
            ActionNoteTypeEnum::SET_USER_IS_BANNED,
            ActionNoteTypeEnum::SET_USER_IS_UNBANNED,
        ])) {
            $modelType = User::class;
            $modelId = $this->faker->randomElement(User::all()->select('id'))['id'];
        }

        if(in_array($typeSelectionItem, [
            ActionNoteTypeEnum::SET_BRAND_ACTIVE,
            ActionNoteTypeEnum::SET_BRAND_INACTIVE
        ])) {
            $modelType = Brand::class;
            $modelId = $this->faker->randomElement(Brand::all()->select('id'))['id'];
        }

        if(in_array($typeSelectionItem, [
            ActionNoteTypeEnum::SET_PRODUCT_DRAFT,
            ActionNoteTypeEnum::SET_PENDING_REVIEW,
            ActionNoteTypeEnum::SET_PRODUCT_ACTIVE,
            ActionNoteTypeEnum::SET_PRODUCT_INACTIVE,
            ActionNoteTypeEnum::SET_USER_NEW_PASSWORD,
        ])) {
            $modelType = Product::class;
            $modelId = $this->faker->randomElement(Product::all()->select('id'))['id'];
        }

//        \Log::info(varDump($typeSelectionItem, ' -1 $typeSelectionItem::'));
//        \Log::info(varDump($modelType, ' -1 $modelType::'));
//        \Log::info(varDump($modelId, ' -1 $modelId::'));
        return [
//            'model_type', 'model_id'
            'user_id' => $staffUserId,
            'model_type' => $modelType,
            'model_id' => $modelId ,
            'note_type' => $typeSelectionItem,
            'note' => $this->faker->realText(2500),
        ];
    }

}
