<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prefecture extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'prefecture_id';

    /**
     * @var array
     */
    protected $guarded = ['prefecture_id'];

    /**
     * Included in toArray() so search results (arrays) can use it too
     *
     * @var array
     */
    protected $appends = ['display_name'];

    /**
     * @return HasMany
     */
    public function hotel(): HasMany
    {
        return $this->hasMany(Hotel::class);
    }

    /**
     * Prefecture name in the current locale: 東京都 (ja) / Tokyo (other)
     *
     * @return Attribute
     */
    protected function displayName(): Attribute
    {
        return Attribute::get(fn () => app()->getLocale() === 'ja'
            ? $this->prefecture_name
            : ucfirst($this->prefecture_name_alpha));
    }
}
