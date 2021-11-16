<?php

namespace App\Http\Requests\Dashboard\Country;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $country = $this->country ? $this->country->id : null;
        $rules=[
            'phonecode' => 'required|numeric|digits_between:2,5|unique:countries,phonecode,'.$country,
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules[$locale.'.name'] = 'required|string|between:3,250|unique:country_translations,name,'.$country.',country_id';
            $rules[$locale.'.nationality'] = 'nullable|string|between:2,250';
        }
        return $rules;
    }
}
