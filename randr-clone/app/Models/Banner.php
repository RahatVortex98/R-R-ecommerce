<?php
namespace App\Models;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active'=>'boolean'];
    public function getImageUrlAttribute() { return Media::url($this->image, 'B'); }
}