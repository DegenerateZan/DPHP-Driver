<?php

namespace DPHPDriver;

use Discord\Discord;

/**
 * A Discord Ready Driver
 */
class Ready
{
    /**
     * @var array $option A discord options.
     */
    private array $option;

    /**
     * @var Discord|null $discord A discord object.
     */
    private ?Discord $discord = null;

    /**
     * @var array $closures A collection of closures that'll be executed after the ready event loop has initiated.
     */
    private array $closures = [];

    /**
     * @var Listeners|null $listeners A listeners object.
     */
    private ?Listeners $listeners = null;

    /**
     * @var string $readyString A string that will be echoed after the ready event loop has initiated. Default value: "Bot is Ready!\n".
     */
    private string $readyString = "Bot is Ready!\n";

    /**
     * Ready constructor.
     *
     * @param array $discordOptions
     */
    public function __construct(array $discordOptions)
    {
        $this->option = $discordOptions;
    }

    /**
     * Begin Discord API initialization.
     */
    public function initDiscord(): void
    {
        $discord = new Discord($this->option);
        $discord->on("init", function (Discord $discord) {
            $this->echoReadyString();
            $this->discord = $discord;
            $this->executeReadyClosures($discord);
            $this->executeEventListeners($discord);
        });
        $this->discord = $discord;
    }

    /**
     * Invoke the Discord API run() method.
     */
    public function start(): void
    {
        $this->discord->run();
    }

    /**
     * Get the Discord instance.
     *
     * @return Discord|null
     */
    public function getDiscord(): ?Discord
    {
        return $this->discord;
    }

    /**
     * Inject a closure to be executed after ready/init has initiated.
     *
     * @param callable $callback
     */
    public function injectReadyClosure(callable $callback): void
    {
        $this->closures[] = $callback;
    }

    /**
     * Assign a whole blob of listener objects to process their assigned event closures.
     *
     * @param Listeners $listeners
     */
    public function addListeners(Listeners $listeners): void
    {
        $this->listeners = $listeners;
    }

    /**
     * Add a string to be echoed after the ready event loop has initiated.
     *
     * @param string $readyString
     */
    public function addReadyString(string $readyString): void
    {
        $this->readyString = $readyString;
    }

    /**
     * Echo the ready string.
     */
    private function echoReadyString(): void
    {
        echo $this->readyString;
    }

    /**
     * Execute the assigned ready closures.
     *
     * @param Discord $discord
     */
    private function executeReadyClosures(Discord $discord): void
    {
        foreach ($this->closures as $closure) {
            $closure($discord);
        }
    }

    /**
     * Execute the assigned event listeners.
     *
     * @param Discord $discord
     */
    private function executeEventListeners(Discord $discord): void
    {
        if ($this->listeners !== null) {
            $this->listeners->execEventListeners($discord);
        }
    }
}
