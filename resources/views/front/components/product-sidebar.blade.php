{{-- resources/views/front/components/product-sidebar.blade.php --}}
<div class="col-12 col-md-4 col-lg-3">
  <div class="shop-sidebar">
    <div class="shop-sidebar__content">
      
      {{-- Categories Filter --}}
      <div class="shop-sidebar__section -categories">
        <div class="section-title -style1 -medium" style="margin-bottom:1.875em">
          <h2>{{ __('products.categories') }}</h2>
          <img src="{{ asset('app/assets/images/introduction/IntroductionOne/content-deco.png') }}" alt="Decoration"/>
        </div>
        <ul>
          <li>
            <a href="{{ request()->url() }}{{ request()->has('sort') ? '?sort=' . request('sort') : '' }}" 
               class="{{ !request('category_id') ? 'active' : '' }}">
              {{ __('products.all') }}
            </a>
          </li>
          @foreach($categories as $category)
          <li>
            <a href="{{ request()->url() }}{{ request()->has('sort') ? '?sort=' . request('sort') . '&' : '?' }}category_id={{ $category->id }}" 
               class="{{ request('category_id') == $category->id ? 'active' : '' }}">
              {{ app()->getLocale() == 'ar' ? ($category->name ?? $category->englishname) : ($category->englishname ?? $category->name) }}
              @if(isset($category->products_count))
                <span class="count">({{ $category->products_count }})</span>
              @endif
            </a>
          </li>
          @endforeach
        </ul>
      </div>

      {{-- Price Filter --}}
      <div class="shop-sidebar__section -refine">
        <div class="section-title -style1 -medium" style="margin-bottom:1.875em">
          <h2>{{ __('products.price_range') }}</h2>
          <img src="{{ asset('app/assets/images/introduction/IntroductionOne/content-deco.png') }}" alt="Decoration"/>
        </div>
        
        {{-- Price Range Checkboxes --}}
        <div class="shop-sidebar__section__item">
          <h5>{{ __('products.price') }}</h5>
          <ul>
            <li>
              <label for="price-0">
                <input type="checkbox" name="price_range" value="0-25" 
                       {{ request('price_range') == '0-25' ? 'checked' : '' }} 
                       onchange="filterProducts(this)"/>
                {{ app()->getLocale() == 'ar' ? 'دينار' : 'LYD' }}0.00 - {{ app()->getLocale() == 'ar' ? 'دينار' : 'LYD' }}25.00
              </label>
            </li>
            <li>
              <label for="price-1">
                <input type="checkbox" name="price_range" value="25-50" 
                       {{ request('price_range') == '25-50' ? 'checked' : '' }} 
                       onchange="filterProducts(this)"/>
                {{ app()->getLocale() == 'ar' ? 'دينار' : 'LYD' }}25.00 - {{ app()->getLocale() == 'ar' ? 'دينار' : 'LYD' }}50.00
              </label>
            </li>
            <li>
              <label for="price-2">
                <input type="checkbox" name="price_range" value="50-75" 
                       {{ request('price_range') == '50-75' ? 'checked' : '' }} 
                       onchange="filterProducts(this)"/>
                {{ app()->getLocale() == 'ar' ? 'دينار' : 'LYD' }}50.00 - {{ app()->getLocale() == 'ar' ? 'دينار' : 'LYD' }}75.00
              </label>
            </li>
            <li>
              <label for="price-3">
                <input type="checkbox" name="price_range" value="75-100" 
                       {{ request('price_range') == '75-100' ? 'checked' : '' }} 
                       onchange="filterProducts(this)"/>
                {{ app()->getLocale() == 'ar' ? 'دينار' : 'LYD' }}75.00 - {{ app()->getLocale() == 'ar' ? 'دينار' : 'LYD' }}100.00
              </label>
            </li>
            <li>
              <label for="price-4">
                <input type="checkbox" name="price_range" value="100+" 
                       {{ request('price_range') == '100+' ? 'checked' : '' }} 
                       onchange="filterProducts(this)"/>
                {{ app()->getLocale() == 'ar' ? 'دينار' : 'LYD' }}100.00+
              </label>
            </li>
          </ul>
        </div>

        {{-- Brand Filter --}}
        @if(isset($brands) && $brands->count() > 0)
        <div class="shop-sidebar__section__item">
          <h5>{{ __('products.brand') }}</h5>
          <ul>
            @foreach($brands as $brand)
            <li>
              <label for="brand-{{ $loop->index }}">
                <input type="checkbox" name="brand" value="{{ $brand->brandname ?? $brand->material }}" 
                       {{ request('brand') == ($brand->brandname ?? $brand->material) ? 'checked' : '' }} 
                       onchange="filterProducts(this)"/>
                {{ $brand->brandname ?? $brand->material }}
              </label>
            </li>
            @endforeach
          </ul>
        </div>
        @endif

        {{-- Country of Origin Filter --}}
        <div class="shop-sidebar__section__item">
          <h5>{{ __('products.country_origin') }}</h5>
          <ul>
            @php
                $availableCountries = collect();
                if(isset($products)) {
                    $availableCountries = $products->getCollection()
                        ->pluck('country_of_origin_' . (app()->getLocale() == 'ar' ? 'ar' : 'en'))
                        ->filter()
                        ->unique()
                        ->sort();
                }
            @endphp
            
            @if($availableCountries->count() > 0)
                @foreach($availableCountries as $index => $country)
                <li>
                  <label for="country-{{ $index }}">
                    <input type="checkbox" name="country" value="{{ $country }}" 
                           {{ request('country') == $country ? 'checked' : '' }} 
                           onchange="filterProducts(this)"/>
                    {{ $country }}
                  </label>
                </li>
                @endforeach
            @else
                {{-- Default countries if none found in products --}}
                <li>
                  <label for="country-0">
                    <input type="checkbox" name="country" value="USA" 
                           {{ request('country') == 'USA' ? 'checked' : '' }} 
                           onchange="filterProducts(this)"/>
                    {{ __('products.usa') }}
                  </label>
                </li>
                <li>
                  <label for="country-1">
                    <input type="checkbox" name="country" value="UK" 
                           {{ request('country') == 'UK' ? 'checked' : '' }} 
                           onchange="filterProducts(this)"/>
                    {{ __('products.uk') }}
                  </label>
                </li>
                <li>
                  <label for="country-2">
                    <input type="checkbox" name="country" value="France" 
                           {{ request('country') == 'France' ? 'checked' : '' }} 
                           onchange="filterProducts(this)"/>
                    {{ __('products.france') }}
                  </label>
                </li>
                <li>
                  <label for="country-3">
                    <input type="checkbox" name="country" value="Italy" 
                           {{ request('country') == 'Italy' ? 'checked' : '' }} 
                           onchange="filterProducts(this)"/>
                    {{ __('products.italy') }}
                  </label>
                </li>
                <li>
                  <label for="country-4">
                    <input type="checkbox" name="country" value="Germany" 
                           {{ request('country') == 'Germany' ? 'checked' : '' }} 
                           onchange="filterProducts(this)"/>
                    {{ __('products.germany') }}
                  </label>
                </li>
            @endif
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function filterProducts(checkbox) {
    const url = new URL(window.location.href);
    const filterName = checkbox.name;
    const filterValue = checkbox.value;
    
    // Remove all existing filter parameters
    url.searchParams.delete('category_id');
    url.searchParams.delete('price_range');
    url.searchParams.delete('brand');
    url.searchParams.delete('country');
    
    // Add the selected filter
    if (checkbox.checked) {
        url.searchParams.set(filterName, filterValue);
    }
    
    // Keep sort parameter if it exists
    const sortParam = new URLSearchParams(window.location.search).get('sort');
    if (sortParam) {
        url.searchParams.set('sort', sortParam);
    }
    
    // Redirect to the new URL
    window.location.href = url.toString();
}
</script>