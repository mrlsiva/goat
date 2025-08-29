<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Gender;
use DB;

class genderController extends Controller
{
    use ResponseHelper;

    public function list(Request $request)
    {
        $genders = Gender::all();

        return $this->successResponse($genders, 200, 'Successfully returned all gender.');
    }
}
