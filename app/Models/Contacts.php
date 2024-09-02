<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacts extends Model
{
    use SoftDeletes;

    protected $table = 'contacts';
    protected $primaryKey = 'id';

    protected $date = ['deleted_at'];

    protected $fillable = [
        'name',
        'description',
        'email',
        'number',
        'other',
        'status'
    ];
}
