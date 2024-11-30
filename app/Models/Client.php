<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Scopes\ClientNotStaffUsers;
//use App\Models\Scopes\SalesmanStaffUsers;

class Client extends Model implements HasMedia
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    use HasFactory;
    use InteractsWithMedia;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ClientNotStaffUsers);
    }

    protected $fillable
        = [
            'name',
            'email',
            'password',
            'status',
//            'first_name',
//            'last_name',
//            'phone',
//            'website',
//            'has_debts',
//            'subscriber_id',
        ];

    protected $hidden
        = [
            'created_at',
            'updated_at'
        ];

/*    private static $clientStatusLabelValueArray
        = [
            'N' => 'New(Waiting activation)',
            'A' => 'Active',
            'I' => 'Inactive',
            'B' => 'Banned'
        ];

    public static function getClientStatusValueArray($keyReturn = true): array
    {
        if ( ! $keyReturn) {
            return self::$clientStatusLabelValueArray;
        }
        $resArray = [];
        foreach (self::$clientStatusLabelValueArray as $key => $value) {
            if ($keyReturn) {
                $resArray[] = ['key' => $key, 'label' => $value];
            }
        }

        return $resArray;
    }

    public static function getClientStatusLabel(string $status): string
    {
        if ( ! empty(self::$clientStatusLabelValueArray[$status])) {
            return self::$clientStatusLabelValueArray[$status];
        }

        return self::$clientStatusLabelValueArray[0];
    }*/

    public function scopeGetById($query, $id)
    {
        return $query->where($this->table . '.id', $id);
    }

    public function scopeGetByName($query, $name = null)
    {
        if (empty($name)) {
            return $query;
        }

        return $query->where($this->table . '.name', 'like', '%' . $name . '%');
    }

    public function scopeGetByEmail($query, $email = null)
    {
        if (empty($email)) {
            return $query;
        }

        return $query->where($this->table . '.email', 'like', '%' . $email . '%');
    }

//    public function scopeGetByStatus($query, $status)
//    {
//        if (empty($status)) {
//            return $query;
//        }
//
//        return $query->where($this->table . '.status', $status);
//    }

    public function scopeGetByMembershipMark($query, $membershipMark)
    {
        if (empty($membershipMark)) {
            return $query;
        }

        return $query->where($this->table . '.membership_mark', $membershipMark);
    }

/*    public static function getClientValidationRulesArray(int $clientId = null, array $skipFieldsArray = []): array
    {
        $table = (new Client)->getTable();
        $clientAvatarUploadingRules = new RulesImageUploading(UploadImageRules::UIR_AUTHOR_AVATAR);
        $clientAvatarRules          = $clientAvatarUploadingRules->getRules();
        \Log::info(varDump($clientAvatarRules, ' -1 $clientAvatarRules::'));

        $validationRulesArray = [
            'name'             => 'required|max:100|unique:' . $table,
            'email'            => 'required|email|max:100|unique:' . $table,
//            'status'           => 'required|in:' . getValueLabelKeys(Client::getClientStatusValueArray(false)),
            'password'         => 'required|min:6|max:15',
            'confirm_password' => 'required|min:6|max:15|same:password',
            'first_name'       => 'required|max:50',
            'last_name'        => 'required|max:50',
            'phone'            => 'nullable|max:100',
            'website'          => 'nullable|max:100',
            'notes'            => 'nullable',
        ];
        $validationRulesArray = Arr::add($validationRulesArray, 'avatar', $clientAvatarRules);

        foreach ($skipFieldsArray as $nextField) {
            if ( ! empty($validationRulesArray[$nextField])) {
                unset($validationRulesArray[$nextField]);
            }
        }

        \Log::info(varDump($validationRulesArray, ' -1 getClientValidationRulesArray $validationRulesArray::'));

        return $validationRulesArray;
    }*/

/*    public static function getValidationMessagesArray(): array
    {
        $coverImageUploadingRules = new RulesImageUploading(UploadImageRules::UIR_AUTHOR_AVATAR);
        $uploadedFileMaxMib       = $coverImageUploadingRules->getRuleParameterValue(UploadImageRulesParameter::UIRPV_MAX_SIZE_IN_BYTES);
        \Log::info(varDump($uploadedFileMaxMib, ' -1 $uploadedFileMaxMib::'));
        $uploadedFileMimes = $coverImageUploadingRules->getRuleParameterValue(UploadImageRulesParameter::UIRPV_ACCEPTABLE_FILE_MIMES);
        \Log::info(varDump($uploadedFileMimes, ' -1 $uploadedFileMimes::'));
        $uploadedFileDimensionsMaxWidth = $coverImageUploadingRules->getRuleParameterValue(UploadImageRulesParameter::UIRPV_DIMENSIONS_MAX_WIDTH);

        \Log::info(varDump($uploadedFileDimensionsMaxWidth, ' -1 $uploadedFileDimensionsMaxWidth::'));

        return [
            'name.required'       => 'Name is required',
            'email.required'      => 'Email is required',
            'email.email'         => 'Email is in invalid format',
//            'status.required'     => 'Status is required',
            'first_name.required' => 'First name is required',
            'last_name.required'  => 'Last name is required',
            'avatar.max'          => 'Selected avatar is too big in size. It exceeds avatar size limit in ' .
                                     getCFFileSizeAsString($uploadedFileMaxMib * 1024),
            'avatar.dimensions'   => 'Selected avatar is too big in width. Max acceptable width : ' .
                                     $uploadedFileDimensionsMaxWidth . 'px',
//            'validation.uploaded' => 'Invalid avatar is selected',
            'uploaded'            => 'Invalid avatar is selected. Check it must be not bigger ' .
                                     getCFFileSizeAsString($uploadedFileMaxMib * 1024),
            'mimes'               => 'Invalid format of avatar. Acceptable formats are : ' . $uploadedFileMimes,

        ];
    }*/

}
