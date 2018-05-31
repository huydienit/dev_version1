<?php

namespace Adtech\Core\App\Models;

use Illuminate\Database\Eloquent\Model;
use \Adtech\Application\Cms\Libraries\Acl as AdtechAcl;

class Package extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'adtech_core_packages';

    protected $primaryKey = 'package_id';

    protected $fillable = ['package', 'module', 'space'];

    public function domains()
    {
        return $this->belongsToMany('Adtech\Core\App\Models\Domain', 'adtech_core_domains_package', 'package_id', 'domain_id')->withPivot('status');
    }
}