@extends('front.app')
@section('title', trans('menu.blog'))

@section('content')
          <div class="breadcrumb">
            <div class="container">
              <h2>{{trans('menu.blog')}}</h2>
              <ul><li>{{trans('menu.home')}}</li><li class="active">{{trans('menu.blog')}}</li></ul>
            </div>
          </div>
      <div class="blog">
        <div class="container">
          <div class="row">
            <div class="col-md-3">
              <div class="blog-sidebar">
                <div class="blog-sidebar__section -search">
                  <form>
                    <input type="text" placeholder="Enter keyword" name="search">
                    <button><i class="fas fa-search"></i></button>
                  </form>
                </div>
                <div class="blog-sidebar__section">
                  <h5 class="blog-sidebar__title">{{trans('blog.folowus')}}</h5>
                                <div class="social-icons -border -round -border--light-bg">
                                  <ul>
                                    <li><a href="https://www.facebook.com/" style="'color: undefined'"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="https://twitter.com" style="'color: undefined'"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="https://instagram.com/" style="'color: undefined'"><i class="fab fa-instagram"> </i></a></li>
                                    <li><a href="https://www.youtube.com/" style="'color: undefined'"><i class="fab fa-youtube"></i></a></li>
                                  </ul>
                                </div>
                </div>
             
                <div class="blog-sidebar__section -polular-post">
                  <h5 class="blog-sidebar__title">{{ trans('blog.popular_posts') }}</h5>
                  @if($popularPosts->count() > 0)
                    @foreach($popularPosts as $post)
                      <div class="post-card-three">
                        <div class="post-card-three__image">
                          <img src="{{ asset('images/blogs/'.$post->image) }}" alt="{{ $post->{'title_' . app()->getLocale()} }}">
                        </div>
                        <div class="post-card-three__content">
<a href="{{ route('blog.detail', ['id' => $post->id]) }}">{{ $post->{'title_' . app()->getLocale()} }}</a>
                          <p>{{ $post->created_at->format('Y-m-d') }}</p>
                        </div>
                      </div>
                    @endforeach
                  @else
                    <p>{{ trans('blog.no_popular_posts') }}</p>
                  @endif
                </div>
                
              </div>
            </div>
            <div class="col-md-9">
              <div class="blog-content">
                <div class="row">
                  @if($blogs->count() > 0)
                    @php $firstBlog = $blogs->first(); @endphp
                    <div class="col-12">
                      <div class="post-card-featured">
                        <div class="post-card-featured__image"><img src="{{ asset('images/blogs/'.$firstBlog->image) }}" alt="{{ $firstBlog->{'title_' . app()->getLocale()} }}"></div>
                        <div class="post-card-featured__content">
                          <div class="post-card-featured__content__date">
                            <h5>{{ $firstBlog->created_at->format('d') }}</h5>
                            <p>{{ $firstBlog->created_at->format('M') }}</p>
                          </div>
                          <div class="post-card-featured__content__detail">
                            
<a class="post-card-featured-title" href="{{ route('blog.detail', ['id' => $firstBlog->id]) }}">{{ $firstBlog->{'title_' . app()->getLocale()} }}</a>
                            <p class="post-card-featured-description">{{ Str::limit($firstBlog->{'description_' . app()->getLocale()}, 100) }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    @foreach($blogs->skip(1) as $blog)
                      <div class="col-12 col-md-6">
                        <div class="post-card-two">
                          <div class="post-card-two__image">
                            <img src="{{ asset('images/blogs/'.$blog->image) }}" alt="{{ $blog->{'title_' . app()->getLocale()} }}">
                            <div class="post-card-two__info">
                              <div class="post-card-two__info__date">
                                <h5>{{ $blog->created_at->format('d') }}</h5>
                                <p>{{ $blog->created_at->format('M') }}</p>
                              </div>
                              <div class="post-card-two__info__detail">
                                <p>by <span>Admin</span></p><a href="#">Blog</a>
                              </div>
                            </div>
                          </div>
                          <div class="post-card-two__content">
<a href="{{ route('blog.detail', ['id' => $blog->id]) }}">{{ $blog->{'title_' . app()->getLocale()} }}</a>
                            <p>{{ Str::limit($blog->{'description_' . app()->getLocale()}, 100) }}</p>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  @else
                    <div class="col-12">
                      <p>No blogs available.</p>
                    </div>
                  @endif
                </div>
                @if($blogs->hasPages())
                  <ul class="paginator">
                    {{ $blogs->links() }}
                  </ul>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
     
    
    </div>
@endsection