<?php

namespace App\Http\Controllers;


use App\Models\User;


class UsersController extends Controller
{

    public function index()
    {
        $model = new User();

        dd($model->getResult());
    }

}
