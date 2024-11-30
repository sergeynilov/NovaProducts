<?php

namespace App\Nova\Helpers;

use App\Enums\OrderStatusEnum;
use App\Library\Facades\DateConv;
use App\Nova\Order;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class OrderHelper
{


    public function getEditorProps(NovaRequest $request): array
    {
        /*        \Log::info(varDump($request->isCreateOrAttachRequest(), ' -1 $request->isCreateOrAttachRequest()::'));

                \Log::info(varDump($request->isUpdateOrUpdateAttachedRequest(), ' -2 $request->isUpdateOrUpdateAttachedRequest()::'));

                \Log::info(varDump($request->isResourceDetailRequest(), ' -3 $request->isResourceDetailRequest()::'));*/


        $isEditor = $request->isUpdateOrUpdateAttachedRequest() || $request->isCreateOrAttachRequest() || $request->isResourceDetailRequest();
        $isCreate = $request->isCreateOrAttachRequest();
        /*        \Log::info(' -1300 OrderHelper fields $request::');
                \Log::info(json_encode($request));
        */
        \Log::info(' -13 OrderHelper fields $isCreate::');
        \Log::info(json_encode($isCreate));

        \Log::info(' -13 OrderHelper fields $isEditor::');
        \Log::info(json_encode($isEditor));

        $editorTitle = 'In create mode select status of the order manually';
        $headingBgColor = 'text-white-100';
        $status = null;
        if ($isEditor) {
            $orderModel = NovaRequest::createFrom($request)
                ->findModelQuery()
                ->first();

//            \Log::info(varDump($orderModel, '   -1 $orderModel::::'));
//            \Log::info(varDump(get_class($orderModel), '   -1 get_class($orderModel)::::'));
            if(!empty($orderModel) and get_class($orderModel) === 'App\Models\Order') {
                $status = $orderModel->status ?? null;
                \Log::info(' -145 $status::');
                \Log::info(json_encode($status));
                if ( ! $isCreate and ! empty($status)) { // in Edit mode
                    $headingBgColor = OrderStatusEnum::getStatusColors(hexValue: false)[$status->value];
                    $editorTitle = ' With "'.OrderStatusEnum::getLabel($status).'" status use buttons to change status of the order';
                }
            }
        }
        return [$isEditor, $isCreate, $headingBgColor, $editorTitle, $status];
    }


    public function getMiscellaneousFields(Order $orderResource, bool $isEdit, ?OrderStatusEnum $status = null): array
    {
//        \Log::info(varDump($orderResource, ' -1 getMiscellaneousFields $orderResource::'));
        \Log::info(varDump($status, ' -1 $status::'));
        return [
/*            Slug::make('Slug')->from('title')
                ->textAlign('left')->hideFromIndex()->hideWhenCreating()
                ->readonly()->hideWhenCreating(),

            Slug::make('Sku')
                ->textAlign('left')->hideFromIndex()->hideWhenCreating()
                ->readonly()->hideWhenCreating(),*/

            Text::make(__('Payment client ip'), 'payment_client_ip')->readonly()->hideFromIndex(),

            Text::make('Created at')->readonly()->resolveUsing(function ($createdAt) {
                return DateConv::getFormattedDateTime($createdAt);
            })->showOnUpdating()->showOnDetail()->hideWhenCreating(),

            Text::make('Updated at')->readonly()->resolveUsing(function ($updatedAt) {
                return DateConv::getFormattedDateTime($updatedAt);
            })->showOnUpdating()->hideWhenCreating()->showOnDetail( // in "View" mode "products/11"
                function (NovaRequest $request, $resource) {
                    return ! empty($updatedAt);
                }
            )->showOnUpdating( // in "Edit" mode "products/11/edit"
                function (NovaRequest $request, $resource) {
                    return ! empty($updatedAt);
                }
            ),
        ];
    }

    public function getOtherShippingFields(Order $orderResource, bool $isEdit, ?OrderStatusEnum $status = null): array
    {
        return [
            Heading::make('<p class="text-d font-bold">The Order would be shipped to this address</p>')->asHtml(),
        ];
    }

    public function getBillingFields(Order $orderResource, bool $isEdit, ?OrderStatusEnum $status = null): array {
        return [
            Text::make(__('Billing first name'), 'billing_first_name')->rules('max:50')->hideFromIndex(),
            Text::make(__('Billing phone'), 'billing_phone')->rules('max:20')->hideFromIndex(),
            Text::make(__('Billing last name'), 'billing_last_name')->rules('max:50')->sortable()->hideFromIndex(),
            Text::make(__('Billing company'), 'billing_company')->rules('max:100')->sortable()->hideFromIndex(),
            Email::make(__('Billing email'), 'billing_email')->rules('max:100')->hideFromIndex(),

            Text::make(__('Billing country'), 'billing_country')->rules('max:2')->hideFromIndex(),
            // NSN_TODO
            Text::make(__('Billing address'), 'billing_address')->rules('max:100')->hideFromIndex(),
            Text::make(__('Billing address 2'), 'billing_address2')->rules('max:100')->sortable()->hideFromIndex(),

            Text::make(__('Billing city'), 'billing_city')->rules('max:50')->hideFromIndex(),
            Text::make(__('Billing state'), 'billing_state')->rules('max:100')->hideFromIndex(),
            Text::make(__('Billing first name'), 'billing_first_name')->rules('max:50')->hideFromIndex(),
            Text::make(__('Billing postcode'), 'billing_postcode')->rules('max:100')->hideFromIndex(),

        ];
    }

}
