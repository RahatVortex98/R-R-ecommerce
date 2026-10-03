<?php
namespace App\Support;
use Illuminate\Support\Facades\Storage;

class Media
{
    public static function url(?string $path, string $label = ''): string
    {
        if ($path) return Storage::url($path);
        $c = e(mb_strtoupper(mb_substr($label, 0, 1)));
        return 'data:image/svg+xml;utf8,'.rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600"><rect width="100%" height="100%" fill="#e8f5e9"/><text x="50%" y="58%" font-size="140" text-anchor="middle" fill="#2e7d32" font-family="sans-serif">'.$c.'</text></svg>');
    }
}