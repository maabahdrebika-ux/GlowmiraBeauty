{{-- resources/views/front/productcategory.blade.php --}}
@extends('front.app')
@section('title', trans('menu.shop'))

@section('content')
      <div class="breadcrumb">
        <div class="container">
          <h2>{{ app()->getLocale() == 'ar' ? ($categoriess->name ?? 'Shop') : ($categoriess->englishname ?? 'Shop') }}</h2>
          <ul>
            <li>{{ trans('menu.home') }}</li>
            <li>{{ trans('menu.shop') }}</li>
            <li class="active">{{ app()->getLocale() == 'ar' ? ($categoriess->name ?? 'Category') : ($categoriess->englishname ?? 'Category') }}</li>
          </ul>
        </div>
      </div>
      <div class="shop">
        <div class="container">
          <div class="row">
            {{-- Include Sidebar Component --}}
            @include('front.components.product-sidebar', [
                'categories' => $categories ?? collect(),
                'brands' => $brands ?? collect(),
                'products' => $products ?? null
            ])
            
            <div class="col-12 col-md-8 col-lg-9">
              <div class="shop-header">
                
                <form method="GET" action="" style="display: inline;" id="sort-form">
                  <select class="customed-select" name="sort" id="sort-select">
                    <option value="" {{ !request('sort') ? 'selected' : '' }}>{{ trans('products.sort_by') }}</option>
                    <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>{{ trans('products.a_to_z') }}</option>
                    <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>{{ trans('products.z_to_a') }}</option>
                    <option value="low-high" {{ request('sort') == 'low-high' ? 'selected' : '' }}>{{ trans('products.low_to_high') }}</option>
                    <option value="high-low" {{ request('sort') == 'high-low' ? 'selected' : '' }}>{{ trans('products.high_to_low') }}</option>
                  </select>
                </form>
              </div>
              <div class="shop-products">
                <div class="shop-products__gird">
                  <div class="row mx-n1 mx-lg-n3">
                    @forelse($products as $product)
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 px-1 px-lg-3">
                          <div class="product">
                            <div class="product-type">
                              @if($product->discounts->count() > 0)
                                <h5 class="-sale">-{{ $product->discounts->first()->percentage }}%</h5>
                              @elseif($product->created_at > now()->subDays(30))
                                <h5 class="-new">{{ trans('products.new') }}</h5>
                              @endif
                              @if($product->is_out_of_stock)
                                <span class="stock-badge stock-out">{{ $product->out_of_stock_message }}</span>
                              @elseif($product->is_critical_stock)
                                <span class="stock-badge stock-critical">{{ $product->critical_stock_message }}</span>
                              @elseif($product->is_low_stock)
                                <span class="stock-badge stock-low">{{ $product->low_stock_message }}</span>
                              @endif
                            </div>
                            <div class="product-thumb">
                              <a class="product-thumb__image" href="{{ route('product/info', encrypt($product->id)) }}">
                                @if($product->image)
                                  <img src="{{ asset('images/product/' . rawurlencode($product->image)) }}" alt="Product image"/>
                                @else
                                  <img src="{{ asset('app/assets/images/product/1.png') }}" alt="Product image"/>
                                @endif
                              </a>
                              <div class="product-thumb__actions">
                                <div class="product-btn">
                                    <button class="btn -white product__actions__item -round product-atc" onclick="addToCart({{ $product->id }})">
                                        <i class="fas fa-shopping-bag"></i>
                                    </button>
                                </div>
                                <div class="product-btn"><a class="btn -white product__actions__item -round product-qv" href="#" data-product-id="{{ $product->id }}" data-product-name="{{ app()->getLocale() == 'en' ? ($product->namee ?? $product->name) : $product->name }}" data-product-category="{{ app()->getLocale() == 'en' ? ($product->categories->englishname ?? $product->categories->name ?? 'Category') : ($product->categories->name ?? 'Category') }}" data-product-price="{{ $product->price }}" data-product-image="{{ $product->image ? asset('images/product/' . rawurlencode($product->image)) : asset('app/assets/images/product/1.png') }}"><i class="fas fa-eye"></i></a>
                                </div>
                                <div class="product-btn"><button class="btn -white product__actions__item -round" onclick="toggleWishlist({{ $product->id }}, this)" title="{{ __('menu.add_to_wishlist') }}"><i class="far fa-heart"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="product-content">
                              <div class="product-content__header">
                                <div class="product-category">{{ app()->getLocale() == 'en' ? ($product->categories->englishname ?? $product->categories->name ?? 'Category') : ($product->categories->name ?? 'Category') }}</div>
                              </div>
                              <a class="product-name" href="{{ route('product/info', encrypt($product->id)) }}">{{ app()->getLocale() == 'en' ? ($product->namee ?? $product->name) : $product->name }}</a>
                              <div class="product-content__footer">
                                @if($product->discounts->count() > 0)
                                  <h5 class="product-price--main">{{ number_format($product->price - ($product->price * $product->discounts->first()->percentage / 100), 2) }} {{ __('app.lyd') }}</h5>
                                  <h5 class="product-price--discount">{{ number_format($product->price, 2) }} {{ __('app.lyd') }}</h5>
                                @else
                                  <h5 class="product-price--main">{{ number_format($product->price, 2) }} {{ __('app.lyd') }}</h5>
                                @endif
                                <div class="product-colors">
                                  @if(isset($sizesByProduct[$product->id]))
                                    @foreach($sizesByProduct[$product->id]->take(2) as $size)
                                      <div class="product-colors__item" style="background-color: #ccc">{{ $size->name ?? 'S' }}</div>
                                    @endforeach
                                  @endif
                                  @if(isset($gradesByProduct[$product->id]))
                                    @foreach($gradesByProduct[$product->id]->take(2) as $color)
                                      <div class="product-colors__item" style="background-color: {{ $color->color_code ?? '#000' }}"></div>
                                    @endforeach
                                  @endif
                                </div>
                              </div>
                            </div>
                          </div>
                    </div>
                    @empty
                    <div class="col-12">
                      <div class="text-center">
                        <p>{{ trans('products.no_products') }}</p>
                      </div>
                    </div>
                    @endforelse
                  </div>
                </div>
               
                <div class="pagination-wrapper">
                  {{ $products->appends(request()->query())->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

<script>
// Add to Cart functionality
function addToCart(productId, quantity = 1) {
    const addToCartBtn = event.target.closest('button');
    
    // Show loading state
    if (addToCartBtn) {
        addToCartBtn.disabled = true;
        const originalHTML = addToCartBtn.innerHTML;
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
            // Show success message with SweetAlert
            Swal.fire({
                icon: 'success',
                title: '{{ __("products.added_to_cart") }}',
                text: data.message || '{{ __("products.product_added_successfully") }}',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
            
            // Update cart count if needed
            updateCartCount();
        } else {
            throw new Error(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: '{{ __("app.error") }}',
            text: error.message || '{{ __("app.error_adding_to_cart") }}',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: true,
            position: 'center'
        });
    })
    .finally(() => {
        // Reset button
        if (addToCartBtn) {
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = '<i class="fas fa-shopping-bag"></i>';
        }
    });
}

// Update cart count
function updateCartCount() {
    fetch('/cart/items/count')
    .then(response => response.json())
    .then(data => {
        if (data.success && data.count !== undefined) {
            // Update cart count element if it exists
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = data.count;
            }
        }
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}

// Wishlist functionality
function toggleWishlist(productId, button) {
    // Show loading state
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button icon
            if (data.in_wishlist) {
                button.innerHTML = '<i class="fas fa-heart text-danger"></i>';
                button.title = '{{ __("menu.remove_from_wishlist") }}';
            } else {
                button.innerHTML = '<i class="far fa-heart"></i>';
                button.title = '{{ __("menu.add_to_wishlist") }}';
            }

            // Show success message
            Swal.fire({
                icon: 'success',
                title: data.message,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });

            // Update wishlist count if needed
            updateWishlistCount();
        } else {
            if (data.login_required) {
                // Redirect to login
                window.location.href = '{{ route("customer.login") }}';
            } else {
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("app.error") }}',
                    text: data.message,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    position: 'center'
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: '{{ __("app.error") }}',
            text: '{{ __("app.error_processing_wishlist") }}',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: true,
            position: 'center'
        });
    })
    .finally(() => {
        button.disabled = false;
        if (!button.innerHTML.includes('fa-heart')) {
            button.innerHTML = originalContent;
        }
    });
}

// Update wishlist count
function updateWishlistCount() {
    fetch('/wishlist/count')
    .then(response => response.json())
    .then(data => {
        if (data.success && data.count !== undefined) {
            // Update wishlist count elements
            const wishlistCountElements = document.querySelectorAll('.wishlist-count, .wishlist-quantity');
            wishlistCountElements.forEach(element => {
                element.textContent = data.count;
            });
        }
    })
    .catch(error => {
        console.error('Error updating wishlist count:', error);
    });
}

// Handle sort select for mobile - more reliable than onchange
document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            document.getElementById('sort-form').submit();
        });
    }
});
</script>

@endsection