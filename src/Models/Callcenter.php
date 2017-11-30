<?php

namespace Jvleeuwen\Broadsoft\Models;

use Illuminate\Database\Eloquent\Model;

class Callcenter extends Model
{
    public function monitoring()
    {
        return $this->hasOne(
            'Jvleeuwen\Broadsoft\Models\CallcenterMonitoring',
            'targetId',
            'userId'
        );
    }
}
