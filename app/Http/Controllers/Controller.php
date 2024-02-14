<?php

namespace App\Http\Controllers;

use App\Services\AuthUserService;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\View;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\Http\HtmxResponse;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;
use Orkester\Manager;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected object $data;
    protected string $notify;
    protected string $hx_trigger;

    public function __construct(
        protected readonly HtmxRequest $request
    )
    {
        $this->data = Manager::getData();
        $this->data->currentUrl = $request->getCurrentUrl() ?? '/' . $request->path();
        $this->notify = '';
        $this->hx_trigger = '';
    }

    public function render(string $view, ?string $fragment = null)
    {
        if (str_contains($view, '.')) {
            $viewName = $view;
        } else {
            $class = get_called_class();
            $viewName = str_replace("\\", ".", str_replace("Controller", "", str_replace("App\\Http\\Controllers\\", "", $class))) . ".{$view}";
        }
        if (is_null($fragment)) {
            $response = response()
                ->view($viewName, ['data' => $this->data]);
        } else {
            $response = view()->renderFragment($viewName, $fragment,['data' => $this->data]);
        }
        if ($this->notify != '') {
            $response->header('HX-Trigger', $this->notify);
        }
        if ($this->hx_trigger != '') {
            $response->header('HX-Trigger', $this->hx_trigger);
        }
        return $response;
    }

    public function clientRedirect(string $url)
    {
        return new HtmxResponseClientRedirect($url);
    }

    public function notify($type, $message): string
    {
//        HX-Trigger: {"showMessage":"Here Is A Message"}
        $this->notify = json_encode([
            'notify' => [
                'type' => $type,
                'message' => $message
            ]
        ]);
        return $this->notify;
    }

    public function trigger(string $trigger)
    {
        $this->hx_trigger = $trigger;
    }

    public function renderNotify($type, $message)
    {
        if ($this->hx_trigger != '') {
            $trigger = json_encode([
                'notify' => [
                    'type' => $type,
                    'message' => $message
                ],
                $this->hx_trigger => []
            ]);
        } else {
            $trigger = $this->notify($type, $message);
        }
        $response = response('', 204)->header('HX-Trigger', $trigger);
        return $response;
    }

}
