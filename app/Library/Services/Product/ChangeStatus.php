<?php

namespace App\Library\Services\Product;

use App\Enums\ActionNoteTypeEnum;
use App\Enums\ConfigValueEnum;
use App\Enums\ProductStatusEnum;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class ChangeStatus
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function setDraft(int $productId, string $note): bool
    {
        $product = Product::find($productId);
        \Log::info(varDump($productId, ' -1 ? setDraft $productId::'));

        if (empty($product) or $product->status === ProductStatusEnum::DRAFT) {
            return false;
        }
        \Log::info(varDump($product->status, ' -12 INSIDE setDraft $product->status::'));
        $product->status = ProductStatusEnum::DRAFT;
        $product->updated_at = Carbon::now(ConfigValueEnum::get(ConfigValueEnum::TIMEZONE));
        $product->save();

        (new \App\Library\Services\ActionNote\Crud)->insert(
            userId: $this->user->id,
            modelType: Product::class,
            modelId: $productId,
            noteType: ActionNoteTypeEnum::SET_PRODUCT_DRAFT,
            note: $note
        );

        return true;
    }

    public function setPendingReview(int $productId, string $note): bool
    {
        $product = Product::find($productId);
//        \Log::info(varDump($productId, ' -1 ? $productId::'));

        if (empty($product) or $product->status === ProductStatusEnum::PENDING_REVIEW) {
            return false;
        }
        \Log::info(varDump($product->status, ' -12 INSIDE setPendingReview $product->status::'));
        $product->status = ProductStatusEnum::PENDING_REVIEW;
        $product->updated_at = Carbon::now(ConfigValueEnum::get(ConfigValueEnum::TIMEZONE));
        $product->save();

        (new \App\Library\Services\ActionNote\Crud)->insert(
            userId: $this->user->id,
            modelType: Product::class,
            modelId: $productId,
            noteType: ActionNoteTypeEnum::SET_PENDING_REVIEW,
            note: $note
        );

        return true;
    }

    public function setActive(int $productId, string $note): bool
    {
        $product = Product::find($productId);
//        \Log::info(varDump($productId, ' -1 ? $productId::'));

        if (empty($product) or $product->status === ProductStatusEnum::ACTIVE) {
            return false;
        }
        \Log::info(varDump($product->status, ' -12 INSIDE setActive $product->status::'));
        $product->status = ProductStatusEnum::ACTIVE;
        $product->updated_at = Carbon::now(ConfigValueEnum::get(ConfigValueEnum::TIMEZONE));
        $product->save();

        (new \App\Library\Services\ActionNote\Crud)->insert(
            userId: $this->user->id,
            modelType: Product::class,
            modelId: $productId,
            noteType: ActionNoteTypeEnum::SET_PRODUCT_ACTIVE,
            note: $note
        );

        return true;
    }

    public function setInactive(int $productId, string $note): bool
    {
        $product = Product::find($productId);
        \Log::info(varDump($productId, ' -1 ? $productId::'));

        if (empty($product) or $product->status === ProductStatusEnum::INACTIVE) {
            return false;
        }
        \Log::info(varDump($product->status, ' -12 INSIDE setInactive $product->status::'));
        $product->status = ProductStatusEnum::INACTIVE;
        $product->updated_at = Carbon::now(ConfigValueEnum::get(ConfigValueEnum::TIMEZONE));
        $product->save();

        (new \App\Library\Services\ActionNote\Crud)->insert(
            userId: $this->user->id,
            modelType: Product::class,
            modelId: $productId,
            noteType: ActionNoteTypeEnum::SET_PRODUCT_INACTIVE,
            note: $note
        );

        return true;
    }

}
