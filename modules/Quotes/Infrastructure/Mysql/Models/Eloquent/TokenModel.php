<?php

namespace Avrillo\Quotes\Infrastructure\Mysql\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class TokenModel extends Model
{
    protected $table = 'tokens';
    protected $fillable = ['token', 'expires_at'];
}
