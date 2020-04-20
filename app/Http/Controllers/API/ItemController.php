<?php

namespace App\Http\Controllers\API;

use App\Checkout;
use App\CheckoutItems;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemsCollection;
use App\Http\Resources\ItemsResource;
use App\Image;
use App\Item;
use App\SubCategory;
use App\User;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return (ItemsCollection::collection(Item::paginate(10)))->additional([
            'msg' => 'items list',
            'create item' => [
                'href' => route('items.store'),
                'method' => 'POST',
                'params' => 'subcategory_id, price, stock, brand, description, expiration_date, min_allowed_stock',
                'optional_params' => 'image1, image2, ......'
            ]
        ]);
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

    public function store(Request $request)
    {
        $files = $request->file();
        $errors = [];

        $this->validate($request, [
            'subcategory_id' => 'required|exists:sub_categories,id',
            'price' => 'required|numeric|min:0|max:10000',
            'stock' => 'required|numeric|min:0|max:100000',
            'brand' => 'required|string|min:3|max:20',
            'description' => 'required|string|min:3|max:1000',
            'expiration_date' => 'required|date|after:today',
            'min_allowed_stock' => 'required|numeric|min:0|max:100000',
        ]);

        if (auth()->user()->role !== 'custome') {
            $item = new Item();
            $item->user_id = auth()->user()->id;
            $item->sub_cat_id = $request->subcategory_id;
            $item->price = $request->price;
            $item->stock = $request->stock;
            $item->brand = $request->brand;
            $item->description = $request->description;
            $item->expiration_date = $request->expiration_date;
            $item->min_allowed_stock = $request->min_allowed_stock;

            if ($item->save()) {

                if (!empty($files) && is_array($files)) {
                    foreach ($files as $file) {
                        $file_name = $file->getClientOriginalName();
                        $file_size = round($file->getSize() / 1024);
                        $file_ex = $file->getClientOriginalExtension();
                        $full_name = time() . '_' . $file_name . '.' . $file_ex;

                        if (!in_array($file_ex, ['png', 'jpg', 'jpeg'])) {
                            $errors['not_image'][] = $file_name . 'not image';
                        } elseif ($file_size > 1024) {
                            $errors['large_size'][] = $file_name . 'is too big';
                        } else {
                            $image = new Image();
                            $image->item_id = $item->id;
                            $image->name = $full_name;

                            if ($image->save()) {
                                $file->move('uploads', $full_name);
                            }
                        }
                    }
                } elseif (!empty($files)) {
                    $file_name = $files->getClientOriginalName();
                    $file_size = round($files->getSize() / 1024);
                    $file_ex = $files->getClientOriginalExtension();
                    $full_name = time() . '_' . $file_name . '.' . $file_ex;

                    if (!in_array($file_ex, ['png', 'jpg', 'jpeg'])) {
                        $errors['not_image'][] = $file_name . 'not image';
                    } elseif ($file_size > 1024) {
                        $errors['large_size'][] = $file_name . 'is too big';
                    } else {
                        if (in_array($full_name, $images)) {
                            $errors['repeated'][] = $file_name . 'is repeated';
                        } else {
                            $image = new Image();
                            $image->item_id = $item->id;
                            $image->name = $full_name;

                            if ($image->save()) {
                                $files->move('uploads', $full_name);
                            }
                        }
                    }
                }

                return response()->json([
                    'msg' => 'the item is created successfully',
                    'data' => [
                        'subcategory' => $item->subcategory->name,
                        'user' => $item->user->name,
                        'price' => $item->price,
                        'stock' => $item->stock,
                        'brand' => $item->brand,
                        'description' => $item->description,
                        'expiration_date' => $item->expiration_date,
                        'min_allowed_stock' => $item->min_allowed_stock,
                        'href' => route('items.show', $item->id),
                        'image_errors' => $errors
                    ],
                ], 201);
            } else {
                return response()->json([
                    'error' => 'an error occur during create item'
                ], 202);
            }
        } else {
            return response()->json([
                'msg' => 'You have not permission to create item'
            ], 401);
        }
    }

    public function show($id)
    {
        return (new ItemsResource(Item::find($id)))->additional([
            'update item' => [
                'href' => route('items.update', $id),
                'method' => 'PUT',
                'params' => 'price, stock, description, expiration_date, min_allowed_stock'
            ],
            'delete item' => [
                'href' => route('items.destroy', $id),
                'method' => 'DELETE'
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'price' => 'required|numeric|min:0|max:10000',
            'stock' => 'required|numeric|min:0|max:100000',
            'description' => 'required|string|min:3|max:1000',
            'expiration_date' => 'required|date|after:today',
            'min_allowed_stock' => 'required|numeric|min:0|max:100000',
        ]);

        if (auth()->user()->role !== 'custome') {
            $item = Item::find($id);
            $item->price = $request->price;
            $item->stock = $request->stock;
            $item->description = $request->description;
            $item->expiration_date = $request->expiration_date;
            $item->min_allowed_stock = $request->min_allowed_stock;

            if ($item->save()) {

                return response()->json([
                    'msg' => 'the item is updated successfully',
                    'data' => [
                        'subcategory' => $item->subcategory->name,
                        'user' => $item->user->name,
                        'price' => $item->price,
                        'stock' => $item->stock,
                        'brand' => $item->brand,
                        'description' => $item->description,
                        'expiration_date' => $item->expiration_date,
                        'min_allowed_stock' => $item->min_allowed_stock,
                        'href' => route('items.show', $item->id),
                    ],
                ], 201);
            } else {
                return response()->json([
                    'error' => 'an error occur during update item'
                ], 202);
            }
        } else {
            return response()->json([
                'msg' => 'You have not permission to update item'
            ], 401);
        }
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'custome') {
            $item = Item::find($id);
            $images = $item->images;

            if ($item->delete()) {

                foreach ($images as $img) {
                    $img_name = $img->name;
                    if($img->delete()) {
                        if (is_file(public_path('uploads\\' . $img_name))) {
                            unlink(public_path('uploads\\' . $img_name));
                        }
                    }
                }

                return response()->json([
                    'msg' => 'the item is deleted successfully',
                    'items list' => [
                        'href' => route('items.index'),
                        'method' => 'GET'
                    ],
                    'create item' => [
                        'href' => route('items.store'),
                        'method' => 'POST',
                        'params' => 'subcategory_id, price, stock, brand, description, expiration_date, min_allowed_stock',
                        'optional_params' => 'image1, image2, ......'
                    ]
                ], 201);
            } else {
                return response()->json([
                    'error' => 'an error occur during delete item'
                ], 202);
            }
        } else {
            return response()->json([
                'msg' => 'You have not permission to delete item'
            ], 401);
        }
    }
}
