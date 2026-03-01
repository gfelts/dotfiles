<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Referral;
use App\Models\User;

class AuditService
{
    public static function log(Referral $referral, User $user, string $action, array $changes = []): void
    {
        AuditLog::create([
            'referral_id' => $referral->id,
            'user_id' => $user->id,
            'user_initials' => $user->initials,
            'action' => $action,
            'field_changes' => empty($changes) ? null : $changes,
            'created_at' => now(),
        ]);
    }

    public static function buildChanges(Referral $referral): array
    {
        $changes = [];
        foreach ($referral->getChanges() as $field => $newValue) {
            if (in_array($field, ['updated_at', 'updated_by'])) {
                continue;
            }
            $changes[] = [
                'field' => $field,
                'old' => $referral->getOriginal($field),
                'new' => $newValue,
            ];
        }
        return $changes;
    }
}
