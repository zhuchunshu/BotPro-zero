<?php
namespace App\Plugins\zero\src\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class ZeroUsers extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'zero_user';
    public $timestamps = true;

}