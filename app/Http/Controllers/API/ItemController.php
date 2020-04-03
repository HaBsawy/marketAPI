<?php

namespace App\Http\Controllers\API;

use App\Checkout;
use App\CheckoutItems;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemsCollection;
use App\Http\Resources\ItemsResource;
use App\Item;
use App\SubCategory;
use App\User;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ItemsCollection::collection(Item::paginate(10));
    }

    public function userItem(User $user)
    {
        return ItemsCollection::collection(Item::where('user_id', $user->id)->paginate(10));
    }

    public function subcategoryItem(SubCategory $subcategory)
    {
        return ItemsCollection::collection(Item::where('sub_cat_id', $subcategory->id)->paginate(10));
    }

    public function checkoutItem(Checkout $checkout)
    {
        return ItemsCollection::collection($checkout->items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return new ItemsResource($item);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
