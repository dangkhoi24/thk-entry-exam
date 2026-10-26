<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasContainsScope
{
    /**
     * Partial match on a column, "%" and "_" in the keyword are searched literally.
     * A null keyword adds no condition.
     */
    public function scopeWhereContains(Builder $query, string $column, ?string $keyword): void
    {
        if ($keyword === null) {
            return;
        }

        $query->where($column, 'like', '%'.addcslashes($keyword, '\\%_').'%');
    }
}
