<?php

namespace DPHPDriver;

use Discord\Discord;


/**
 * A Events Listener closures Manager 
 */
class Listeners {

    private $listeners = [];
    
    /**
     * to execute the assigned Event Listener closures
     *
     * @return void
     */
    public function execEventListeners(Discord $discord)
    {
        foreach ($this->listeners as $listener){
            $type = $listener["eventType"];
            $callback = $listener["callback"];
            $discord->on($type, $callback);
        }
    }

    
    /**
     * addEventListener a.k.a assign the Event Listener closure
     *
     * NOTE : Don't forget to call execEventLoop() to execute the assigned closures
     * @param  Discord\WebSockets\Event $eventType Const
     * @param  callback $callback
     * @return this
     */
    public function injectEventListener($eventType,callable $callback){
        $listener = [
            "eventType" => $eventType,
            "callback"  => $callback
        ];
        array_push($this->listeners, $listener);
        return $this;
    }

    /**
     * to truncate assigned Events closures
     *
     * @return void
     */
    public function truncateEventsClosure(){
        if(!is_null($this->listeners)){
            unset($this->listeners);
        }
    }
}