

DETAILED INFORMATION :


I added

    extension=imagick.so

line in ; Dynamic Extensions ; block of both file:///etc/php/8.2/cli/php.ini and file:///etc/php/8.2/apache2/php.ini
and restarting

    service apache2 restart

I got error :

root@master-at-home:/mnt/_work_sdb8/wwwroot/lar/NovaProducts# php --modules | grep imagick
PHP Warning:  PHP Startup: Unable to load dynamic library 'imagick.so' (tried: /usr/lib/php/20220829/imagick.so (/usr/lib/php/20220829/imagick.so: cannot open shared object file: No such file or directory), /usr/lib/php/20220829/imagick.so.so (/usr/lib/php/20220829/imagick.so.so: cannot open shared object file: No such file or directory)) in Unknown on line 0

What is wrong ?

=================
=================
=================

In  Laravel 10 / nova 4.27 app I in editor I need to change background color/color of text  in detailed view page and try using `extraAttributes`
as I read in docs https://nova.laravel.com/docs/resources/fields.html#text-field :


            Text::make('Status')
                ->withMeta([
                    'extraAttributes' => [
                        'style' => 'bg-color:red !important; color:yellow !important',
                    ],
                ])
                ->displayUsing(function ($status) {
                return UserStatusEnum::getLabel(UserStatusEnum::tryFrom($status));
            })->hideFromIndex()->hideWhenUpdating()->hideWhenCreating(),


But nothing was changed and colors left the same... Can I do this in some way ?

====================
====================
====================

In  Laravel 10 / nova 4.27 app I in editor I show published_at field only if
status field has ACTIVE value as :


                    Badge::make(__('Status'), 'status')->required()
                        ->options(ProductStatusEnum::getStatusSelectionItems())->hideFromDetail()->hideWhenUpdating()
                        ->colors(ProductStatusEnum::getStatusColors(hexValue: true))->displayUsingLabels(),

                    Date::make(__('Published at'), 'published_at')
                        ->displayUsing(fn($value) => $value ? DateConv::getFormattedDateTime($value) : '--')
                        ->showOnDetail(function () use ($status) { // in view mode "/nova/resources/products/17"
                            return $status === ProductStatusEnum::ACTIVE;
                        })
                        ->showOnUpdating(function () use ($status) { // in edit mode "/nova/resources/products/17/edit"
                            return $status === ProductStatusEnum::ACTIVE;
                        })
                        ->sortable(),

it works ok when I opened exiting model, but if there is a way to show/hide published_at Date input depending
on which status is selected reactively in the form?

=============================

In  Laravel 10 / nova 4.27 app I added custom menu with products items :

                MenuSection::make('Products', [
                    MenuItem::make('Products', '/resources/products'),
                    MenuItem::make('Create Product', '/resources/products/new'),
                ])->collapsable(),



Also Product resource has custom filters :
    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [new ProductByStatus];
    }

where ProductByStatus defined app/Nova/Filters/ProductByStatus.php:


class ProductByStatus extends Filter
{
    public $name = 'By status';

    public $component = 'select-filter';

    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('status', $value);
    }

    public function options(NovaRequest $request)
    {
        return ProductStatusEnum::getStatusSelectionItems(labelAsKey: true);
    }


and enum file app/Enums/ProductStatusEnum.php:


enum ProductStatusEnum: string
{
    case DRAFT = 'D';
    case PENDING_REVIEW = 'P';
    case ACTIVE = 'A';
    case INACTIVE = 'I';

    public static function getStatusSelectionItems(bool $labelAsKey = false): array
    {
        $statusSelectionItems = [
            self::DRAFT->value => 'Draft', //  D => Draft
            self::PENDING_REVIEW->value => 'Pending Review',  // P=>Pending Review
            self::ACTIVE->value => 'Active',  // A-Active
            self::INACTIVE->value => 'Inactive',  // I-Inactive
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

Next I want to create a menu item with only Active product, but when I run Product filter with Status = 'Active'

I see such url in my browser :

    http://local-nova-products.com/nova/resources/products?products_page=1&products_filter=W3siQXBwXFxOb3ZhXFxGaWx0ZXJzXFxQcm9kdWN0QnlCcmFuZCI6IiJ9LHsiQXBwXFxOb3ZhXFxGaWx0ZXJzXFxQcm9kdWN0QnlTdGF0dXMiOiJEIn0seyJBcHBcXE5vdmFcXEZpbHRlcnNcXFByb2R1Y3RCeVB1Ymxpc2hlZEF0RmlsdGVyIjoiIn1d

How this products_filter created for me to run such filter manually ?

==================================



git add  resources/js/NewFrontend/HomePage.vue
git add  resources/js/router/router.js

git add      resources/js/NewFrontend/AdVerification.vue
git add      resources/js/NewFrontend/AntiBlockingSolutions.vue
git add      resources/js/NewFrontend/CrawlProxy.vue
git add      resources/js/NewFrontend/DynamicResidentialProxies.vue
git add      resources/js/NewFrontend/ReviewMonitoring.vue
git add      resources/js/NewFrontend/RotatingISPProxies.vue
git add      resources/js/NewFrontend/SearchEngineOptimization.vue
git add      resources/js/NewFrontend/Travel.vue
git add      resources/js/NewFrontend/UnlimitedResidentialProxies.vue
git add      resources/scss/personal-cabinet/info-pages.scss
