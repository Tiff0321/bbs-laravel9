<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * 显示分类下的话题列表
     *
     * @param Category $category
     * @return Application|Factory|View
     */
    public function show(Category $category): View|Factory|Application
    {
        $topics = Topic::where('category_id', $category->id)->paginate(20);
        return view('topics.index', compact('topics', 'category'));
    }
}
