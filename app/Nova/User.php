<?php

namespace App\Nova;

use App\Enums\ConfigValueEnum;
use App\Enums\UserMembershipMarkEnum;
use App\Enums\UserStatusEnum;
use App\Nova\Actions\User\ChangeUserPassword;
use App\Nova\Actions\User\CheckBannedUsers;
use App\Nova\Helpers\UserHelper;
use App\Nova\Lenses\Orders\MostActiveUsersWithProcessingOrders;
use Carbon\Carbon;
use Ctessier\NovaAdvancedImageField\AdvancedImage;
use DigitalCreative\ColumnToggler\ColumnTogglerTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableText;
use Maatwebsite\Excel\Excel;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Timothyasp\Badge\Badge;

class User extends Resource
{
    use ColumnTogglerTrait;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * Get the value that should be displayed as TITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function title()
    {
        return $this->name . '. With email ' . $this->email;
    }

    /**
     * Get the value that should be displayed as SUBTITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function subtitle()
    {
        return "Status: ".UserStatusEnum::getLabel($this->status) .
               (!empty($this->userProfile) ? '. Membership mark: ' . UserMembershipMarkEnum::getLabel($this->userProfile->membership_mark) : '');
    }


    public static $globalSearchResults = 10;
    public static $globallySearchable = true;
    public static $perPageOptions = [10, 25, 100];


    /**
     * The columns that should be searched.
     *
     * @var array
     */
/*    public static $search
        = [
            'id', 'name', 'email',
        ];*/

    public static function searchableColumns()
    {
        return ['id', new SearchableText('name'), new SearchableText('email'),
            new SearchableText('user_profile.phone'),
            new SearchableText('user_profile.website'),
            new SearchableText('user_profile.notes')];

        /*         Schema::table('user_profile', function (Blueprint $table) {
            $table->fullText('phone' );
            $table->fullText('website' );
            $table->fullText('notes' );
        });
 */
    }

    protected UserHelper $userHelper;

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->with('userProfile');

        return parent::indexQuery($request, $query);
    }

//    public function __construct()
//    {
//        parent::__construct();
//        $this->userHelper = new UserHelper();
//    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $userHelper = new UserHelper();
        [$isEditor, $isCreate, $headingBgColor, $editorTitle, $status] = $userHelper->getEditorProps($request);
        $filesystemDisk = ConfigValueEnum::get(ConfigValueEnum::FILESYSTEM_DISK);
        return [
            Heading::make('<p class="text-xl font-bold '.$headingBgColor.'">'.$editorTitle.'</p>')->asHtml(),
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->showWhenPeeking()
                ->readonly(function ($request) use ($isCreate) {
                    return !$isCreate;
                })
                ->rules('required', 'max:255')
                ->sortable()->help(!$isCreate ? 'This field in "Edit" mode is not editable' : ''),

            Badge::make(__('Status'))->required()
                ->showWhenPeeking()
                ->options(UserStatusEnum::getStatusSelectionItems())->hideFromDetail()->hideWhenUpdating()
                ->colors(UserStatusEnum::getStatusColors(hexValue: true))->displayUsingLabels(),

            Text::make('Status')
                ->displayUsing(function ($status) {
                    return UserStatusEnum::getLabel(UserStatusEnum::tryFrom($status));
                })->hideFromIndex()->hideWhenUpdating()->hideWhenCreating(),

            Text::make(__('Email'), 'email')
                ->showWhenPeeking()
                ->rules('required', 'email', 'max:255')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}')
                ->readonly(function ($request) use ($isCreate) {
                    return !$isCreate;
                })
                ->sortable()->help(!$isCreate ? 'This field in "Edit" mode is not editable' : ''),

            AdvancedImage::make(__('Avatar'), 'avatar')
                ->disk($filesystemDisk)
                ->path($userHelper->getUploadDirectory())->prunable()->deletable(true)
                ->showWhenPeeking()
                ->resolveUsing(function ($avatarPath) use ($userHelper, $filesystemDisk) {
                    if ($avatarPath && Storage::disk($filesystemDisk)->exists($avatarPath)) {
                        return $avatarPath;
                    }

                    return $userHelper->getDefaultAvatar();
                })
                ->resize($userHelper->getAvatarResizeWidth())->croppable($userHelper->getAvatarCroppableRatio())
                ->quality($userHelper->getAvatarQuality()),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),

            HasOne::make(__('User profile'), 'UserProfile'),

            HasMany::make(name: __('Owner of products'), attribute: 'products', resource: \App\Nova\Product::class),
            HasMany::make(name: __('Created orders'), attribute: 'orders', resource: \App\Nova\Order::class),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
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
     *
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
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [
            new MostActiveUsersWithProcessingOrders,
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            \App\Nova\Actions\User\SetStatusNew::make()->sole()
                ->canRun(function ($request, $user) {
                    return $user->status !== UserStatusEnum::NEW and $user->status !== UserStatusEnum::BANNED;
                }), // DONE
            \App\Nova\Actions\User\SetStatusActive::make()->sole()
                ->canRun(function ($request, $user) {
                    return $user->status !== UserStatusEnum::ACTIVE and $user->status !== UserStatusEnum::BANNED;
                }),
            \App\Nova\Actions\User\SetStatusInactive::make()->sole()
                ->canRun(function ($request, $user) {
                    return $user->status !== UserStatusEnum::INACTIVE and $user->status !== UserStatusEnum::BANNED;
                }),
            CheckBannedUsers::make()->standalone(),
            ChangeUserPassword::make()->sole(),
            (new DownloadExcel)->withHeadings()->onlyOnIndex()
                ->withWriterType(Excel::CSV)
                ->withName('Users report in csv format')->withFilename('users_list_'.Carbon::now().'.csv'),

//            (new DownloadExcel)->withHeadings()
////                ->withWriterType(Excel::ODS)       LoginController.php
//                ->withName('Generate report in xls format')->withFilename('users_list_' .  Carbon::now() . '.csv'),
            \Cog\Laravel\Nova\Ban\Actions\Ban::make()->sole()
                ->canRun(function ($request, $user) {
                    return $user->status !== UserStatusEnum::BANNED;
                }),
            \Cog\Laravel\Nova\Ban\Actions\Unban::make()->sole()
                ->canRun(function ($request, $user) {
                    return $user->status === UserStatusEnum::BANNED;
                })
        ];
    }
}
