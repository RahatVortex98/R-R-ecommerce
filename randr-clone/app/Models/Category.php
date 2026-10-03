<?php
namespace App\Models;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
    protected $casts = ['is_featured'=>'boolean','show_on_home'=>'boolean','is_active'=>'boolean'];
    public function getRouteKeyName() { return 'slug'; }
    public function parent() { return $this->belongsTo(self::class, 'parent_id'); }
    public function children() { return $this->hasMany(self::class, 'parent_id')->where('is_active', true)->orderBy('sort'); }
    public function products() { return $this->hasMany(Product::class); }
    public function getImageUrlAttribute() { return Media::url($this->image, $this->name); }
    public function familyIds(): array { return $this->children()->pluck('id')->push($this->id)->all(); }
}