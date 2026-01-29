@extends('front.app')
@section('title', app()->getLocale() == 'ar' ? ($product->name ?? __('products.unknown_product')) : ($product->namee ?? $product->name ?? __('products.unknown_product')))

@section('content')
<div class="breadcrumb">
    <div class="container">
        <h2>{{ __('products.products') }}</h2>
        <ul>
            <li><a href="{{ route('/') }}">{{ __('products.home') }}</a></li>
            <li><a href="{{ route('all_products') }}">{{ __('products.products') }}</a></li>
            <li class="active">
                {{ app()->getLocale() == 'ar' 
                    ? ($product->name ?? __('products.unknown_product')) 
                    : ($product->namee ?? $product->name ?? __('products.unknown_product')) }}
            </li>
        </ul>
    </div>
</div>

<div class="shop">
    <div class="container">
        <div class="product-detail__wrapper">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="product-detail__slide-two">
                        <div class="product-detail__slide-two__big slick-slider">
                            {{-- Main product image first --}}
                            @if($product->image)
                                <div class="slider__item">
                                    <img src="{{ asset('images/product/'.rawurlencode($product->image)) }}" alt="{{ app()->getLocale() == 'ar' ? ($product->name ?? '') : ($product->namee ?? $product->name ?? '') }}"/>
                                </div>
                            @endif
                            
                            {{-- Additional images from imagesfiles table --}}
                            @if(isset($imagesfiles) && count($imagesfiles) > 0)
                                @foreach($imagesfiles as $image)
                                    <div class="slider__item">
                                        <img src="{{ asset('images/product/'.rawurlencode($image->name)) }}" alt="{{ app()->getLocale() == 'ar' ? ($product->name ?? '') : ($product->namee ?? $product->name ?? '') }}"/>
                                    </div>
                                @endforeach
                            @endif
                            
                            {{-- Default image if no images available --}}
                            @if(!$product->image && (!isset($imagesfiles) || count($imagesfiles) == 0))
                                <div class="slider__item">
                                    <img src="{{ asset('images/product/default-product.jpg') }}" alt="Default product image"/>
                                </div>
                            @endif
                        </div>
                        <div class="product-detail__slide-two__small slick-slider">
                            {{-- Main product image thumbnail first --}}
                            @if($product->image)
                                <div class="slider__item">
                                    <img src="{{ asset('images/product/'.rawurlencode($product->image)) }}" alt="Product thumbnail"/>
                                </div>
                            @endif
                            
                            {{-- Additional image thumbnails from imagesfiles table --}}
                            @if(isset($imagesfiles) && count($imagesfiles) > 0)
                                @foreach($imagesfiles as $image)
                                    <div class="slider__item">
                                        <img src="{{ asset('images/product/'.rawurlencode($image->name)) }}" alt="Product thumbnail"/>
                                    </div>
                                @endforeach
                            @endif
                            
                            {{-- Default thumbnail if no images available --}}
                            @if(!$product->image && (!isset($imagesfiles) || count($imagesfiles) == 0))
                                <div class="slider__item">
                                    <img src="{{ asset('images/product/default-product.jpg') }}" alt="Default product image"/>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="product-detail__content">
                        <div class="product-detail__content__header">
                            <h5>{{ app()->getLocale() == 'ar' 
                                ? ($product->categories->name ?? __('products.unknown_category')) 
                                : ($product->categories->englishname ?? $product->categories->name ?? __('products.unknown_category')) }}</h5>
                            <h2>{{ app()->getLocale() == 'ar' 
                                ? ($product->name ?? __('products.unknown_product')) 
                                : ($product->namee ?? $product->name ?? __('products.unknown_product')) }}</h2>
                        </div>
                        <div class="product-detail__content__header__comment-block">
                            <div class="rate">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($averageRating))
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p>{{ $reviewCount }} {{ __('products.reviews_count') ?? 'reviews' }}</p>
                            @auth('customer')
                                <a href="#reviews-section" onclick="openReviewSwal({{ $product->id }}); return false;">{{ __('products.write_review') ?? 'Write a review' }}</a>
                            @else
                                <a href="{{ route('customer.login') }}">{{ __('products.login_to_review') ?? 'Login to write a review' }}</a>
                            @endauth
                        </div>
                        
                        <h3>
                            @if($discountedPrice)
                                <span style="text-decoration: line-through; color: #999; font-size: 0.8em;">{{ number_format($product->price, 2) }} {{ __('products.lyd') }}</span>
                                <br>
                                <span style="color: #e74c3c;">{{ number_format($discountedPrice, 2) }} {{ __('products.lyd') }}</span>
                                @if($discount)
                                    <small style="background: #e74c3c; color: white; padding: 2px 8px; border-radius: 4px; margin-left: 10px;">
                                        -{{ $discount->percentage }}%
                                    </small>
                                @endif
                            @else
                                {{ number_format($product->price, 2) }} {{ __('products.lyd') }}
                            @endif
                        </h3>
                        
                        <div class="divider"></div>
                        
                        <div class="product-detail__content__footer">
                            <ul>
                                <li>{{ __('products.brand') }}:
                                    {{ app()->getLocale() == 'ar' 
                                        ? ($product->brandname_ar ?? $product->material ?? __('products.not_available')) 
                                        : ($product->brandname_en ?? $product->material ?? __('products.not_available')) }}
                                </li>
                                <li>{{ __('products.sku') }}: {{ $product->barcode ?? __('products.not_available') }}</li>
                                <li>{{ __('products.availability') ?? 'Availability' }}: 
                                    <span style="color: {{ $product->is_available ? 'green' : 'red' }}">
                                        {{ $product->is_available ? __('products.available_text') : __('products.unavailable_text') }}
                                    </span>
                                </li>
                                @php
                                    $totalStock = $product->stocks->sum('quantty');
                                @endphp
                                @if($totalStock <= 0)
                                    <li style="color: #f11; font-weight: bold;">
                                        <i class="fas fa-times-circle"></i>
                                        {{ app()->getLocale() == 'ar' ? 'نفذت الكمية' : 'Out of Stock' }}
                                    </li>
                                @elseif($totalStock <= 2)
                                    <li style="color: #ff416c; font-weight: bold; font-size: 1.1em;">
                                        <i class="fas fa-fire"></i>
                                        {{ app()->getLocale() == 'ar' 
                                            ? 'حرج! المتبقى فقط ' . $totalStock . ' قطعة!' 
                                            : 'Hurry! Only ' . $totalStock . ' left!' }}
                                    </li>
                                @elseif($totalStock <= 4)
                                    <li style="color: #f7971e; font-weight: bold;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ app()->getLocale() == 'ar' 
                                            ? 'المتبقى فقط ' . $totalStock . ' قطعة!' 
                                            : 'Only ' . $totalStock . ' left!' }}
                                    </li>
                                @endif
                                @if(app()->getLocale() == 'ar' && $product->country_of_origin_ar)
                                    <li>{{ __('products.country_of_origin') ?? 'Country of Origin' }}: {{ $product->country_of_origin_ar }}</li>
                                @elseif(app()->getLocale() == 'en' && $product->country_of_origin_en)
                                    <li>{{ __('products.country_of_origin') ?? 'Country of Origin' }}: {{ $product->country_of_origin_en }}</li>
                                @endif
                            </ul>
                            
                            @if($product->stocks && count($product->stocks) > 0)
                                @php
                                    $availableColors = $product->stocks->pluck('coolors')->filter()->unique();
                                    $availableSizes = $product->stocks->pluck('sizes')->filter()->unique();
                                @endphp
                                
                                @if($availableColors && count($availableColors) > 0)
                                    <div class="product-detail__colors">
                                        <span>{{ __('products.color') ?? 'Color' }}:</span>
                                        @foreach($availableColors as $color)
                                            <div class="product-detail__colors__item" style="background-color: {{ $color->name ?? '#000' }}"></div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @if($availableSizes && count($availableSizes) > 0)
                                    <div class="product-detail__sizes">
                                        <span>{{ __('products.size') ?? 'Size' }}:</span>
                                        @foreach($availableSizes as $size)
                                            <span class="size-option">{{ $size->name ?? __('products.unknown_size') }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                            
                            <div class="product-detail__controller">
                                <div class="quantity-controller -border -round">
                                    <div class="quantity-controller__btn -descrease">-</div>
                                    <div class="quantity-controller__number">1</div>
                                    <div class="quantity-controller__btn -increase">+</div>
                                </div>
                                <div class="add-to-cart -dark">
                                    <button class="btn -round -red" onclick="addToCart({{ $product->id }})" id="addToCartBtn">
                                        <i class="fas fa-shopping-bag"></i>
                                        {{ __('products.add_to_cart') }}
                                    </button>
                                </div>
                                <div class="product-detail__controler__actions">
                                    <a class="btn -round -white" href="#"><i class="fas fa-heart"></i></a>
                                </div>
                            </div>
                            
                            <div class="divider"></div>
                            
                            <div class="product-detail__content__description">
                                <h5>{{ __('products.description') ?? 'Description' }}</h5>
                                <p>{{ app()->getLocale() == 'ar' 
                                    ? ($product->description_ar ?? $product->description ?? __('products.no_description_available')) 
                                    : ($product->description_en ?? $product->description ?? __('products.no_description_available')) }}</p>
                            </div>
                            
                            <div class="divider"></div>
                            
                            <!-- Reviews Section -->
                            <div class="product-detail__content__reviews" id="reviews-section">
                                <h5>{{ __('reviews.customer_reviews') }} ({{ $reviewCount }})</h5>
                                
                                @if($reviews && count($reviews) > 0)
                                    <div class="reviews-summary">
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <div class="overall-rating">
                                                    <h2>{{ $averageRating }}</h2>
                                                    <div class="rate">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= round($averageRating))
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <p>{{ $reviewCount }} {{ __('products.total_reviews') ?? 'total reviews' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-8">
                                                <div class="rating-breakdown">
                                                    @for($rating = 5; $rating >= 1; $rating--)
                                                        <div class="rating-bar">
                                                            <span>{{ $rating }} {{ __('products.star') ?? 'star' }}</span>
                                                            <div class="progress-bar">
                                                                @php
                                                                    $percentage = $reviewCount > 0 ? ($ratingDistribution[$rating] / $reviewCount) * 100 : 0;
                                                                @endphp
                                                                <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                                                            </div>
                                                            <span>{{ $ratingDistribution[$rating] }}</span>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endif

                                <div class="reviews-list">
                                    @forelse($reviews as $review)
                                        <div class="review" id="review-{{ $review->id }}">
                                            <div class="review__header">
                                                <div class="review__header__avatar">
                                                    <img src="https://via.placeholder.com/60x60/4a90e2/ffffff?text={{ strtoupper(substr($review->customer->name ?? 'U', 0, 1)) }}" alt="{{ $review->customer->name ?? __('reviews.anonymous') }}"/>
                                                </div>
                                                <div class="review__header__info">
                                                    <h5>{{ $review->customer->name ?? __('reviews.anonymous') }}</h5>
                                                    <p>{{ $review->created_at->format('M d, Y') }}</p>
                                                    @if($review->is_verified_purchase)
                                                        <span class="verified-badge">{{ __('reviews.verified_purchase') }}</span>
                                                    @endif
                                                </div>
                                                <div class="review__header__rate">
                                                    <div class="rate">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    @auth('customer')
                                                        @if(Auth::guard('customer')->id() == $review->customer_id)
                                                            <div class="review-actions">
                                                                <button class="btn-edit-review" onclick="toggleEditForm({{ $review->id }})">
                                                                    <i class="fas fa-edit"></i> {{ __('reviews.edit_review') }}
                                                                </button>
                                                                <button class="btn-delete-review" onclick="confirmDeleteReview({{ $review->id }})">
                                                                    <i class="fas fa-trash"></i> {{ __('reviews.delete_review') }}
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </div>
                                            
                                            <!-- Review Content (display mode) -->
                                            <div id="review-content-{{ $review->id }}">
                                                <p class="review__content">{{ $review->comment }}</p>
                                            </div>
                                            
                                            <!-- Edit Form (hidden by default) -->
                                            <div id="review-edit-form-{{ $review->id }}" style="display: none;">
                                                <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    
                                                    <div class="form-group">
                                                        <label>{{ __('products.your_rating') }} <span class="text-danger">*</span></label>
                                                        <div class="rating-input">
                                                            @for($i = 5; $i >= 1; $i--)
                                                                <input type="radio" id="edit-rating-{{ $review->id }}-{{ $i }}" name="rating" value="{{ $i }}" {{ $review->rating == $i ? 'checked' : '' }} required>
                                                                <label for="edit-rating-{{ $review->id }}-{{ $i }}">
                                                                    <i class="fas fa-star"></i>
                                                                </label>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label>{{ __('products.your_review') }} <span class="text-danger">*</span></label>
                                                        <textarea name="comment" class="form-control" rows="4" required>{{ $review->comment }}</textarea>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <button type="submit" class="btn -dark btn-sm">
                                                            <i class="fas fa-save"></i> {{ __('products.save') ?? 'Save' }}
                                                        </button>
                                                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEditForm({{ $review->id }})">
                                                            {{ __('products.cancel') ?? 'Cancel' }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            
                                            <!-- Delete Form (hidden, used for confirmation) -->
                                            <form id="delete-form-{{ $review->id }}" action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                                    
                                                    <!-- Reply Section -->
                                                    @if($review->replies && count($review->replies) > 0)
                                                        <div class="review-replies">
                                                            @foreach($review->replies as $reply)
                                                                <div class="reply">
                                                                    <div class="reply__header">
                                                                        @if($reply->isFromAdmin())
                                                                            <div class="reply__header__avatar admin">
                                                                                <img src="https://via.placeholder.com/40x40/28a745/ffffff?text=A" alt="Admin"/>
                                                                            </div>
                                                                            <div class="reply__header__info">
                                                                                <h6>{{ __('products.admin') ?? 'Store Manager' }}</h6>
                                                                                <p>{{ $reply->created_at->format('M d, Y') }}</p>
                                                                            </div>
                                                                        @else
                                                                            <div class="reply__header__avatar">
                                                                                <img src="https://via.placeholder.com/40x40/6c757d/ffffff?text={{ strtoupper(substr($reply->customer->name ?? 'U', 0, 1)) }}" alt="{{ $reply->customer->name ?? 'User' }}"/>
                                                                            </div>
                                                                            <div class="reply__header__info">
                                                                                <h6>{{ $reply->customer->name ?? __('products.anonymous') }}</h6>
                                                                                <p>{{ $reply->created_at->format('M d, Y') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <p class="reply__content">{{ $reply->comment }}</p>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Reply Form -->
                                                    @auth('customer')
                                                        <div class="reply-form-container">
                                                            <button class="btn-reply-toggle" onclick="toggleReplyForm({{ $review->id }})">
                                                                <i class="fas fa-reply"></i> {{ trans('products.reply') ?? 'Reply' }}
                                                            </button>
                                                            <form action="{{ route('reviews.reply.store') }}" method="POST" id="reply-form-{{ $review->id }}" class="reply-form" style="display: none;">
                                                                @csrf
                                                                <input type="hidden" name="review_id" value="{{ $review->id }}">
                                                                <div class="form-group">
                                                                    <textarea name="comment" class="form-control" rows="3" 
                                                                              placeholder="{{trans('products.enter_your_reply') ?? 'Write your reply...' }}" 
                                                                              required></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn -dark btn-sm">
                                                                        <i class="fas fa-paper-plane"></i> {{ trans('reviews.submit_reply') }}
                                                                    </button>
                                                                    <button type="button" class="btn btn-secondary btn-sm" onclick="toggleReplyForm({{ $review->id }})">
                                                                        {{ trans('reviews.cancel') }}
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    @endauth
                                                </div>
                                            @empty
                                                <div class="no-reviews">
                                                    <p>{{ __('reviews.no_reviews_yet') }}</p>
                                                </div>
                                            @endforelse
                                        </div>

                                        @if($reviews && $reviews->hasPages())
                                            <div class="pagination-wrapper">
                                                {{ $reviews->links() }}
                                            </div>
                                        @endif

                                        @auth('customer')
                                            <hr>
                                            <div class="review-form-section">
                                                <h5>{{ __('reviews.write_review') }}</h5>
                                                <form action="{{ route('reviews.store') }}" method="POST" id="review-form">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    
                                                    <div class="form-group">
                                                        <label>{{ __('reviews.your_rating') }} <span class="text-danger">*</span></label>
                                                        <div class="rating-input">
                                                            @for($i = 5; $i >= 1; $i--)
                                                                <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" required>
                                                                <label for="rating-{{ $i }}">
                                                                    <i class="fas fa-star"></i>
                                                                </label>
                                                            @endfor
                                                        </div>
                                                        @error('rating')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label>{{ __('reviews.your_review') }} <span class="text-danger">*</span></label>
                                                        <textarea name="comment" class="form-control" rows="5" 
                                                                  placeholder="{{ __('reviews.enter_your_review') }}" 
                                                                  required>{{ old('comment') }}</textarea>
                                                        @error('comment')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <button type="submit" class="btn -dark">
                                                            <i class="fas fa-paper-plane"></i>
                                                            {{ __('reviews.submit_review') }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <hr>
                                            <div class="login-prompt">
                                                <p>{{ __('reviews.login_to_write_review') }}</p>
                                                <a href="{{ route('customer.login') }}" class="btn -dark">
                                                    {{ __('reviews.login') }}
                                                </a>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($products && count($products) > 0)
<div class="product-slide" style="direction: ltr;">
    <div class="container">
        <div class="section-title -center" style="margin-bottom: 1.875em">
            <h2>{{ __('products.related_products') }}</h2>
            <p>{{ __('products.related_products_desc') }}</p>
        </div>
        <div class="product-slider">
            <div class="product-slide__wrapper">
                @foreach($products as $relatedProduct)
                <div class="product-slide__item">
                    <div class="product">
                        @if($relatedProduct->discounts && count($relatedProduct->discounts) > 0)
                            <div class="product-type"><h5 class="-sale">-{{ $relatedProduct->discounts->first()->percentage }}%</h5></div>
                        @else
                            <div class="product-type"></div>
                        @endif
                        <div class="product-thumb">
                            <a class="product-thumb__image" href="{{ route('product/info', encrypt($relatedProduct->id)) }}">
                                @if($relatedProduct->image)
                                    <img src="{{ asset('images/product/'.rawurlencode($relatedProduct->image)) }}" alt="{{ app()->getLocale() == 'ar' ? ($relatedProduct->name ?? '') : ($relatedProduct->namee ?? $relatedProduct->name ?? '') }}"/>
                                @else
                                    <img src="{{ asset('images/product/default-product.jpg') }}" alt="Default product image"/>
                                @endif
                            </a>
                            <div class="product-thumb__actions">
                                <div class="product-btn">
                                    <button class="btn -white product__actions__item -round product-atc" onclick="addToCart({{ $relatedProduct->id }})">
                                        <i class="fas fa-shopping-bag"></i>
                                    </button>
                                </div>
                                <div class="product-btn">
                                    <a class="btn -white product__actions__item -round product-qv" href="{{ route('product/info', encrypt($relatedProduct->id)) }}"><i class="fas fa-eye"></i></a>
                                </div>
                                <div class="product-btn">
                                    <a class="btn -white product__actions__item -round" href="#"><i class="fas fa-heart"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="product-content">
                            <div class="product-content__header">
                                <div class="product-category">{{ app()->getLocale() == 'ar' 
                                    ? ($relatedProduct->categories->name ?? __('products.unknown_category')) 
                                    : ($relatedProduct->categories->englishname ?? $relatedProduct->categories->name ?? __('products.unknown_category')) }}</div>
                                <div class="rate">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                            </div>
                            <a class="product-name" href="{{ route('product/info', encrypt($relatedProduct->id)) }}">
                                {{ app()->getLocale() == 'ar' 
                                    ? ($relatedProduct->name ?? __('products.unknown_product')) 
                                    : ($relatedProduct->namee ?? $relatedProduct->name ?? __('products.unknown_product')) }}
                            </a>
                            <div class="product-content__footer">
                                @if($relatedProduct->discounts && count($relatedProduct->discounts) > 0)
                                    @php
                                        $relatedDiscount = $relatedProduct->discounts->first();
                                        $relatedDiscountedPrice = $relatedProduct->price - ($relatedProduct->price * $relatedDiscount->percentage) / 100;
                                    @endphp
                                    <h5 class="product-price--main">{{ number_format($relatedDiscountedPrice, 2) }} {{ __('products.lyd') }}</h5>
                                    <h5 class="product-price--discount">{{ number_format($relatedProduct->price, 2) }} {{ __('products.lyd') }}</h5>
                                @else
                                    <h5 class="product-price--main">{{ number_format($relatedProduct->price, 2) }} {{ __('products.lyd') }}</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<script>
// Ensure jQuery is loaded before initializing sliders
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded. Slick slider requires jQuery.');
        return;
    }
    
    // Handle hash in URL for reviews section
    if (window.location.hash === '#reviews-section') {
        setTimeout(() => {
            const reviewsTab = document.querySelector('.tab-switcher[data-tab-index="2"]');
            if (reviewsTab) {
                reviewsTab.click();
            }
            // Scroll to reviews section
            const reviewsSection = document.querySelector('#reviews-section');
            if (reviewsSection) {
                reviewsSection.scrollIntoView({ behavior: 'smooth' });
            }
        }, 100);
    }
    
    // Tab functionality
    const tabSwitchers = document.querySelectorAll('.tab-switcher');
    const tabContents = document.querySelectorAll('.tab-content__item');
    
    tabSwitchers.forEach(switcher => {
        switcher.addEventListener('click', function() {
            const targetTabIndex = this.getAttribute('data-tab-index');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.style.display = 'none';
            });
            
            // Remove active class from all switchers
            tabSwitchers.forEach(switcher => {
                switcher.classList.remove('active');
            });
            
            // Show target tab content
            const targetContent = document.querySelector(`[data-tab-index="${targetTabIndex}"]`);
            if (targetContent) {
                targetContent.style.display = 'block';
            }
            
            // Add active class to clicked switcher
            this.classList.add('active');
        });
    });
    
    // Quantity controller functionality
    const decreaseBtn = document.querySelector('.quantity-controller__btn.-descrease');
    const increaseBtn = document.querySelector('.quantity-controller__btn.-increase');
    const quantityNumber = document.querySelector('.quantity-controller__number');
    
    if (decreaseBtn && increaseBtn && quantityNumber) {
        let quantity = 1;
        
        decreaseBtn.addEventListener('click', function() {
            if (quantity > 1) {
                quantity--;
                quantityNumber.textContent = quantity;
            }
        });
        
        increaseBtn.addEventListener('click', function() {
            quantity++;
            quantityNumber.textContent = quantity;
        });
    }
    
    // Initialize slick sliders with RTL support
    const isRTL = document.documentElement.getAttribute('dir') === 'rtl';
    
    // Wait for images to load before initializing sliders
    function initializeSliders() {
        // Check if sliders already initialized
        if ($('.product-detail__slide-two__big').hasClass('slick-initialized')) {
            return;
        }
        
        // Initialize big image slider
        $('.product-detail__slide-two__big').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            rtl: isRTL,
            asNavFor: '.product-detail__slide-two__small',
            adaptiveHeight: true
        });
        
        // Initialize small (thumbnail) slider
        $('.product-detail__slide-two__small').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '.product-detail__slide-two__big',
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            rtl: isRTL,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                }
            ],
            // Fix for RTL direction
            cssEase: 'ease-in-out',
            speed: 300
        });
        
        // Fix for RTL transform issue
        if (isRTL) {
            setTimeout(function() {
                const slickTrack = document.querySelector('.product-detail__slide-two__small .slick-track');
                if (slickTrack) {
                    // Force reflow to fix RTL layout
                    slickTrack.style.transform = 'translate3d(0px, 0px, 0px)';
                    
                    // Trigger resize event to recalculate positions
                    $(window).trigger('resize');
                }
            }, 50);
        }
    }
    
    // Initialize sliders when page is ready
    initializeSliders();
    
    // Also initialize when images are loaded
    $('img').on('load', function() {
        initializeSliders();
    });
    
    // Reinitialize on window resize
    $(window).on('resize', function() {
        initializeSliders();
    });
});

// Simple notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 15px 20px;
        border-radius: 5px;
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(400px);
        transition: transform 0.3s ease;
    `;
    
    // Set background color based on type
    switch (type) {
        case 'success':
            notification.style.backgroundColor = '#aa6969';
            break;
        case 'warning':
            notification.style.backgroundColor = '#ffc107';
            notification.style.color = '#212529';
            break;
        case 'error':
            notification.style.backgroundColor = '#dc3545';
            break;
        default:
            notification.style.backgroundColor = '#17a2b8';
    }
    
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Check if item exists in cart
function checkCartItem(productId, color = null, size = null) {
    return fetch('/cart/check-item', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            product_id: productId,
            color: color,
            size: size
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.exists) {
            return {
                exists: true,
                item: data.item
            };
        }
        return { exists: false, item: null };
    })
    .catch(error => {
        console.error('Error checking cart item:', error);
        return { exists: false, item: null };
    });
}

// Add to Cart functionality
function addToCart(productId, quantity = 1) {
    const addToCartBtn = document.getElementById('addToCartBtn') || event.target.closest('button');
    
    // Get quantity from quantity controller if it exists
    const quantityElement = document.querySelector('.quantity-controller__number');
    if (quantityElement) {
        quantity = parseInt(quantityElement.textContent);
    }

    // Show loading state
    if (addToCartBtn) {
        addToCartBtn.disabled = true;
        addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    }

    // Make AJAX request
    fetch('/cart/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show appropriate message based on action
            let message = data.message || '{{ __("products.added_to_cart") }}';
            let notificationType = 'success';
            
            // Check if item was added or quantity was updated
            if (message.includes('quantity_updated') || message.includes('updated')) {
                message = '{{ __("products.quantity_updated_in_cart") }}';
                notificationType = 'info';
            } else if (message.includes('added_to_cart')) {
                notificationType = 'success';
            }
            
            showNotification(message, notificationType);
            
            // Update cart count and UI
            updateCartCount();
            
            // Update button text temporarily to show confirmation
            if (addToCartBtn) {
                addToCartBtn.innerHTML = '<i class="fas fa-check"></i> {{ __("products.added") }}';
                setTimeout(() => {
                    addToCartBtn.innerHTML = '<i class="fas fa-shopping-bag"></i> {{ __("products.add_to_cart") }}';
                }, 1500);
            }
            
            // Reset button
            if (addToCartBtn) {
                addToCartBtn.disabled = false;
            }
        } else {
            // Handle different error types with specific messages
            let errorMessage = data.message || '{{ __("products.error_occurred") }}';
            let notificationType = 'error';
            
            if (errorMessage.includes('must_login_to_order')) {
                errorMessage = '{{ __("app.must_login_to_order") }}';
                notificationType = 'warning';
                showNotification(errorMessage, notificationType);
                
                // Redirect to login page after delay
                setTimeout(() => {
                    window.location.href = '{{ route("customer.login") }}';
                }, 2000);
            } else if (errorMessage.includes('insufficient_stock') || errorMessage.includes('exceeds_stock')) {
                notificationType = 'warning';
                showNotification(errorMessage, notificationType);
            } else {
                showNotification(errorMessage, notificationType);
            }
            
            // Reset button
            if (addToCartBtn) {
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = '<i class="fas fa-shopping-bag"></i> {{ __("products.add_to_cart") }}';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || '{{ __("products.error_occurred") }}', 'error');
        
        // Reset button
        if (addToCartBtn) {
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = '<i class="fas fa-shopping-bag"></i>';
        }
    });
}

// Universal cart count update function
function updateCartCount() {
    fetch('/cart/items/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.count !== undefined) {
            // Update ALL cart counter elements with different class names for compatibility
            const selectors = ['.cart__quantity', '.cart-count', '.cart-quantity', '.cart-badge'];
            
            selectors.forEach(selector => {
                const cartCountElements = document.querySelectorAll(selector);
                cartCountElements.forEach(element => {
                    element.textContent = data.count;
                });
            });
            
            // Trigger custom event for other scripts to listen to
            window.dispatchEvent(new CustomEvent('cartCountUpdated', { 
                detail: { count: data.count } 
            }));
        }
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}

// Review system functionality
document.addEventListener('DOMContentLoaded', function() {
    // Star rating functionality
    const ratingInputs = document.querySelectorAll('.rating-input input[type="radio"]');
    const ratingLabels = document.querySelectorAll('.rating-input label');
    
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Update visual feedback
            const rating = this.value;
            updateStarDisplay(rating);
        });
    });
    
    ratingLabels.forEach(label => {
        label.addEventListener('mouseover', function() {
            const rating = this.getAttribute('for').split('-')[1];
            updateStarDisplay(rating);
        });
        
        label.addEventListener('mouseout', function() {
            // Reset to selected rating or none
            const selectedRating = document.querySelector('.rating-input input:checked')?.value || 0;
            updateStarDisplay(selectedRating);
        });
    });
    
    function updateStarDisplay(rating) {
        ratingLabels.forEach((label, index) => {
            const starValue = 5 - index; // Reverse because of RTL direction
            if (starValue <= rating) {
                label.style.color = '#ffc107';
            } else {
                label.style.color = '#ddd';
            }
        });
    }
    
    // Initialize star display
    const selectedRating = document.querySelector('.rating-input input:checked')?.value || 0;
    updateStarDisplay(selectedRating);
    
    // Review form submission
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            
            // Form will submit normally, but we show loading state
            // The page will reload after submission
        });
    }
    
    // Auto-scroll to reviews section when "Write a review" link is clicked
    const writeReviewLink = document.querySelector('a[href="#reviews-section"]');
    if (writeReviewLink) {
        writeReviewLink.addEventListener('click', function(e) {
            e.preventDefault();
            const reviewsSection = document.querySelector('#reviews-section');
            if (reviewsSection) {
                reviewsSection.scrollIntoView({ behavior: 'smooth' });
                
                // Switch to reviews tab
                setTimeout(() => {
                    const reviewsTab = document.querySelector('.tab-switcher[data-tab-index="2"]');
                    if (reviewsTab) {
                        reviewsTab.click();
                    }
                }, 500);
            }
        });
    }
});

// Toggle reply form
function toggleReplyForm(reviewId) {
    const form = document.getElementById('reply-form-' + reviewId);
    const btn = document.querySelector('.btn-reply-toggle');
    
    if (form) {
        if (form.style.display === 'none') {
            form.style.display = 'block';
            if (btn) btn.style.display = 'none';
        } else {
            form.style.display = 'none';
            if (btn) btn.style.display = 'inline-flex';
        }
    }
}

// Toggle edit form for reviews
function toggleEditForm(reviewId) {
    const contentDiv = document.getElementById('review-content-' + reviewId);
    const formDiv = document.getElementById('review-edit-form-' + reviewId);
    
    if (contentDiv && formDiv) {
        if (formDiv.style.display === 'none') {
            // Show edit form, hide content
            formDiv.style.display = 'block';
            contentDiv.style.display = 'none';
        } else {
            // Show content, hide edit form
            formDiv.style.display = 'none';
            contentDiv.style.display = 'block';
        }
    }
}

// Confirm delete review - SweetAlert popup with fallback
function confirmDeleteReview(reviewId) {
    // Check if SweetAlert2 is loaded
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '{{ __("reviews.confirm_delete_review") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __("reviews.delete") }}',
            cancelButtonText: '{{ __("reviews.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form-' + reviewId);
                if (form) {
                    form.submit();
                }
            }
        });
    } else {
        // Fallback to browser confirm dialog
        if (confirm('{{ __("reviews.confirm_delete_review") }}')) {
            const form = document.getElementById('delete-form-' + reviewId);
            if (form) {
                form.submit();
            }
        }
    }
}

// Switch to reviews tab
function switchToReviewsTab() {
    const reviewsTab = document.querySelector('.tab-switcher[data-tab-index="2"]');
    if (reviewsTab) {
        reviewsTab.click();
    }
}

// Open SweetAlert for writing a review
function openReviewSwal(productId) {
    // Check if SweetAlert2 is loaded
    if (typeof Swal === 'undefined') {
        // Fallback: switch to reviews tab
        switchToReviewsTab();
        setTimeout(() => {
            const reviewForm = document.querySelector('.review-form-section form');
            if (reviewForm) {
                reviewForm.scrollIntoView({ behavior: 'smooth' });
            }
        }, 300);
        return;
    }
    
    // Generate star rating HTML dynamically
    let starsHtml = '';
    for (let i = 5; i >= 1; i--) {
        starsHtml += `
            <input type="radio" id="swal-rating-${i}" name="rating" value="${i}" required>
            <label for="swal-rating-${i}" class="swal-star" data-rating="${i}">
                <i class="fas fa-star"></i>
            </label>
        `;
    }
    
    Swal.fire({
        title: '{{ __("reviews.write_review") }}',
        html: `
            <form id="swal-review-form" action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="${productId}">
                
                <div class="swal-rating-container">
                    <label>{{ __('reviews.your_rating') }} <span class="text-danger">*</span></label>
                    <div class="swal-rating-input">
                        ${starsHtml}
                    </div>
                    <div class="swal-rating-error" style="color: #dc3545; font-size: 12px; display: none; margin-top: 5px;">
                        {{ __('reviews.rating_required') }}
                    </div>
                </div>
                
                <div class="swal-form-group">
                    <label>{{ __('reviews.your_review') }} <span class="text-danger">*</span></label>
                    <textarea name="comment" class="swal-textarea" rows="4" 
                              placeholder="{{ __('reviews.enter_your_review') }}" required></textarea>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: '{{ __('reviews.submit_review') }}',
        cancelButtonText: '{{ __('reviews.cancel') }}',
        focusConfirm: false,
        didOpen: () => {
            // Initialize star rating for SweetAlert
            const starLabels = document.querySelectorAll('.swal-star');
            
            // Hover effect
            starLabels.forEach((label) => {
                label.addEventListener('mouseover', function() {
                    const rating = this.getAttribute('data-rating');
                    updateSwalStars(rating);
                });
                
                label.addEventListener('mouseout', function() {
                    const selectedRating = document.querySelector('.swal-rating-input input:checked')?.value || 0;
                    updateSwalStars(selectedRating);
                });
                
                label.addEventListener('click', function() {
                    document.querySelector('.swal-rating-error').style.display = 'none';
                });
            });
            
            function updateSwalStars(rating) {
                starLabels.forEach((label) => {
                    const starValue = parseInt(label.getAttribute('data-rating'));
                    if (starValue <= rating) {
                        label.querySelector('i').style.color = '#ffc107';
                    } else {
                        label.querySelector('i').style.color = '#ddd';
                    }
                });
            }
            
            // Initialize with no stars selected
            updateSwalStars(0);
        },
        preConfirm: () => {
            const rating = document.querySelector('.swal-rating-input input:checked')?.value;
            const comment = document.querySelector('.swal-textarea').value;
            
            if (!rating) {
                document.querySelector('.swal-rating-error').style.display = 'block';
                return false;
            }
            
            if (comment.length < 10) {
                Swal.showValidationMessage('{{ __("reviews.comment_min_length") }}');
                return false;
            }
            
            return {
                rating: rating,
                comment: comment
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('swal-review-form');
            if (form) {
                form.submit();
            }
        }
    });
}
</script>

<style>
.add-to-cart .btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
}

/* Slick slider custom styles for product detail */
.product-detail__slide-two__big .slick-slide {
    display: flex;
    justify-content: center;
    align-items: center;
}

.product-detail__slide-two__big .slick-slide img {
    max-width: 100%;
    max-height: 500px;
    width: auto;
    height: auto;
}

.product-detail__slide-two__small .slick-slide {
    padding: 5px;
    cursor: pointer;
}

.product-detail__slide-two__small .slick-slide img {
    width: 100%;
    height: auto;
    border: 2px solid transparent;
}

.product-detail__slide-two__small .slick-slide.slick-current img {
    border: 2px solid #aa6969;
}

/* RTL specific styles */
body[dir="rtl"] .product-detail__slide-two__small .slick-slide {
    float: right;
}

body[dir="rtl"] .product-detail__slide-two__small .slick-track {
    transform: translate3d(0px, 0px, 0px);
}

/* Fix slick slider navigation arrows for RTL */
body[dir="rtl"] .product-detail__slide-two__small .slick-prev {
    left: auto;
    right: 10px;
}

body[dir="rtl"] .product-detail__slide-two__small .slick-next {
    right: auto;
    left: 10px;
}

.add-to-cart .btn i {
    font-size: 16px;
    margin: 0;
}

/* Review System Styles */
.reviews-summary {
    margin-bottom: 30px;
}

.overall-rating {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.overall-rating h2 {
    font-size: 3rem;
    margin: 0;
    color: #333;
}

.overall-rating .rate {
    margin: 10px 0;
}

.rating-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.progress-bar {
    flex: 1;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: #ffc107;
    transition: width 0.3s ease;
}

.rating-breakdown {
    padding: 20px;
}

.verified-badge {
    display: inline-block;
    padding: 2px 8px;
    background: #aa6969;
    color: white;
    font-size: 11px;
    border-radius: 4px;
    margin-top: 4px;
}

.review {
    border-bottom: 1px solid #eee;
    padding: 20px 0;
}

.review:last-child {
    border-bottom: none;
}

.review__header {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 15px;
}

.review__header__avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.review__header__info {
    flex: 1;
}

.review__header__info h5 {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.review__header__info p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 14px;
}

.review__header__rate .rate {
    display: flex;
    gap: 2px;
}

.review__content {
    color: #555;
    line-height: 1.6;
    margin: 0;
}

.no-reviews {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.pagination-wrapper {
    margin-top: 30px;
    text-align: center;
}

.pagination-wrapper .pagination {
    display: flex;
    justify-content: center;
    flex-wrap: nowrap;
    gap: 0;
    overflow-x: auto;
    padding-bottom: 10px;
    -webkit-overflow-scrolling: touch;
}

.pagination-wrapper .page-item {
    flex: 0 0 auto;
    margin: 0 2px;
}

.pagination-wrapper .page-link {
    padding: 8px 12px;
    font-size: 14px;
    border-radius: 5px;
    white-space: nowrap;
}

/* Mobile Responsive Pagination */
@media (max-width: 576px) {
    .pagination-wrapper {
        margin-top: 20px;
        padding: 0 10px;
    }
    
    .pagination-wrapper .pagination {
        gap: 0;
        padding: 5px 0 15px 0;
    }
    
    .pagination-wrapper .page-link {
        padding: 6px 10px;
        font-size: 13px;
        min-width: 36px;
    }
    
    .pagination-wrapper .page-item.active .page-link {
        padding: 6px 10px;
    }
}

@media (max-width: 400px) {
    .pagination-wrapper .page-link {
        padding: 5px 8px;
        font-size: 12px;
        min-width: 32px;
    }
    
    .pagination-wrapper .page-item.active .page-link {
        padding: 5px 8px;
    }
}

.review-form-section {
    margin-top: 30px;
}

.rating-input {
    display: flex;
    gap: 5px;
    direction: rtl;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 24px;
    color: #ddd;
    transition: color 0.2s;
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input[type="radio"]:checked ~ label {
    color: #ffc107;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.text-danger {
    color: #dc3545;
    font-size: 14px;
    margin-top: 4px;
}

.login-prompt {
    text-align: center;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 8px;
}

.login-prompt p {
    margin-bottom: 15px;
    color: #666;
}

/* Review Replies Styles */
.review-replies {
    margin-top: 15px;
    padding-left: 65px;
}

.reply {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
}

.reply:last-child {
    margin-bottom: 0;
}

.reply__header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.reply__header__avatar img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

.reply__header__avatar.admin img {
    border: 2px solid #28a745;
}

.reply__header__info h6 {
    margin: 0;
    font-size: 14px;
    color: #333;
}

.reply__header__info p {
    margin: 2px 0 0 0;
    color: #666;
    font-size: 12px;
}

.reply__content {
    color: #555;
    line-height: 1.5;
    font-size: 14px;
    margin: 0;
    padding-left: 46px;
}

.reply-form-container {
    margin-top: 15px;
}

.btn-reply-toggle {
    background: none;
    border: none;
    color: #aa6969;
    font-size: 14px;
    cursor: pointer;
    padding: 5px 10px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: color 0.2s;
}

.btn-reply-toggle:hover {
    color: #8a5555;
    text-decoration: underline;
}

.reply-form {
    margin-top: 10px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.reply-form .form-group {
    margin-bottom: 10px;
}

.reply-form textarea {
    resize: vertical;
    min-height: 80px;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 13px;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 10px;
}

.btn-secondary:hover {
    background: #5a6268;
}

/* Reviews Section (Direct display below description) */
.product-detail__content__description {
    margin-bottom: 20px;
}

.product-detail__content__description h5 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #333;
}

.product-detail__content__description p {
    color: #555;
    line-height: 1.8;
}

.product-detail__content__reviews {
    margin-top: 30px;
}

.product-detail__content__reviews h5 {
    font-size: 20px;
    margin-bottom: 20px;
    color: #333;
}

/* RTL Support for replies */
body[dir="rtl"] .review-replies {
    padding-right: 65px;
    padding-left: 0;
}

body[dir="rtl"] .reply__content {
    padding-right: 46px;
    padding-left: 0;
}

body[dir="rtl"] .btn-secondary {
    margin-left: 0;
    margin-right: 10px;
}

@media (max-width: 576px) {
    .review-replies {
        padding-left: 45px;
    }
    
    body[dir="rtl"] .review-replies {
        padding-right: 45px;
        padding-left: 0;
    }
    
    .reply__content {
        padding-left: 36px;
    }
    
    body[dir="rtl"] .reply__content {
        padding-right: 36px;
        padding-left: 0;
    }
}

/* Review Management Styles */
.review-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.review__header__rate {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.review__header__rate .rate {
    display: flex;
    gap: 2px;
}

.btn-edit-review,
.btn-delete-review {
    background: none;
    border: none;
    font-size: 12px;
    cursor: pointer;
    padding: 4px 8px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s;
    border-radius: 4px;
}

.btn-edit-review {
    color: #007bff;
}

.btn-edit-review:hover {
    color: #0056b3;
    background: #e7f1ff;
}

.btn-delete-review {
    color: #dc3545;
}

.btn-delete-review:hover {
    color: #c82333;
    background: #f8d7da;
}

.review-edit-form {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-top: 15px;
}

.review-edit-form .form-group {
    margin-bottom: 15px;
}

.review-edit-form textarea {
    resize: vertical;
    min-height: 100px;
}

/* RTL Support for review actions */
body[dir="rtl"] .review-actions {
    flex-direction: row-reverse;
}

body[dir="rtl"] .review__header__rate {
    align-items: flex-start;
}

body[dir="rtl"] .btn-edit-review,
body[dir="rtl"] .btn-delete-review {
    flex-direction: row-reverse;
}

@media (max-width: 576px) {
    .review-actions {
        flex-wrap: wrap;
    }
    
    .review__header__rate {
        align-items: flex-start;
        width: 100%;
    }
    
    .review__header {
        flex-wrap: wrap;
    }
}

/* SweetAlert Review Form Styles */
.swal-rating-container {
    text-align: center;
    margin-bottom: 20px;
}

.swal-rating-container label {
    display: block;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 14px;
}

.swal-rating-input {
    display: flex;
    justify-content: center;
    gap: 5px;
    direction: rtl;
}

.swal-rating-input input[type="radio"] {
    display: none;
}

.swal-star {
    cursor: pointer;
    font-size: 32px;
    color: #ddd;
    transition: color 0.2s;
}

.swal-star:hover,
.swal-star:hover ~ .swal-star,
.swal-rating-input input:checked ~ .swal-star {
    color: #ffc107;
}

.swal-form-group {
    margin-bottom: 15px;
}

.swal-form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 14px;
    text-align: right;
}

.swal-textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    resize: vertical;
    min-height: 100px;
}

.swal-textarea:focus {
    outline: none;
    border-color: #aa6969;
    box-shadow: 0 0 0 2px rgba(170, 105, 105, 0.25);
}

/* RTL Support for SweetAlert */
body[dir="rtl"] .swal-rating-input {
    flex-direction: row;
}

body[dir="rtl"] .swal-form-group label {
    text-align: right;
}

@media (max-width: 576px) {
    .swal-star {
        font-size: 28px;
    }
}
</style>
@endsection