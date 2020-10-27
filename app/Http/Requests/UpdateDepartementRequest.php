<?php

namespace App\Http\Requests;

use App\Models\Departement;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDepartementRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('departement_edit');
    }

    public function rules()
    {
        return [
            'dept_name' => [
                'string',
                'max:250',
                'nullable',
            ],
        ];
    }
}
