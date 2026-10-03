<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    public static function allCached(): array { return Cache::rememberForever('settings', fn () => static::pluck('value', 'key')->all()); }
    public static function get(string $key, $default = null) { return static::allCached()[$key] ?? $default; }
    public static function put(array $kv): void
    {
        foreach ($kv as $k => $v) static::updateOrCreate(['key' => $k], ['value' => $v]);
        Cache::forget('settings');
    }
}