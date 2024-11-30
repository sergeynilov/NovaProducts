<?php

namespace App\Nova\Lenses\Orders;

use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class MostActiveUsersWithProcessingOrders extends Lens
{
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = ['name'];

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
       \Log::info(' -1 query MostActiveUsersWithProcessingOrders ::');

        return $request->withFilters( $query->select(self::columns())
            ->join('orders', 'users.id', '=', 'orders.creator_id')
            ->groupBy('users.id', 'users.name')
            ->withCasts(['orders_count' => 'float'])
            ->orderBy('orders_count', 'desc') );
/*        return $request->withOrdering($request->withFilters(
            $query->select(self::columns())
                ->join('orders', 'users.id', '=', 'orders.user_id')
                ->groupBy('users.id', 'users.name')
                ->withCasts([
                    'orders_count' => 'float',
                ])
        ), fn ($query) => $query->orderBy('orders_count', 'desc'));*/
    }

    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns()
    {
        \Log::info(' -1 MostActiveUsersWithProcessingOrders columns ::');
        return [
            'users.id',
            'users.name',
            DB::raw('count(orders.id) as orders_count'),
        ];
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        \Log::info(' -1 MostActiveUsersWithProcessingOrders fields ::');
        return [
            ID::make('ID', 'id'),
            Text::make('Name', 'name'),

            Number::make(__('Orders count'), 'orders_count', function ($value) {
                return $value;
            }),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        \Log::info(' -1 MostActiveUsersWithProcessingOrders cards ::');
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        \Log::info(' -1 filters ::');
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        \Log::info(' -1 actions ::');
        return parent::actions($request);
    }

/* Title of the card
*
* @return string
*/
    public function name(): string
    {
        \Log::info(' -1 name ::');
        return 'Active users with biggest number of processing orders';
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        \Log::info(' -1 uriKey ::');
        return 'orders-most-active-users-with-processing-orders';
    }

    /**
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorize()
    {
        \Log::info(' -1 authorize ::');
        return true;
    }

}
