<?php declare(strict_types=1);

namespace STS\AwsEvents\Frameworks\Laravel;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use STS\AwsEvents\Events\Event;
use STS\AwsEvents\Events\Sns;

class AwsEventsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app['router']->post(
            '_sns',
            function (Request $request): void {
                $this->app['events']->dispatch($event = Event::make($request->getContent()));

                if ($event instanceof Sns && $event->containsEvent()) {
                    $this->app['events']->dispatch($event->getContainedEvent());
                }
            }
        );
    }

    public function boot(): void
    {
    }
}
