<?php

namespace Adtech\Application\Cms\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Collection;
use Adtech\Core\App\Models\Domain;
use Adtech\Core\App\Models\Menu;
use Session;
use Auth;

class Controller extends BaseController
{
    use ValidatesRequests;
    protected $user;
    protected $currentDomain;
    protected $_menuList;
    protected $_menuTop;
    protected $domainDefault;

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
            if (null != $domain) {
                $this->currentDomain = $domain;
                $domain_id = $domain->domain_id;
            }
        }
        $this->domainDefault = $domain_id;
        if(isset($_GET["domain_id"])) {
            $domain_id = $_GET["domain_id"];
        }
        self::getMenu($domain_id);
        $arrColor = ['#4089C7', '#00BB8D', '#58BEDC', '#F99928', '#F06E6B', '#A7B4BA'];

        if (null != $this->_menuList) {
            if (count($this->_menuList) > 0) {
                $tab = (session()->has('tab')) ? session('tab') : '';
                $checkGroup = 1;
                foreach ($this->_menuList as $key => $item) {
                    if ($item->parent == 0 && $tab != '') {
                        if ($item->group != $tab) {
                            $checkGroup = 0;
                            $this->_menuList->forget($key);
                        } else {
                            $checkGroup = 1;
                        }
                    } elseif ($tab != '' && $checkGroup == 0) {
                        $this->_menuList->forget($key);
                    }
                }
            }
        }

        $share = [
            'USER_LOGGED' => $this->user,
            'USER_LOGGED_EMAIL' => $email,
            'USER_LOGGED_ID' => $id,
            'DOMAIN_ID' => $domain_id,
            'MENU_LEFT' => $this->_menuList,
            'MENU_TOP' => $this->_menuTop,
            'COLOR_LIST' => $arrColor,
            'group_name'  => config('site.group_name'),
            'template'  => config('site.desktop.template'),
            'skin'  => config('site.desktop.skin'),
            'mtemplate'  => config('site.mobile.template'),
            'mskin'  => config('site.mobile.skin'),
        ];

        view()->share($share);
    }

    function getMenu($domain_id = 0) {
        $menusGroups = Menu::select('group')->where('group', '!=', '')->distinct()->get();
        $this->_menuTop = $menusGroups;

        $menus = Menu::where('domain_id', $domain_id)->orderBy('parent')->orderBy('sort')->get();
        if (count($menus) > 0) {
            foreach ($menus as $menu) {

                $parent_id = $menu->parent;
                $menu_id = $menu->menu_id;

                $menuData['items'][$menu_id] = $menu;
                $menuData['parents'][$parent_id][] = $menu_id;
            }
            $this->_menuList = new Collection();
            self::buildMenu(0, $menuData);
        }
    }

    function buildMenu($parentId, $menuData)
    {
        if (isset($menuData['parents'][$parentId]))
        {
            foreach ($menuData['parents'][$parentId] as $itemId)
            {
                $item = $menuData['items'][$itemId];
                $item->level = 1;
                if ($parentId == 0)
                    $item->level = 0;
                else
                    $item->level = $menuData['items'][$parentId]->level + 1;
                $this->_menuList->push($item);

                // find childitems recursively
                $more = self::buildMenu($itemId, $menuData);
                if (!empty($more))
                    $this->_menuList->push($more);
            }
        }
    }
}
