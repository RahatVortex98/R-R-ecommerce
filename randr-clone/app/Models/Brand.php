<?php
namespace App\Models;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active'=>'boolean'];
    public function getRouteKeyName() { return 'slug'; }
    public function getImageUrlAttribute() { return Media::url($this->logo, $this->name); }
}