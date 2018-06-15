<?php

namespace Adtech\Core\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\Controller as Controller;
use Adtech\Core\App\Models\PublisherCpcSiteDate;
use Illuminate\Support\Facades\DB;
use Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $users = $blogs = [];
//        $request->session()->forget('tab');
        $analytics_error = $pageVisits = $blog_count = $visitors = $user_count = $month_visits = $year_visits = rand(100, 1000);
        return view('ADTECH-CORE::modules.core.dashboard.index',
            compact('analytics_error', 'users', 'blogs', 'pageVisits', 'blog_count', 'visitors', 'user_count', 'month_visits', 'year_visits'));
    }

    public function filemanage()
    {
        return view('ADTECH-CORE::modules.core.file.manage');
    }
}
