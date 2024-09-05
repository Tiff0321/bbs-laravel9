<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function root(): Factory|View|Application
    {
        return view('pages.root');
    }


    /**
     * 权限不足页面
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|RedirectResponse|Redirector
     */
    public function permissionDenied(): View|Factory|Redirector|RedirectResponse|Application
    {
        // 如果当前用户有权限访问后台，直接跳转访问
        if (config('administrator.permission')()) {
            return redirect(url(config('administrator.uri')), 302);
        }

        // 否则使用视图，没有权限的话
        return view('pages.permission_denied');
    }
}
