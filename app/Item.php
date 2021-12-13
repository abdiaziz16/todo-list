<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The table name for this model
     *
     * @var string
     */
    protected $table = 'item';

    /**
     * The table name for this model
     *
     * @var int
     */

    protected $primaryKey = 'id';
    public $timestamps = false;

}
