<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreUnitRequest extends FormRequest
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
        $unitId = $this->route('unit');

        return [
            'name' => 'required|string|max:255|unique:units,name' . ($unitId ? ',' . $unitId : ''),
            'abbreviation' => 'required|string|max:10|unique:units,abbreviation' . ($unitId ? ',' . $unitId : ''),
            'description' => 'nullable|string',
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
            'name.required' => 'Nama satuan harus diisi.',
            'name.string' => 'Nama satuan harus berupa teks.',
            'name.max' => 'Nama satuan maksimal 255 karakter.',
            'name.unique' => 'Nama satuan sudah terdaftar.',
            'abbreviation.required' => 'Singkatan satuan harus diisi.',
            'abbreviation.string' => 'Singkatan satuan harus berupa teks.',
            'abbreviation.max' => 'Singkatan satuan maksimal 10 karakter.',
            'abbreviation.unique' => 'Singkatan satuan sudah terdaftar.',
            'description.string' => 'Deskripsi harus berupa teks.',
        ];
    }
}
