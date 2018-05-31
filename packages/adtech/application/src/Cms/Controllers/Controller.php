<?php

namespace Adtech\Application\Cms\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Adtech\Core\App\Models\Domain;
use Auth;

class Controller extends BaseController
{
    use ValidatesRequests;
    protected $user;
    protected $currentDomain;

    public function __construct()
    {
        //
        $id = Auth::id();
        $this->user = Auth::user();
        $email = $this->user ? $this->user->email : null;
        $host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null;
        $domain_id = 0;
        if ($host) {
            $domain = Domain::where('name', $host)->first();
            $this->currentDomain = $domain;
            $domain_id = $domain->domain_id;
        }
        $share = [
            'USER_LOGGED' => $this->user,
            'USER_LOGGED_EMAIL' => $email,
            'USER_LOGGED_ID' => $id,
            'DOMAIN_ID' => $domain_id,
            'group_name'  => config('site.group_name'),
            'template'  => config('site.desktop.template'),
            'skin'  => config('site.desktop.skin'),
            'mtemplate'  => config('site.mobile.template'),
            'mskin'  => config('site.mobile.skin'),
        ];

        view()->share($share);
    }
}
