<?php

namespace HazardTeam\AutoClearChunk\Commands;

use HazardTeam\AutoClearChunk\AutoClearChunk;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;

class ClearChunkCommands extends Command implements PluginOwned {

	/** @var AutoClearChunk $plugin */
    private $plugin;

    /**
     * ClearChunkCommands constructor.
     * @param AutoClearChunk $plugin
     */
    public function __construct(AutoClearChunk $plugin) {
		$this->plugin = $plugin;
		parent::__construct("clearchunk", "Clear Chunk Commands");
        $this->setPermission("autoclearchunk.clearchunk");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
    	if (!$this->testPermission($sender)) return false;
    
    	// if from console they have to enter world name, and if player name world is optional
		$world = null;
    	if(!$sender instanceof Player){
    		if(!isset($args[0])){
    			$sender->sendMessage(TextFormat::RED . "Please input world name");
    			return false;
    		}
    		$world = implode(" ", $args);
    	} else {
    		// Check if $world variable null
			if($world == null){
				// for add custom world optional
				if(isset($args[0])){
					$world = implode(" ", $args);
				} else {
					// if args 0 null this will get player world
					$world = $sender->getWorld();
				}
			}
		}
		// now clear the world chunk
		$this->getOwningPlugin()->clearChunk($world, $sender);
        return true;
	}
	
	public function getOwningPlugin(): AutoClearChunk{
        return $this->plugin;
    }
}
