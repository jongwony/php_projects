<?php
namespace Festiv\Publ\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /** @var array */
    protected $fillable = [
        'name',
        'email',
        'password',
        'grant',
    ];

    /** @var array */
    protected $casts = [
        'id' => 'integer',
        'grant' => 'integer',
    ];

    /** @var array */
    protected $hidden = [
        'password',
    ];
}
