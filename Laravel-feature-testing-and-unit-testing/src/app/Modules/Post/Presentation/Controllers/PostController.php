<?php

namespace App\Modules\Post\Presentation\Controllers;

use App\Modules\Post\Core\Domain\Model\Post;
use App\Modules\Post\Core\Domain\Repository\PostRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class PostController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
  private $postRepository;

  public function __construct(PostRepository $postRepository)
  {
    $this->postRepository = $postRepository;
  }

  public function index()
  {
    $message = __('Post::general.belajar');
    $posts = $this->postRepository->getAll();
    return view('Post::welcome', compact('message', 'posts'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('Post::create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data = $request->validate([
      'title' => 'required',
      'description' => 'required',
    ]);

    $post = new Post();
    $post->title = $data['title'];
    $post->description = $data['description'];
    $post->slug = SlugService::createSlug(Post::class, 'slug', $data['title']);
    $this->postRepository->store($post->toArray());

    return redirect('/post')->with('message', 'Your post has been added!');
  }

  /**
   * Display the specified resource.
   *
   * @param  string  $slug
   * @return \Illuminate\Http\Response
   */
  public function show($slug)
  {
    //$post = Post::where('slug', $slug)->first();
    $post = $this->postRepository->findBySlug($slug);
    return view('Post::show')->with('post', $post);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  string  $slug
   * @return \Illuminate\Http\Response
   */
  public function edit($slug)
  {
    // $post = Post::where('slug', $slug)->first();
    return view('Post::edit')
      ->with('post', $post);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  string  $slug
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $slug)
  {
    // $post = Post::where('slug', $slug)->first();
    $request->validate([
      'title' => 'required',
      'description' => 'required',
      'image' => 'required|mimes:jpg,png,jpeg|max:5048'
    ]);

    $newImageName = uniqid() . '-' . $request->title . '.' . $request->image->extension();

    // $request->image->move(public_path('images'), $newImageName);

    // $post->update([
    //     'title' => $request->input('title'),
    //     'description' => $request->input('description'),
    //     'slug' => SlugService::createSlug(Post::class, 'slug', $request->title),
    //     'image_path' => $newImageName,
    //     'user_id' => auth()->user()->id
    // ]);

    return redirect('/post')->with('message', 'Your post has been updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  string  $slug
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $slug)
  {
    // $post = Post::where('slug', $slug)->first();
    // $post->delete();

    return redirect('/post')->with('message', 'Your post has been deleted');
  }
}
