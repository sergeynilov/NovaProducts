<?php

namespace App\Nova;

use App\Enums\UserMembershipMarkEnum;
use Devloops\PhoneNumber\PhoneNumber;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;

class UserProfile extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\UserProfile>
     */
    public static $model = \App\Models\UserProfile::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'User profile';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'phone', 'website', 'notes'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $membershipMarkOptions = UserMembershipMarkEnum::getMembershipMarkSelectionItems();

        return [
            ID::make()->sortable(),

            Select::make(__('Membership mark'), 'membership_mark')->required()
                ->displayUsingLabels()
                ->options($membershipMarkOptions)->sortable(),

//            Text::make(__('Phone'), 'phone')
//                ->sortable()
//                ->rules('required', 'max:100'),
            PhoneNumber::make(__('Phone'), 'phone')->sortable()
                ->rules('required', 'max:100')->withAllCountries(['ua', 'us', 'es']),
            Url::make(__('Website url'), 'website')->rules('required', 'max:255')
                ->textAlign('left')->hideFromIndex()
                ->help('Fill valid url'),

            Trix::make(__('Notes'), 'notes')
//                ->rules('required')
                ->hideFromIndex()->showOnPreview()->alwaysShow()->withFiles('public')->fullWidth()->stacked(),

            /*
CREATE TABLE `user_profile` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `membership_mark` enum('N','M','S','G') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT ' N => No membership, M - Member, S=>Silver Membership, G=>Gold Membership',
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_profile_user_id_membership_mark_index` (`user_id`,`membership_mark`),
  CONSTRAINT `user_profile_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; */
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
