<?php

namespace Nhvv\Api\App\Models;

use Illuminate\Database\Eloquent\Model;

class Index extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'table_name';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    protected $fillable = ['name'];
}