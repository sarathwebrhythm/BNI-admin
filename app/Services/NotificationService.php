<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function create(
        int $memberId,
        string $title,
        string $message,
        string $type,
        ?int $referenceId = null,
        ?string $referenceType = null
    ) {
        return Notification::create([
            'member_id'      => $memberId,
            'title'          => $title,
            'message'        => $message,
            'type'           => $type,
            'reference_id'   => $referenceId,
            'reference_type' => $referenceType,
            'is_read'        => false,
        ]);
    }
}
