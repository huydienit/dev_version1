<?php

namespace Test1\Test1m1\App\Repositories;

use Adtech\Application\Cms\Repositories\Eloquent\Repository;

/**
 * Class IndexRepository
 * @package Test1\Test1m1\Repositories
 */
class IndexRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return 'Test1\Test1m1\App\Models\Index';
    }
}