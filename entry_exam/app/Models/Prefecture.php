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

    public function hotel(): HasMany
    {
        return $this->hasMany(Hotel::class, 'prefecture_id', 'prefecture_id');
    }

    /**
     * Prefecture name in the current locale: 東京都 (ja) / Tokyo (other)
     */
    protected function displayName(): Attribute
    {
        return Attribute::get(fn () => app()->getLocale() === 'ja'
            ? $this->prefecture_name
            : ucfirst($this->prefecture_name_alpha));
    }
}
