<?php

namespace Nhvv\Api\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;

/**
 * Class IndexRepository
 * @package Nhvv\Api\Repositories
 */
class IndexRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return 'Nhvv\Api\App\Models\Index';
    }
}