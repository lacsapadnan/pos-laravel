<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:products,code' . ($productId ? ',' . $productId : ''),
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk harus diisi.',
            'name.string' => 'Nama produk harus berupa teks.',
            'name.max' => 'Nama produk maksimal 255 karakter.',
            'code.required' => 'Kode produk harus diisi.',
            'code.string' => 'Kode produk harus berupa teks.',
            'code.max' => 'Kode produk maksimal 50 karakter.',
            'code.unique' => 'Kode produk sudah terdaftar.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'category_id.required' => 'Kategori harus dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'unit_id.required' => 'Satuan harus dipilih.',
            'unit_id.exists' => 'Satuan yang dipilih tidak valid.',
            'price.required' => 'Harga harus diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
            'stock.required' => 'Stok harus diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
            'minimum_stock.required' => 'Minimum stok harus diisi.',
            'minimum_stock.integer' => 'Minimum stok harus berupa angka.',
            'minimum_stock.min' => 'Minimum stok tidak boleh kurang dari 0.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat: jpeg, png, jpg, gif.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'status.required' => 'Status harus dipilih.',
            'status.boolean' => 'Status harus berupa aktif/tidak aktif.',
        ];
    }
}
