<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{     //$table,$primaryKey,$timestabes (must call that)
    protected $table = 'posts';
    public $primaryKey = 'id'; 
    public $timestabes =true; 

    public function userAnyname(){

    // return $this->belongsTo('App\User');
   
        return $this->belongsTo('App\User', 'user_id'); 
    
        
      
    }
}
