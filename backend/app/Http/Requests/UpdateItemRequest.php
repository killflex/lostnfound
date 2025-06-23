<?php

namespace App\Http\Requests;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:10240',
            'type' => ['sometimes', new Enum(ItemType::class)],
            'status' => ['sometimes', new Enum(ItemStatus::class)],        ];
    }
}