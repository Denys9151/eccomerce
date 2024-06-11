<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistProducts = Wishlist::with('product')->where('user_id', Auth::id())->orderBy('id', 'DESC')->get();
        return view('frontend.pages.wishlist', compact('wishlistProducts'));
    }

    public function addToWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response(['status' => 'error', 'message' => 'Login before add a product into wishlist!']);
        }

        $wishlistCount = Wishlist::where(['product_id' => $request->id, 'user_id' => Auth::id()])->count();
        if ($wishlistCount > 0) {
            return response(['status' => 'error', 'message' => 'The product is already in your wishlist!']);
        }

        $wishlist = new Wishlist();
        $wishlist->product_id = $request->id;
        $wishlist->user_id = Auth::id();
        $wishlist->save();

        $count = Wishlist::where('user_id', Auth::id())->count();

        return response(['status' => 'success', 'message' => 'Product added to wishlist!', 'count' => $count]);
    }

    public function destroy(string $id)
    {
        $wishlistProducts = Wishlist::findOrFail($id);
        if($wishlistProducts->user_id !== Auth::user()->id){
            return redirect()->back();
        }
        $wishlistProducts->delete();

        toastr('Product deleted from wishlist!', 'success', 'Success');

        return redirect()->back();
    }
}
