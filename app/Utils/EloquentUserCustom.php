<?php


namespace App\Utils;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EloquentUserCustom extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $values) {
            if (! Str::contains($key, 'password')) {
                if (is_array($values)) {
                    $operator = $values[0];
                    $value = $values[1];
                    $query->where($key, $operator, $value);
                } else {
                    $query->where($key, $values);
                }
            }
        }

        return $query->first();
    }
}
