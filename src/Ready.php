<?php

namespace DPHPDriver;

use Discord\Discord;


/**
 * A Discord Ready Driver
 */
class Ready {
    
    /**
     * a discord options
     *
     * @var array
     */
    private array $option;  
      
    /**
     * discord object
     *
     * @var Discord
     */
    private Discord $discord;    
    /**
     * a collection of closures that'll be executed after the ready event loop has initiated
     *
     * @var array
     */
    private array $closures = [];
        
    /**
     * listeners object
     *
     * @var Listeners
     */
    private Listeners $listeners;
    
    /**
     * to contains a string that will be echo'ed after the ready event loop has initiated
     *
     * @var string
     */
    private string $sentences = "";

    /**
     *  a class contructor 
     *
     * @param  mixed $discordOptions
     * @return void
     */
    public function __construct(array $discordOptions){
        $this->option = $discordOptions;
    }
    
    /**
     * To Begin Discord Api initialization
     * 
     * @return void
     */
    public function initDiscord(){
     

        $discord = new Discord($this->option);
        $discord->on("init", function(Discord $discord){
            echo $this->sentences;
            $this->discord = $discord;
            foreach($this->closures as $callback){
                $callback($discord);
            }
            if(isset($this->listeners)){
                $this->listeners->execEventListeners($discord);
            }
        });
        $this->discord = $discord;
    }

 
    /**
     * To invoke the discord api run() method;
     *
     * @return void
     */
    public function start(){
        $this->discord->run();
    }

        
    /**
     * to Get Discord Instance
     *
     * @return Discord\Discord
     */
    public function getDiscord(): Discord{
        return $this->discord;
    }

    /**
     * to inject closure that'll be executed after ready / init has initiated
     * @param callable $callback
     * @return void
     */
    public function injectReadyClosure(callable $callback){
        array_push($this->closures, $callback);
    }
        
    /**
     * assign a whole blob of listener object, to process it's assigned events closure
     * 
     * NOTE : not recommended, because its unmodifiable in a runtime after you assign the whole object, unless you know what you're doing! 
     * (recomended to call execEventListeners() inside a ready closure instead)
     *
     * 
     * @return void
     */
    public function addListeners(Listeners $listeners){
        $this->listeners = $listeners;
    }
    
    /**
     * to add any string that will be echo'ed after the ready event loop has initiated
     *
     * @param  string $sentences
     * @return void
     */
    public function addReadyString(string $sentences = "Bot is Ready!\n"){
        $this->sentences = $sentences;
    }

    /**
     * to truncate the assigned ready closures
     *
     * @return void
     */
    public function truncateReadyClosure(){
        if(!isset($this->closures)){
            unset($this->closures);
        }
    }
}