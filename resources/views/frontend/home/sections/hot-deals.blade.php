<section id="wsus__hot_deals" class="wsus__hot_deals_2">
    <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__section_header justify-content-start">
                        <div class="monthly_top_filter2 mb-1">
                            <button class="active auto_click" data-filter=".new_arrival">New Arrival</button>
                            <button data-filter=".featured_product">Featured</button>
                            <button data-filter=".top_product">Top Product</button>
                            <button data-filter=".best_product">Best Product</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row grid2">
                @foreach($typeBaseProducts as $key => $products)
                    @foreach($products as $product)
                        <x-product-card :product="$product" :key="$key"/>
                    @endforeach
                @endforeach
            </div>
        </div>

    <section id="wsus__single_banner" class="home_2_single_banner">
            <div class="container">
                <div class="row">

                    <div class="col-xl-6 col-lg-6">
                        @if( $homepage_section_banner_three->banner_one->status == 1)
                            <div class="wsus__single_banner_content banner_1">
                                <a href="{{ $homepage_section_banner_three->banner_one->banner_url }}">
                                    <img class="img-fluid" src="{{ asset($homepage_section_banner_three->banner_one->banner_image) }}" alt="">
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="row">
                            <div class="col-12">
                                @if( $homepage_section_banner_three->banner_two->status == 1)
                                    <div class="wsus__single_banner_content single_banner_2">
                                        <a href="{{ $homepage_section_banner_three->banner_two->banner_url }}">
                                            <img class="img-fluid" src="{{ asset($homepage_section_banner_three->banner_two->banner_image) }}" alt="">
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 mt-lg-4">
                                <div class="wsus__single_banner_content">
                                    @if( $homepage_section_banner_three->banner_three->status == 1)
                                        <a href="{{ $homepage_section_banner_three->banner_three->banner_url }}">
                                            <img class="img-fluid" src="{{ asset($homepage_section_banner_three->banner_three->banner_image) }}" alt="">
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

</section>

@foreach($typeBaseProducts as $key => $products)
    @foreach($products as $product)
{{--
        <section class="product_popup_modal">
        <div class="modal fade" id="product-type-{{ $product->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="far fa-times"></i></button>
                        <div class="row">
                            <div class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                                <div class="wsus__quick_view_img">
                                    @if($product->video_link)
                                        <a class="venobox wsus__pro_det_video" data-autoplay="true" data-vbtype="video"
                                           href="{{ $product->video_link }}">
                                            <i class="fas fa-play"></i>
                                        </a>
                                    @endif
                                    <div class="row modal_slider">

                                        <div class="col-xl-12">
                                            <div class="modal_slider_img">
                                                <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100">
                                            </div>
                                        </div>

                                        @if(count($product->productImageGalleries) === 0)
                                            <div class="col-xl-12">
                                                <div class="modal_slider_img">
                                                    <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100">
                                                </div>
                                            </div>
                                        @endif

                                        @foreach($product->productImageGalleries as $productImage)
                                            <div class="col-xl-12">
                                                <div class="modal_slider_img">
                                                    <img src="{{ asset($productImage->image) }}" alt="{{ $product->name }}" class="img-fluid w-100">
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                                <div class="wsus__pro_details_text">
                                    <a class="title" href="#">{{ $product->name }}</a>
                                    <p class="wsus__stock_area"><span class="in_stock">in stock</span> (167 item)</p>
                                    @if(checkDiscount($product))
                                        <h4>{{ $settings->currency_icon }}{{ $product->offer_price }}
                                            <del>${{ $product->price }}</del>
                                        </h4>
                                    @else
                                        <h4>{{ $settings->currency_icon }}{{ $product->price }}</h4>
                                    @endif
                                    <p class="review">
                                        @php
                                            $avgRating = $product->reviews()->avg('rating');
                                            $fullRating = round($avgRating);
                                        @endphp

                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i < $fullRating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor

                                        <span>({{ count($product->reviews) }} reviews)</span>
                                    </p>
                                    <p class="description">{!! $product->short_description !!}</p>

                                    <form class="shopping-cart-form">
                                        <div class="wsus__selectbox">
                                            <div class="row">
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                @foreach($product->variants as $variant)
                                                    <div class="col-xl-6 col-sm-6">
                                                        <h5 class="mb-2">{{ $variant->name }}</h5>
                                                        <select class="select_2" name="variants_items[]">
                                                            @foreach($variant->productVariantItems as $variantItem)
                                                                <option value="{{ $variantItem->id }}" {{ $variantItem->is_default == 1 ? 'selected' : '' }}>{{ $variantItem->name }} (${{ $variantItem->price }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="wsus__quentity">
                                            <h5>quentity :</h5>
                                            <div class="select_number">
                                                <input class="number_area" name="qty" type="text" min="1" max="100" value="1"/>
                                            </div>
                                            <h3>$50.00</h3>
                                        </div>
                                        <ul class="wsus__button_area">
                                            <li><button type="submit" class="add_cart" href="#">add to cart</button></li>
                                            <li><a class="buy_now" href="#">buy now</a></li>
                                            <li><a href="" class="add-to-wishlist" data-id="{{ $product->id }}"><i class="fal fa-heart"></i></a></li>
--}}
{{--                                            <li><a href="#"><i class="far fa-random"></i></a></li>--}}{{--

                                        </ul>
                                    </form>


                                    <p class="brand_model"><span>brand :</span> {{ $product->brand->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
--}}
    @endforeach
@endforeach
