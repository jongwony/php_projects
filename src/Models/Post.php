<?php
namespace Festiv\Publ\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @var array */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'writer',
        'password',
        'contents',
        'options',
    ];

    /** @var array */
    protected $hidden = [
        'password',
    ];

    /** @var array */
    protected $casts = [
        'user_id' => 'int',
        'category_id' => 'int',
        'options' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
