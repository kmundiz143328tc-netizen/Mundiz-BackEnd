<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'changes',
        'ip_address',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ← THIS METHOD IS WHAT EVERYTHING CALLS
    public static function log(
        string $action,
        string $module,
        string $description,
        array $changes = []
    ): void {
        try {
            static::create([
                'user_id'     => auth()->id(),
                'action'      => $action,
                'module'      => $module,
                'description' => $description,
                'changes'     => $changes ?: null,
                'ip_address'  => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Silent fail — never crash the main feature
        }
    }
}