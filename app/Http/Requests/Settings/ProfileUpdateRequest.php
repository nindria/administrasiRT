<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];

        // Add validation for warga data if user has 'warga' role and data is present
        if ($this->user()->hasRole('warga') && $this->has('wargaData')) {
            $rules['wargaData.nik'] = ['required', 'string', 'max:16'];
            $rules['wargaData.full_name'] = ['required', 'string', 'max:255'];
            $rules['wargaData.tempat_lahir'] = ['nullable', 'string', 'max:255'];
            $rules['wargaData.tanggal_lahir'] = ['nullable', 'date'];
            $rules['wargaData.status'] = ['nullable', 'string', 'max:50'];
        }

        // Only validate kartuKeluargaData if it exists in the request
        if ($this->has('kartuKeluargaData')) {
            $rules['kartuKeluargaData.no_kk'] = ['nullable', 'string', 'max:16'];
            $rules['kartuKeluargaData.jumlah_anggota'] = ['required', 'integer', 'min:1'];
            $rules['kartuKeluargaData.foto_ktp_kepala_keluarga'] = ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'];
        }

        // Only validate rumahData if it exists in the request
        if ($this->has('rumahData')) {
            $rules['rumahData.perumahan'] = ['nullable', 'string', 'max:255'];
            $rules['rumahData.jalan'] = ['nullable', 'string', 'max:255'];
            $rules['rumahData.blok'] = ['nullable', 'string', 'max:10'];
            $rules['rumahData.nomor'] = ['nullable', 'string', 'max:10'];
        }

        return $rules;
    }
}
