<?php

namespace App\Models;

use App\Enums\ActionNoteTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionNote extends Model
{
    use HasFactory;
    protected $table = 'action_notes';
    public $timestamps = false;

    protected $casts
        = [
            'created_at' => 'datetime',
            'note_type' => ActionNoteTypeEnum::class,
        ];

    protected $fillable = ['model_type', 'model_id', 'user_id', 'note_type', 'note'];

//$table->index(['model_type', 'model_id', 'note_type'], 'action_notes_3fields_index');

    public function scopeGetByUserId($query, int $userId = null): Builder
    {
        if ( ! empty($userId)) {
            $query->where($this->table . '.user_id', $userId);
        }

        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
