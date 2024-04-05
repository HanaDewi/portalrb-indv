<?php

namespace App\Http\Controllers\RuangBelajar;

use App\Models\RuangBelajar\Tag;
use App\Models\RuangBelajar\Article;
use App\Models\RuangBelajar\Slider;
use App\Models\RuangBelajar\SocialCount;
use App\Models\RuangBelajar\About;
use App\Models\RuangBelajar\Comment;
use App\Models\RuangBelajar\Contact;
use App\Models\RuangBelajar\Category;
use App\Models\RuangBelajar\SocialLink;
use App\Models\RuangBelajar\Subscriber;
use App\Models\RuangBelajar\RecivedMail;
use App\Models\RuangBelajar\HomeSectionSetting;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;

class DashboardController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        $categories = Category::all();
        $popularArticles = Article::with(['category'])->where('show_at_popular', 1)
            ->orderBy('updated_at', 'DESC')->take(4)->get();
        $recentArticles = Article::with(['category', 'author'])
            ->orderBy('id', 'DESC')->take(6)->get();

        return view('ruang-belajar.dashboard', compact(
            'sliders',
            'categories',
            'popularArticles',
            'recentArticles'
        ));
    }

    public function ShowPraktek(string $slug)
    {
        $article = Article::with(['author', 'tags', 'comments'])->where('slug', $slug)
            //->activeEntries()->withLocalize()
            ->first();


        $this->countView($article);

        $recentArticles = Article::with(['category', 'author'])->where('slug', '!=', $article->slug)
            ->activeEntries()->withLocalize()->orderBy('id', 'DESC')->take(4)->get();

        $mostCommonTags = $this->mostCommonTags();

        $nextPost = Article::where('id', '>', $article->id)
            ->activeEntries()
            ->withLocalize()
            ->orderBy('id', 'asc')->first();

        $previousPost = Article::where('id', '<', $article->id)
            ->activeEntries()
            ->withLocalize()
            ->orderBy('id', 'desc')->first();

        $relatedPosts = Article::where('slug', '!=', $article->slug)
            ->where('category_id', $article->category_id)
            ->activeEntries()
            ->withLocalize()
            ->take(5)
            ->get();

        $socialCounts = SocialCount::where(['status' => 1, 'language' => Lang::locale()])->get();


        return view('ruang-belajar.materi-detail', compact('article', 'recentArticles', 'mostCommonTags', 'nextPost', 'previousPost', 'relatedPosts', 'socialCounts'));
    }

    public function article(Request $request)
    {

        $articles = Article::query();

        $articles->when($request->has('tag'), function ($query) use ($request) {
            $query->whereHas('tags', function ($query) use ($request) {
                $query->where('name', $request->tag);
            });
        });

        $articles->when($request->has('category') && !empty($request->category), function ($query) use ($request) {
            $query->whereHas('category', function ($query) use ($request) {
                $query->where('slug', $request->category);
            });
        });

        $articles->when($request->has('search'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            })->orWhereHas('category', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        });

        $articles = $articles->activeEntries()->withLocalize()->paginate(20);


        $recentArticles = Article::with(['category', 'author'])
            ->activeEntries()->withLocalize()->orderBy('id', 'DESC')->take(4)->get();
        $mostCommonTags = $this->mostCommonTags();

        $categories = Category::where(['status' => 1, 'language' => Lang::locale()])->get();



        return view('ruang-belajar.materi', compact('articles', 'recentArticles', 'mostCommonTags', 'categories'));
    }

    public function countView($article)
    {
        if (session()->has('viewed_posts')) {
            $postIds = session('viewed_posts');

            if (!in_array($article->id, $postIds)) {
                $postIds[] = $article->id;
                $article->increment('views');
            }
            session(['viewed_posts' => $postIds]);
        } else {
            session(['viewed_posts' => [$article->id]]);

            $article->increment('views');
        }
    }

    public function mostCommonTags()
    {
        return Tag::select('name', \DB::raw('COUNT(*) as count'))
            ->where('language', Lang::locale())
            ->groupBy('name')
            ->orderByDesc('count')
            ->take(15)
            ->get();
    }

    public function handleComment(Request $request)
    {

        $request->validate([
            'comment' => ['required', 'string', 'max:1000']
        ]);

        $comment = new Comment();
        $comment->article_id = $request->article_id;
        $comment->user_id = Auth::user()->id;
        $comment->parent_id = $request->parent_id;
        $comment->comment = $request->comment;
        $comment->save();
        toast(__('frontend.Comment added successfully!'), 'success');
        return redirect()->back();
    }

    public function handleReplay(Request $request)
    {

        $request->validate([
            'replay' => ['required', 'string', 'max:1000']
        ]);

        $comment = new Comment();
        $comment->article_id = $request->article_id;
        $comment->user_id = Auth::user()->id;
        $comment->parent_id = $request->parent_id;
        $comment->comment = $request->replay;
        $comment->save();
        toast(__('frontend.Comment added successfully!'), 'success');

        return redirect()->back();
    }

    public function commentDestory(Request $request)
    {
        $comment = Comment::findOrFail($request->id);
        if (Auth::user()->id === $comment->user_id) {
            $comment->delete();
            return response(['status' => 'success', 'message' => __('frontend.Deleted Successfully!')]);
        }

        return response(['status' => 'error', 'message' => __('frontend.Someting went wrong!')]);
    }

    public function SubscribeArticleLetter(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:subscribers,email']
        ], [
            'email.unique' => __('frontend.Email is already subscribed!')
        ]);

        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();

        return response(['status' => 'success', 'message' => __('frontend.Subscribed successfully!')]);
    }

    public function about()
    {
        $about = About::where('language', Lang::locale())->first();
        return view('frontend.about', compact('about'));
    }

    public function contact()
    {
        $contact = Contact::where('language', Lang::locale())->first();
        $socials = SocialLink::where('status', 1)->get();
        return view('frontend.contact', compact('contact', 'socials'));
    }

    public function handleContactFrom(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'max:255'],
            'message' => ['required', 'max:500']
        ]);

        try {
            $toMail = Contact::where('language', 'en')->first();

            /** Send Mail */
            Mail::to($toMail->email)->send(new ContactMail($request->subject, $request->message, $request->email));

            /** store the mail */

            $mail = new RecivedMail();
            $mail->email = $request->email;
            $mail->subject = $request->subject;
            $mail->message = $request->message;
            $mail->save();
        } catch (\Exception $e) {
            toast(__($e->getMessage()));
        }

        toast(__('frontend.Message sent successfully!'), 'success');

        return redirect()->back();
    }
}
