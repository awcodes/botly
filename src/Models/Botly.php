<?php

declare(strict_types=1);

namespace Awcodes\Botly\Models;

use Awcodes\Botly\Database\Factories\BotlyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property array $rules
 * @property array $sitemaps
 * @property array $ai_crawlers
 */
class Botly extends Model
{
    /** @use HasFactory<BotlyFactory> */
    use HasFactory;

    protected $table = 'botly';

    protected $guarded = [];

    protected static function newFactory(): BotlyFactory
    {
        return BotlyFactory::new();
    }

    protected function casts(): array
    {
        return [
            'rules' => 'array',
            'sitemaps' => 'array',
            'ai_crawlers' => 'array',
        ];
    }
}
