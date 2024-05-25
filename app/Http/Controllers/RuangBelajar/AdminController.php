<?php

namespace App\Http\Controllers\RuangBelajar;

use App\Http\Controllers\Controller;
use App\Models\RuangBelajar\Category;
use App\Models\RuangBelajar\Language;
use App\Models\RuangBelajar\Article;
use App\Models\RuangBelajar\SocialLink;
use App\Models\RuangBelajar\Subscriber;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use \Spatie\Permission\Models\Permission;
use \Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index(): View
    {
        $publishedArticle = Article::where(['status' => 1, 'is_approved' => 1])->count();
        $pendingArticle = Article::where(['status' => 1, 'is_approved' => 0])->count();
        $categories = Category::count();
        $languages = Language::count();
        //$roles = Role::count();
        //$permissions = Permission::count();
        $socials = SocialLink::count();
        $subscribers = Subscriber::count();

        return view('ruang-belajar.admin.admin-dashboard', compact('publishedArticle', 'pendingArticle', 'categories', 'languages',  'socials', 'subscribers'));
    }

    public function Category(): View
    {
        $categories = Category::all();

        return view('ruang-belajar.admin.category', compact('categories'));
    }

    public function category_edit(string $id)
    {
        //$languages = Language::all();
        $category = Category::findOrFail($id);
        return view('ruang-belajar.admin.category_edit', compact('category'));
    }

    public function artikel(): View
    {
        $articles = Article::where(['status' => 1, 'is_approved' => 1])->get();

        return view('ruang-belajar.admin.article', compact('articles'));
    }

    public function artikel_pending(): View
    {
        $articles = Article::where(['status' => 1, 'is_approved' => 0])->get();

        return view('ruang-belajar.admin.article_pending', compact('articles'));
    }

    public function social_media(): View
    {
        $socialMedias = SocialLink::all();

        return view('ruang-belajar.admin.social_media', compact('socialMedias'));
    }

    public function subscriber(): View
    {
        $subscribers = Subscriber::all();

        return view('ruang-belajar.admin.subscriber', compact('subscribers'));
    }
}
