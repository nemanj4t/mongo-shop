@extends('layouts.app')

@section('content')
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- Product main img -->
                <div class="col-md-5 col-md-push-2">
                    <div id="product-main-img">
                        <div class="product-preview">
                            <img src="{{$product->image}}" alt="">
                        </div>
                    </div>
                </div>
                <!-- /Product main img -->

                <!-- Product thumb imgs -->
                <div class="col-md-2  col-md-pull-5">
                </div>
                <!-- /Product thumb imgs -->

                <!-- Product details -->
                <div class="col-md-5">
                    <div class="product-details">
                        <h2 class="product-name">{{$product->name}}</h2>
                        <div>
                            <div class="product-rating">
                                @if($product->grade)
                                    @for($i=0; $i < round(floatval($product->grade)); $i++)
                                        <i class="fa fa-star"></i>
                                    @endfor
                                @else
                                    <i class="fa fa-star"></i> - no rating yet!
                                @endif
                            </div>
                            <a class="review-link" href="#">{{sizeof($product->comments)}} Review(s) | Add your review</a>
                        </div>
                        <div>
                            <h3 class="product-price">${{$product->price}}</h3>
                            <span class="product-available">
                                @if(intval($product->stock) > 0)
                                    In
                                @else
                                    Out of
                                @endif
                                    Stock
                            </span>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

                        <div class="add-to-cart row">
                            <shopping-cart-button product_id="{{$product->_id}}" ></shopping-cart-button>
                            <wish-list-button product_id="{{$product->_id}}"></wish-list-button>
                        </div>
                        <ul class="product-links">
                            <li>Category:</li>
                            <li><a class="navitemlink" href="/categories/{{$product->category->id}}">{{$product->category->name}}</a></li>
                        </ul>
                    </div>
                    <table class="table">
                        @foreach($attr as $key => $value)
                            @if(!is_array($value))
                                @if($key != '_id' && $key != 'category_id' && $key != 'image' && $key != 'recommendation' && $key != 'created_at' && $key != 'updated_at')
                                <tr>
                                    <td>{{ ucfirst($key) }}</td>
                                    <td>{{$value}}</td>
                                </tr>
                                @endif
                            @else
                                @foreach($value as $key => $attribute)
                                    @if(!($value != 'user_ids'))
                                        <tr>
                                            <td>{{$key}}</td>
                                            <td>{{$attribute}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        @if(isset($attr['product_properties']))
                            @foreach($attr['product_properties'] as $key => $value)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{$value}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
                <!-- /Product details -->

                <!-- Product tab -->
                <div class="col-md-12">
                    <div id="product-tab">
                        <!-- product tab nav -->
                        <ul class="tab-nav">
                            <li><a data-toggle="tab" class="navitemlink" href="#tab3">Reviews</a></li>
                        </ul>
                        <!-- /product tab nav -->

                        <!-- product tab content -->
                        <div class="tab-content">
                            <!-- tab3  -->
                            <div id="tab3">
                                <div class="row">
                                    <!-- Rating -->
                                    <div class="col-md-3">
                                        <div id="rating">
                                            <div class="rating-avg">
                                                <span>{{$product->grade}}</span>
                                                <div class="rating-stars">
                                                    @if($product->grade)
                                                        @for($i=0; $i < round(floatval($product->grade)); $i++)
                                                        <i class="fa fa-star"></i>
                                                        @endfor
                                                    @else
                                                        <i class="fa fa-star"></i> - no rating yet
                                                    @endif
                                                </div>
                                            </div>
                                            <ul class="rating">
                                                <li>
                                                    <div class="rating-stars">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                    </div>
                                                    <div class="rating-progress">
                                                        @if($s5 > 0)
                                                            <div style="width: {{$s5/sizeof($product->comments)*100}}%;"></div>
                                                        @endif
                                                    </div>
                                                    <span class="sum">{{$s5}}</span>
                                                </li>
                                                <li>
                                                    <div class="rating-stars">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </div>
                                                    <div class="rating-progress">
                                                        @if($s4 > 0)
                                                            <div style="width: {{$s4/sizeof($product->comments)*100}}%;"></div>
                                                        @endif
                                                    </div>
                                                    <span class="sum">{{$s4}}</span>
                                                </li>
                                                <li>
                                                    <div class="rating-stars">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </div>
                                                    <div class="rating-progress">
                                                        @if($s3 > 0)
                                                            <div style="width: {{$s3/sizeof($product->comments)*100}}%;"></div>
                                                        @endif
                                                    </div>
                                                    <span class="sum">{{$s3}}</span>
                                                </li>
                                                <li>
                                                    <div class="rating-stars">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </div>
                                                    <div class="rating-progress">
                                                        @if($s2 > 0)
                                                            <div style="width: {{$s2/sizeof($product->comments)*100}}%;"></div>
                                                        @endif
                                                    </div>
                                                    <span class="sum">{{$s2}}</span>
                                                </li>
                                                <li>
                                                    <div class="rating-stars">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </div>
                                                    <div class="rating-progress">
                                                        @if($s1 > 0)
                                                            <div style="width: {{$s1/sizeof($product->comments)*100}}%;"></div>
                                                        @endif
                                                    </div>
                                                    <span class="sum">{{$s1}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div id="reviews">
                                            <ul class="reviews">
                                                @if(sizeof($product->comments) === 0)
                                                <div class="jumbotron text-center" style="height: 100%">
                                                    <p class="lead">This product still does not have reviews, yet</p>
                                                    <hr class="my-4">
                                                    <p>Be first!</p>
                                                </div>
                                                @endif
                                                @foreach($product->comments as $comment)
                                                    <li>
                                                        <div class="review-heading">
                                                            <h5 class="name">{{$comment->user->name}}</h5>
                                                            <p class="date">{{$comment->created_at}}</p>
                                                            <div class="review-rating">
                                                                @for($i = 0; $i < intval($comment->rating); $i++)
                                                                    <i class="fa fa-star"></i>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="review-body">
                                                            <p>{{$comment->content}}</p>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- /Reviews -->

                                    <!-- Review Form -->
                                    <div class="col-md-3">
                                        <div id="review-form">
                                            @auth()
                                            <form class="review-form" action="/product/{{$product->id}}/comments" method="POST">
                                                @csrf
                                                <textarea class="input" name="comment_content" placeholder="Your Review"></textarea>
                                                <div class="input-rating">
                                                    <span>Your Rating: </span>
                                                    <div class="stars">
                                                        <input id="star5" name="rating" value="5" type="radio"><label for="star5"></label>
                                                        <input id="star4" name="rating" value="4" type="radio"><label for="star4"></label>
                                                        <input id="star3" name="rating" value="3" type="radio"><label for="star3"></label>
                                                        <input id="star2" name="rating" value="2" type="radio"><label for="star2"></label>
                                                        <input id="star1" name="rating" value="1" type="radio"><label for="star1"></label>
                                                    </div>
                                                </div>
                                                <button type="submit" class="primary-btn">Submit</button>
                                            </form>
                                            @else
                                                <div class="jumbotron text-center">
                                                    <h4 >Hello, customer!</h4>
                                                    <p class="lead">Please login or register to give review.</p>
                                                    <hr class="my-4">
                                                    <p class="lead">
                                                        <a class="btn btn-danger" href="/login" role="button">Login</a>
                                                        <a class="btn btn-primary ml-2" href="/register" role="button">Register</a>
                                                    </p>
                                                </div>
                                            @endauth
                                        </div>
                                    </div>
                                    <!-- /Review Form -->
                                </div>
                            </div>
                            <!-- /tab3  -->
                        </div>
                        <!-- /product tab content  -->
                    </div>
                </div>
                <!-- /product tab -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->
@endsection