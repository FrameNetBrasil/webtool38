<?php

namespace App\Http\Middleware;

use App\Services\AppService;
use App\Services\AuthUserService;
use Closure;
use Illuminate\Http\Request;
use Orkester\Manager;
use Symfony\Component\HttpFoundation\Response;

class Data
{
    public function handle(Request $request, Closure $next): Response
    {
//        ddump('=================== in data middleware');
        $data = $request->all();
        foreach ($data as $id => $value) {
            if (str_contains($id, '_') && ($id != '_token')) {
                $var = explode('_', $id);
                $attr = $var[1];
                $data[$var[0]] ??= (object)[];
                if (isset($var[2])) {
                    $extra = $var[2];
                    $data[$var[0]]->$attr ??= (object)[];
                    $data[$var[0]]->$attr->$extra = $value;
                } else {
                    $data[$var[0]]->$attr = $value;
                }
            }
        }
        Manager::setData((object)$data);
        return $next($request);
    }
}
