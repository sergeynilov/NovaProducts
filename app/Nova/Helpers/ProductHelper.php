<?php

namespace App\Nova\Helpers;

use App\Enums\ProductStatusEnum;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProductHelper
{
    public function getEditorProps(NovaRequest $request): array
    {
/*        \Log::info(varDump($request->isCreateOrAttachRequest(), ' -1 $request->isCreateOrAttachRequest()::'));

        \Log::info(varDump($request->isUpdateOrUpdateAttachedRequest(), ' -2 $request->isUpdateOrUpdateAttachedRequest()::'));

        \Log::info(varDump($request->isResourceDetailRequest(), ' -3 $request->isResourceDetailRequest()::'));*/


        $isEditor = $request->isUpdateOrUpdateAttachedRequest() || $request->isCreateOrAttachRequest() || $request->isResourceDetailRequest();
        $isCreate = $request->isCreateOrAttachRequest();
/*        \Log::info(' -1300 ProductHelper fields $request::');
        \Log::info(json_encode($request));
*/
        \Log::info(' -13 ProductHelper fields $isCreate::');
        \Log::info(json_encode($isCreate));

        \Log::info(' -13 ProductHelper fields $isEditor::');
        \Log::info(json_encode($isEditor));

        $editorTitle = 'In create mode select status of the product manually';
        $headingBgColor = 'text-white-100';
        $status = null;
        if ($isEditor) {
            $productModel = NovaRequest::createFrom($request)
                ->findModelQuery()
                ->first();
            if(!empty($productModel) and get_class($productModel) === 'App\Models\User') {
                \Log::info(varDump($productModel, '   -1 $productModel::::'));
                $status = $productModel->status ?? null;
                \Log::info(' -145 $status::');
                \Log::info(json_encode($status));
                if ( ! $isCreate and ! empty($status)) { // in Edit mode
                    $headingBgColor = ProductStatusEnum::getStatusColors(hexValue: false)[$status->value];
                    $editorTitle = ' With "'.ProductStatusEnum::getLabel($status).'" status use buttons to change status of the product';
                }
            }
        }
        return [$isEditor, $isCreate, $headingBgColor, $editorTitle, $status];
    }

    public function getUploadDirectory(): string
    {
        return 'public/products';
    }

    public function getImageAspectRatio(): float
    {
        return 4 / 3;
    }

    public function getConversionType(): string
    {
        return 'thumb';
    }

    public function getDefaultImage(): string
    {
        return 'products/default-product.jpeg';
    }

    /*





        public function getImageResizeWidth():int
        {
            return 256;
        }

        public function getImageCroppableRatio(): float
        {
            return 16 / 9;
        }


        public function getImageQuality(): int
        {
            return 100;
        }*/





}
