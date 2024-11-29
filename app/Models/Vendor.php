<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $guarded = ['id'];
    
    protected $primaryKey = 'id_vendor'; 


}



