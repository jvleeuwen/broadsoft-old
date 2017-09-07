<?php

namespace jvleeuwen\broadsoft\Database\Models;

use Illuminate\Database\Eloquent\Model;

class bsUserAssignedCallcenter extends Model
{
    public function bsUser()
    {
        return $this->hasOne('jvleeuwen\broadsoft\Database\Models\bsUser','userId', 'userId');
    }
}
