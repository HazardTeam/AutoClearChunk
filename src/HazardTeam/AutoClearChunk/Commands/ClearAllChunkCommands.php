<?php

namespace HazardTeam\AutoClearChunk\Commands;

use HazardTeam\AutoClearChunk\AutoClearChunk;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;

class ClearAllChunkCommands extends Command implements PluginOwned {

	/** @var AutoClearChunk $plugin */
    private $plugin;

    /**
     * ClearAllChunkCommands constructor.
     * @param AutoClearChunk $plugin
     */
    public function __construct(AutoClearChunk $plugin) {
		$this->plugin = $plugin;
		parent::__construct("clearallchunk", "Clear Chunk Commands");
        $this->setPermission("autoclearchunk.clearchunkall");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
    	if (!$this->testPermission($sender)) return false;
    
		// now clear the world chunk
		// message clear chunk only goes to CommandSender
		$this->getOwningPlugin()->clearAllChunk($sender);
        return true;
	}
	
	public function getOwningPlugin(): AutoClearChunk{
        return $this->plugin;
    }
}
