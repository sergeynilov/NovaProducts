<?php

namespace App\Library\Services\ActionNote;

use App\Enums\ActionNoteTypeEnum;
use App\Models\ActionNote;

class Crud
{
    public function insert(int $userId, string $modelType, int $modelId, ActionNoteTypeEnum $noteType, string $note): bool
    {
        ActionNote::create([
            'user_id' => $userId,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'note_type' => $noteType,
            'note' => $note,
        ]);
        return true;
    }
}
