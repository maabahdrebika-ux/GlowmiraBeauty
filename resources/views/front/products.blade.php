{{-- resources/views/front/products.blade.php --}}
@extends('front.app')
@section('title', trans('products.products'))

@section('styles')
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
      <div class="breadcrumb">
        <div class="container">
          <h2>{{ trans('menu.shop') }}</h2>
          <ul><li>{{ trans('menu.home') }}</li><li class="active">{{ trans('menu.shop') }}</li></ul>
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
                
                <form method="GET" style="display: inline;">
                  <select class="customed-select" name="sort" onchange="this.form.submit()">
                    <option value="az">{{ trans('products.a_to_z') }}</option>
                    <option value="za">{{ trans('products.z_to_a') }}</option>
                    <option value="low-high">{{ trans('products.low_to_high') }}</option>
                    <option value="high-low">{{ trans('products.high_to_low') }}</option>
                  </select>
                </form>
              </div>
              <div class="shop-products">
                <div class="shop-products__gird">
                  <div class="row mx-n1 mx-lg-n3">
                    @foreach($products as $product)
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 px-1 px-lg-3">
                          <div class="product">
                            <div class="product-type">
                              @if($product->discounts->count() > 0)
                                <h5 class="-sale">-{{ $product->discounts->first()->percentage }}%</h5>
                              @elseif($product->created_at > now()->subDays(30))
                                <h5 class="-new">{{ trans('products.new') }}</h5>
                              @endif
                            </div>
                            <div class="product-thumb">
                              <a class="product-thumb__image" href="{{ route('product/info', encrypt($product->id)) }}">
                                @if($product->image)
                                  <img src="{{ asset('images/product/' . $product->image) }}" alt="Product image"/>
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
                                <div class="product-btn"><a class="btn -white product__actions__item -round product-qv" href="#" data-product-id="{{ $product->id }}" data-product-name="{{ app()->getLocale() == 'en' ? ($product->namee ?? $product->name) : $product->name }}" data-product-category="{{ app()->getLocale() == 'en' ? ($product->categories->englishname ?? $product->categories->name ?? 'Category') : ($product->categories->name ?? 'Category') }}" data-product-price="{{ $product->price }}" data-product-image="{{ $product->image ? asset('images/product/' . $product->image) : asset('app/assets/images/product/1.png') }}"><i class="fas fa-eye"></i></a>
                                </div>
                                <div class="product-btn"><a class="btn -white product__actions__item -round" href="#"><i class="fas fa-heart"></i></a>
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
                    @endforeach
                  </div>
                </div>
               
                <div class="pagination-wrapper">
                  {{ $products->links() }}
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
        // Show error message with SweetAlert
        Swal.fire({
            icon: 'error',
            title: '{{ __("products.error") }}',
            text: error.message || '{{ __("products.error_adding_to_cart") }}',
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

// Universal cart count update function
function updateCartCount() {
    fetch('/cart/items/count')
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

// Update cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});
</script>

<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

@endsection
