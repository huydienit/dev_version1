<?php

namespace Nhvv\Core\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;

/**
 * Class IndexRepository
 * @package Nhvv\Core\Repositories
 */
class IndexRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return 'Nhvv\Core\App\Models\Index';
    }
}