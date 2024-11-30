<?php

namespace App\Nova\Helpers;

use App\Enums\UserStatusEnum;
use Laravel\Nova\Http\Requests\NovaRequest;

class UserHelper
{

    public function getEditorProps(NovaRequest $request): array
    {
        /*        \Log::info(varDump($request->isCreateOrAttachRequest(), ' -1 $request->isCreateOrAttachRequest()::'));

                \Log::info(varDump($request->isUpdateOrUpdateAttachedRequest(), ' -2 $request->isUpdateOrUpdateAttachedRequest()::'));

                \Log::info(varDump($request->isResourceDetailRequest(), ' -3 $request->isResourceDetailRequest()::'));*/


        $isEditor = $request->isUpdateOrUpdateAttachedRequest() || $request->isCreateOrAttachRequest() || $request->isResourceDetailRequest();
        $isCreate = $request->isCreateOrAttachRequest();
        /*        \Log::info(' -1300 UserHelper fields $request::');
                \Log::info(json_encode($request));
        */
        \Log::info(' -13 UserHelper fields $isCreate::');
        \Log::info(json_encode($isCreate));

        \Log::info(' -13 UserHelper fields $isEditor::');
        \Log::info(json_encode($isEditor));

        $editorTitle = 'In create mode select status of the user manually';
        $headingBgColor = 'text-white-100';
        $status = null;
        if ($isEditor) {
            $userModel = NovaRequest::createFrom($request)
                ->findModelQuery()
                ->first();

//            \Log::info(varDump($userModel, '   -1 $userModel::::'));
//            \Log::info(varDump(get_class($userModel), '   -1 get_class($userModel)::::'));
            if(!empty($userModel) and get_class($userModel) === 'App\Models\User') {
                $status = $userModel->status ?? null;
                \Log::info(' -145 $status::');
                \Log::info(json_encode($status));
                if ( ! $isCreate and ! empty($status)) { // in Edit mode
                    $headingBgColor = UserStatusEnum::getStatusColors(hexValue: false)[$status->value];
                    $editorTitle = ' With "'.UserStatusEnum::getLabel($status).'" status use buttons to change status of the user';
                }
            }
        }
        return [$isEditor, $isCreate, $headingBgColor, $editorTitle, $status];
    }

    public function getUploadDirectory(): string
    {
        return 'public/avatars';
    }

    public function getDefaultAvatar(): string
    {
        return 'avatars/default-avatar.jpg';
    }

    public function getAvatarResizeWidth():int
    {
        return 64;
    }

    public function getAvatarCroppableRatio(): float
    {
        return 16 / 9;
    }

    public function getAvatarQuality(): int
    {
        return 100;
    }

}
