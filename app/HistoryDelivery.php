<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class HistoryDelivery extends Model {
    
    protected $table = 'historyDelivery';
    
    public $timestamps = false;
    
    protected $fillable = [
        'deal', 'status', 'user_original', 'value_old', 'value_new', 'created_at'
    ];
    
    protected $casts = ['value_old' => 'array', 'value_new' => 'array'];
    
}
