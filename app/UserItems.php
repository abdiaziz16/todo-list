<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserItems extends Model
{
    /**
     * The table name for this model
     *
     * @var string
     */
    protected $table = 'useritems';

    /**
     * The table name for this model
     *
     * @var int
     */

    protected $primaryKey = 'id';
    public $timestamps = false;

}
