<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Requests\UpdateStatusItemRequest;
use App\Models\Item;
use App\Http\Controllers\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ItemController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::query()->orderBy('created_at', 'desc');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%')
                ->orWhere('id', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->laporan) {
            $query->where('user_id', auth()->user()->id);
        }

        $items = $query->get();

        return $this->successResponse(
            $items,
            'Items retrieved successfully.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($code)
    {
        $item = Item::with(['user','chats.user'])->where('id', $code)->firstOrFail();
    
    return $this->successResponse($item, 'Item retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();    
        $payload = $request->only(['name', 'description', 'location', 'type', 'status']);

        $path = $request->file('image')->store('images', 'public');

        $payload['image'] = basename($path);
        $payload['user_id'] = $user->id;
        $payload['status'] = $payload['status'] ?? Item::STATUS_PENDING;

        $item = Item::create($payload);

        return $this->successResponse(
            $item,
            'Laporan berhasil dikirim.',
            201
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $payload = $request->only(['name', 'description', 'location', 'type', 'status']);

        // Handle image upload if new image is provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image) {
                Storage::disk('public')->delete('images/' . $item->image);
            }
            
            // Store new image
            $path = $request->file('image')->store('images', 'public');
            $payload['image'] = basename($path);
        }

        $item->update($payload);

        return $this->successResponse(
            $item,
            'Laporan berhasil diperbarui.',
            200
        );
    }

    public function updateStatus(UpdateStatusItemRequest $request, Item $item)
    {
        $payload = $request->validated();
        $item->update($payload);

        return $this->successResponse(
            null,
            'Item status updated successfully.',
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return $this->successResponse(
            null,
            'Item deleted successfully.'
        );
    }
}