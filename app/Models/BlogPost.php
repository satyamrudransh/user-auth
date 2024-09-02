<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use SoftDeletes;

    protected $table='blog_posts';
    protected $primaryKey = 'id';

    protected $date = ['deleted_at'];

    protected $fillable = [
        'blogName','description','blogStatus'
    ];

}
