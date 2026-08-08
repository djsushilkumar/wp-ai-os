<?php

declare(strict_types=1);

namespace WPAIOS\Core\Event;

use WPAIOS\Contracts\EventDispatcherInterface;

/**
 * Enterprise Event Dispatcher supporting prioritized listeners, deferred queues, and WP hooks.
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var array<string, ListenerRegistration[]>
     */
    private array $listeners = [];

    /**
     * @var array<array{eventName: string, payload: array<mixed>}>
     */
    private array $queuedEvents = [];

    public function __construct(private ?WpHooksBridge $wpBridge = null)
    {
        $this->wpBridge = $wpBridge ?? new WpHooksBridge();
    }

    /**
     * Subscribe a listener callback or class instance to an event with optional priority.
     *
     * @param string   $eventName
     * @param callable $listener
     * @param int      $priority Lower numbers execute earlier (e.g. 10 is default).
     * @return void
     */
    public function listen(string $eventName, callable $listener, int $priority = 10): void
    {
        $this->listeners[ $eventName ][] = new ListenerRegistration($listener, $priority);

        // Sort listeners by priority ascending
        usort(
            $this->listeners[ $eventName ],
            static function (ListenerRegistration $a, ListenerRegistration $b) {
                return $a->priority <=> $b->priority;
            }
        );
    }

    /**
     * Dispatch an event payload immediately to listeners and WP hooks.
     *
     * @param string $eventName
     * @param mixed  ...$payload
     * @return void
     */
    public function dispatch(string $eventName, mixed ...$payload): void
    {
        if (isset($this->listeners[ $eventName ])) {
            foreach ($this->listeners[ $eventName ] as $registration) {
                $callable = $registration->listener;
                $callable(...$payload);
            }
        }

        $this->wpBridge->doAction($eventName, ...$payload);
    }

    /**
     * Push an event to the queue for deferred background execution.
     *
     * @param string $eventName
     * @param mixed  ...$payload
     * @return void
     */
    public function queue(string $eventName, mixed ...$payload): void
    {
        $this->queuedEvents[] = [
            'eventName' => $eventName,
            'payload'   => $payload,
        ];
    }

    /**
     * Process all queued deferred events.
     *
     * @return void
     */
    public function flushQueue(): void
    {
        while (! empty($this->queuedEvents)) {
            $event = array_shift($this->queuedEvents);
            $this->dispatch($event['eventName'], ...$event['payload']);
        }
    }

    /**
     * Filter a value through listeners and WordPress filters.
     *
     * @param string $filterName
     * @param mixed  $value
     * @param mixed  ...$args
     * @return mixed
     */
    public function filter(string $filterName, mixed $value, mixed ...$args): mixed
    {
        $current = $value;

        if (isset($this->listeners[ $filterName ])) {
            foreach ($this->listeners[ $filterName ] as $registration) {
                $callable = $registration->listener;
                $current  = $callable($current, ...$args);
            }
        }

        return $this->wpBridge->applyFilters($filterName, $current, ...$args);
    }
}
