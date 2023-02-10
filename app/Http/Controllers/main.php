<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\checks;

class Logic {
    public function cnt_ru($str) {
        $cnt = 0;
        for ($i = 0; $i < count($str); ++$i) {
            $cnt += $this->is_ru($str[$i]);
        }

    }
    public function cnt_en($str) {
        $cnt = 0;
        for ($i = 0; $i < count($str); ++$i) {
            $cnt += $this->is_en($str[$i]);
        }
    }
    public function is_ru($char): bool {
        return $char >= 'а' && $char <='я' || $char >= 'А' && $char <= 'Я';
    }
    public function is_en($char): bool {
        return $char >= 'a' && $char <='z' || $char >= 'A' && $char <= 'Z';
    }
}

class main extends Controller
{
    public function index() {
        $new_check = new checks();
        $data = '';
        if (isset($_POST['user-input'])) {
            $data = $_POST['user-input'];
            $new_check->input = $data;
            if ($data) {
                $new_check->save();
            }
        }
        return view('main', ['data' => $data]);
    }
}
