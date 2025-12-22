@extends('front.app')
@section('title', trans('menu.home'))

@section('content')

          <div class="slider -style-3 slider-arrow-middle">
            <div class="slider__carousel">
              <div class="slider__carousel__item slider-1">
                <div class="container">
                  <div class="slider-background"><img class="slider-background" src="{{ asset('4.jpg') }}" alt="Slider background"/></div>
                  <div class="slider-content">
                    <h1 class="slider-content__title" data-animation-in="fadeInUp" data-animation-delay="0.2">{{ trans('front.slider.slide1_title') }}
                    </h1>
                    <p class="slider-content__description" data-animation-in="fadeInUp" data-animation-delay="0.3">{{ trans('front.slider.description') }}</p>
                    <div data-animation-in="fadeInUp" data-animation-out="fadeInDown" data-animation-delay="0.4"><a class="btn -red" href="{{route('all_products')}}">{{ trans('front.slider.shop_now') }}</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="slider__carousel__item slider-2">
                <div class="container">
                  <div class="slider-background"><img class="slider-background" src="{{ asset('3.jpg') }}" alt="Slider background"/></div>
                  <div class="slider-content">
                    <h1 class="slider-content__title" data-animation-in="fadeInUp" data-animation-delay="0.2">{{ trans('front.slider.slide2_title') }}
                    </h1>
                    <p class="slider-content__description" data-animation-in="fadeInUp" data-animation-delay="0.3">{{ trans('front.slider.description') }}</p>
                    <div data-animation-in="fadeInUp" data-animation-out="fadeInDown" data-animation-delay="0.4"><a class="btn -red" href="{{route('all_products')}}">{{ trans('front.slider.shop_now') }}</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="slider__carousel__item slider-3">
                <div class="container">
                  <div class="slider-background"><img class="slider-background" src="{{ asset('2.jpg') }}" alt="Slider background"/></div>
                  <div class="slider-content">
                    <h1 class="slider-content__title" data-animation-in="fadeInUp" data-animation-delay="0.2">{{ trans('front.slider.slide3_title') }}
                    </h1>
                    <p class="slider-content__description" data-animation-in="fadeInUp" data-animation-delay="0.3">{{ trans('front.slider.description') }}</p>
                    <div data-animation-in="fadeInUp" data-animation-out="fadeInDown" data-animation-delay="0.4"><a class="btn -red" href="{{route('all_products')}}">{{ trans('front.slider.shop_now') }}</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    
     
     
      
      <div class="introduction-five" style="background-image: url('{{ asset('1.jpg') }}')">
        <div class="container">
          <div class="introduction-five__content">
            <h2>{{ __('home.new_items_released_weekly') }}</h2><a class="btn -red" href="{{route('all_products')}}">{{ __('home.all_news_items') }}</a>
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
</script>

@endsection