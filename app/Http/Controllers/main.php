<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\checks;

class main extends Controller
{
    public function index() {
        $new_check = new checks();
        $logicer = new Logic();
        $result = '';
        if (isset($_POST['user-input'])) {
            $data = $_POST['user-input'];
            $new_check->input = $data;
            if ($data) {
                $data = preg_split('//u', $data, -1, PREG_SPLIT_NO_EMPTY);
                $cnt_ru = $logicer->cnt_ru($data);
                $cnt_en = $logicer->cnt_en($data);
                if ($cnt_ru >= $cnt_en) {
                    $new_check->lang = 'ru';
                    for ($i = 0; $i < count($data); ++$i) {
                        if ($logicer->is_en($data[$i])) {
                            $j = $i + 1;
                            while ($j < count($data)) {
                                ++$j;
                            }
                            $result .= '<span class="wrong-char">'.$data[$i].'</span>';
                        } else {
                            $result .= $data[$i];
                        }
                    }
                } else {
                    $new_check->lang = 'en';
                    for ($i = 0; $i < count($data); ++$i) {
                        if ($logicer->is_ru($data[$i])) {
                            $result .= '<span class="wrong-char">'.$data[$i].'</span>';
                        } else {
                            $result .= $data[$i];
                        }
                    }
                }
                $new_check->result = $result;
                $new_check->save();
            }
        }
        return view('main', ['data' => [$result, $new_check->lang]]);
    }
}

class Logic {
    public function cnt_ru($str): int {
        $cnt = 0;
        for ($i = 0; $i < count($str); ++$i) {
            $cnt += $this->is_ru($str[$i]) ? 1 : 0;
        }
        return $cnt;
    }
    public function cnt_en($str): int {
        $cnt = 0;
        for ($i = 0; $i < count($str); ++$i) {
            $cnt += $this->is_en($str[$i]) ? 1 : 0;
        }
        return $cnt;
    }
    public function is_ru($char): bool {
        return ($char >= 'а' && $char <='я') || ($char >= 'А' && $char <= 'Я');
    }
    public function is_en($char): bool {
        return ($char >= 'a' && $char <='z') || ($char >= 'A' && $char <= 'Z');
    }
}