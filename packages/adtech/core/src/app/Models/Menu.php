<?php

namespace Adtech\Core\App\Models;

use Illuminate\Database\Eloquent\Model;
use \Adtech\Application\Cms\Libraries\Acl as AdtechAcl;

class Menu extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'adtech_core_menus';

    protected $primaryKey = 'menu_id';

    protected $fillable = ['parent', 'sort', 'name', 'route_name', 'domain_id', 'icon'];

    public function domains()
    {
        return $this->hasMany('Adtech\Core\App\Models\Domain');
    }
}