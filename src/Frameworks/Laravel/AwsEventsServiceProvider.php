<?php
namespace STS\AwsEvents\Frameworks\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use STS\AwsEvents\Events\Event;
use STS\AwsEvents\Events\Sns;

class AwsEventsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app['router']->post(
            '_sns', function (Request $request) {
                $this->app['events']->dispatch($event = Event::make($request->getContent()));

                if($event instanceof Sns && $event->containsEvent()) {
                    $this->app['events']->dispatch($event->getContainedEvent());
                }
            }
        );
    }

    public function boot()
    {

    }
}