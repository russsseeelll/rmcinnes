<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{

    public function tools()
    {
        $tools = Tool::all();

        return view('welcome', ['tools' => $tools]);
    }

}
