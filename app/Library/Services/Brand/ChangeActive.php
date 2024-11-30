<?php

namespace App\Library\Services\Brand;

use App\Enums\ActionNoteTypeEnum;
use App\Enums\ConfigValueEnum;
use App\Enums\BrandActiveEnum;
use App\Models\Brand;
use App\Models\User;
use Carbon\Carbon;

class ChangeActive
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function setActive(int $brandId, string $note): bool
    {
        $brand = Brand::find($brandId);
//        \Log::info(varDump($brandId, ' -1 ? $brandId::'));

        if (empty($brand) or $brand->active === BrandActiveEnum::ACTIVE) {
            return false;
        }
//        \Log::info(varDump($brand->active, ' -12 INSIDE setActive $brand->active::'));
        $brand->active = BrandActiveEnum::ACTIVE;
        $brand->updated_at = Carbon::now(ConfigValueEnum::get(ConfigValueEnum::TIMEZONE));
        $brand->save();

        (new \App\Library\Services\ActionNote\Crud)->insert(
            userId: $this->user->id,
            modelType: Brand::class,
            modelId: $brandId,
            noteType: ActionNoteTypeEnum::SET_BRAND_ACTIVE,
            note: $note
        );

        return true;
    }

    public function setInactive(int $brandId, string $note): bool
    {
        $brand = Brand::find($brandId);
        \Log::info(varDump($brandId, ' -1 ? setInactive $brandId::'));

        if (empty($brand) or $brand->active === BrandActiveEnum::NOT_ACTIVE) {
            return false;
        }
//        \Log::info(varDump($brand->active, ' -12 INSIDE setInactive $brand->active::'));
        $brand->active = BrandActiveEnum::NOT_ACTIVE;
        $brand->updated_at = Carbon::now(ConfigValueEnum::get(ConfigValueEnum::TIMEZONE));
        $brand->save();

        (new \App\Library\Services\ActionNote\Crud)->insert(
            userId: $this->user->id,
            modelType: Brand::class,
            modelId: $brandId,
            noteType: ActionNoteTypeEnum::SET_BRAND_INACTIVE,
            note: $note
        );

        return true;
    }

}
