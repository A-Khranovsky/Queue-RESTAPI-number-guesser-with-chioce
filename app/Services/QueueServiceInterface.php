<?php


namespace App\Services;


use Illuminate\Http\Request;

interface QueueServiceInterface
{
    public function getConfigParams(Request $request);

    public function start(Request $request);

    public static function show(Request $request);

    public static function total();

    public function clear();

    public function result();

    public function cancel();
}