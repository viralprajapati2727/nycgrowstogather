<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $guarded = [];
    protected $hidden = ["created_by", "created_at", "updated_at"];
}
