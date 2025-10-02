<?php
namespace App\Models;

use App\Models\Tag;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function popularTags(){
        $popular = Tag::withCount('products')->orderByDesc('products_count')->limit(10)->get(); // get top 10 tags only, no need for more for now
        return $popular;
    }

    

}
