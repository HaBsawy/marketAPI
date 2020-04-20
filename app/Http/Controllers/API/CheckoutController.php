<?php

namespace App\Http\Controllers\API;

use App\Checkout;
use App\Http\Controllers\Controller;
use App\Http\Resources\CheckoutCollection;
use App\Http\Resources\CheckoutResource;
use App\Item;
use App\User;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        return (CheckoutCollection::collection(Checkout::paginate(10)))->additional([
            'msg' => 'checkouts list',
            'create checkout' => [
                'href' => route('checkouts.store'),
                'method' => 'POST',
                'params' => '"items": {"0": {"item_id": "1"},"1": {"item_id": "2"},"2": {"item_id": "3"}, .......}',
            ]
        ]);
    }

    public function userCheckouts(User $user)
    {
        return CheckoutCollection::collection(Checkout::where('user_id', $user->id)->paginate(10));
    }

    public function store(Request $request)
    {
        $checkout = new Checkout();
        $checkout->user_id = auth()->user()->id;
        $checkout->status = 'prepared';

        if ($checkout->save()) {
            foreach ($request->items as $item) {
                if (!empty(Item::find($item['item_id']))) {
                    $checkout->items()->attach($item['item_id']);
                }
            }

            return response()->json([
                'msg' => 'the checkout is created successfully',
                'data' => [
                    'user' => $checkout->user->name,
                    'status' => $checkout->status,
                    'href' => route('checkouts.show', $checkout->id)
                ],
            ], 201);
        } else {
            return response()->json([
                'error' => 'an error occur during create checkout'
            ], 202);
        }
    }

    public function show($id)
    {
        return (new CheckoutResource(Checkout::find($id)))->additional([
            'update checkout' => [
                'href' => route('checkouts.update', $id),
                'method' => 'PUT',
                'params' => 'status'
            ],
            'delete checkout' => [
                'href' => route('checkouts.destroy', $id),
                'method' => 'DELETE'
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|in:prepared,sent,delivered & paid'
        ]);

        if (auth()->user()->role !== 'custome') {
            $checkout = Checkout::find($id);
            $checkout->status = $request->status;

            if ($checkout->save()) {
                return response()->json([
                    'msg' => 'the checkout is updated successfully',
                    'data' => [
                        'user' => $checkout->user->name,
                        'status' => $checkout->status,
                        'href' => route('checkouts.show', $checkout->id)
                    ],
                ], 201);
            } else {
                return response()->json([
                    'error' => 'an error occur during update checkout'
                ], 202);
            }
        } else {
            return response()->json([
                'msg' => 'You have not permission to update checkout'
            ], 401);
        }
    }

    public function destroy($id)
    {
        $checkout = Checkout::find($id);

        if ($checkout->user_id == auth()->user()->id) {
            $checkout->items()->detach();

            if ($checkout->delete()) {
                return response()->json([
                    'msg' => 'the checkout is deleted successfully',
                    'checkout list' => [
                        'href' => route('checkouts.index'),
                        'method' => 'GET'
                    ],
                    'create checkout' => [
                        'href' => route('checkouts.store'),
                        'method' => 'POST',
                        'params' => '',
                    ]
                ], 201);
            } else {
                return response()->json([
                    'error' => 'an error occur during delete checkout'
                ], 202);
            }
        } else {
            return response()->json([
                'msg' => 'You have not permission to delete checkout'
            ], 401);
        }
    }
}
