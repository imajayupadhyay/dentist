<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ContactSubmission extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_CLOSED = 'closed';

    public const STATUSES = [
        self::STATUS_NEW => 'New',
        self::STATUS_CONTACTED => 'Contacted',
        self::STATUS_CLOSED => 'Closed',
    ];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'treatment',
        'preferred_date',
        'preferred_time',
        'message',
        'source_page',
        'status',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    /**
     * @return list<array{value: string, label: string}>
     */
    public static function statusOptions(): array
    {
        return collect(self::STATUSES)
            ->map(fn (string $label, string $value): array => [
                'value' => $value,
                'label' => $label,
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        $preferredDate = $this->preferred_date ? Carbon::parse($this->preferred_date) : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'treatment' => $this->treatment,
            'preferred_date' => $preferredDate?->format('M j, Y'),
            'preferred_date_value' => $preferredDate?->toDateString(),
            'preferred_time' => $this->preferred_time,
            'message' => $this->message,
            'source_page' => $this->source_page,
            'status' => $this->status,
            'status_label' => self::STATUSES[$this->status] ?? ucfirst($this->status),
            'admin_notes' => $this->admin_notes,
            'created_at' => $this->created_at?->format('M j, Y, g:i A'),
        ];
    }
}
