<?php

declare(strict_types=1);

namespace HazardTeam\AutoClearChunk;

use HazardTeam\AutoClearChunk\Commands\ClearChunkCommands;
use HazardTeam\AutoClearChunk\Commands\ClearAllChunkCommands;
use JackMD\UpdateNotifier\UpdateNotifier;
use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\event\world\WorldLoadEvent;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;
use pocketmine\command\CommandSender;

class AutoClearChunk extends PluginBase implements Listener
{
    private array $worlds = [];

    public function onEnable(): void
    {
        $this->checkConfig();

        $interval = $this->getConfig()->get("clear-interval", 600);

        foreach (array_diff(scandir($this->getServer()->getDataPath() . "worlds"), ["..", "."]) as $levelName) {
            if (!in_array($levelName, $this->getConfig()->getAll()["blacklisted-worlds"])) {
                $this->worlds[] = $levelName;
            }
        }

        $this->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask(
            function (){
                $this->clearAllChunk();
            }
        ), 20 * $interval, 20 * $interval);
        
        $this->getServer()->getCommandMap()->register("AutoClearChunk", new ClearChunkCommands($this));
        $this->getServer()->getCommandMap()->register("AutoClearChunk", new ClearAllChunkCommands($this));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());
    }

    private function checkConfig(): void
    {
        $this->saveDefaultConfig();

        foreach ([
            "clear-interval" => "integer",
            "message" => "string",
            "blacklisted-worlds" => "array"
        ] as $option => $expectedType) {
            if (($type = gettype($this->getConfig()->getNested($option))) != $expectedType) {
                throw new \TypeError("Config error: Option ($option) must be of type $expectedType, $type was given");
            }
        }
    }

    public function onWorldLoad(WorldLoadEvent $event): void
    {
        $levelName = $event->getWorld()->getFolderName();
        
        if (!in_array($levelName, $this->getConfig()->getAll()["blacklisted-worlds"])) {
            $this->worlds[] = $levelName;
        }
    }

    public function clearAllChunk(CommandSender $sender = null): void
    {
        $cleared = 0;

        foreach ($this->worlds as $world) {
            $worlds = $this->getServer()->getWorldManager()->getWorldByName($world);

            if ($worlds !== null) {
                foreach ($worlds->getLoadedChunks() as $chunkHash => $chunk) {
					World::getXZ($chunkHash, $chunkX, $chunkZ); // For getting chunk X and Z
                    $count = count($worlds->getChunkPlayers($chunkX, $chunkZ)); //Check if the player is in the chunk
                    if ($count === 0) {
                        $cleared += 1;
                        $worlds->unloadChunk($chunkX, $chunkZ); // Unload Chunk
                    }
                }
            }
        }
		if($sender == null){
     	   $message = TextFormat::colorize($this->getConfig()->get("message", "Successfully cleared {COUNT} chunks"));
   	     $this->getServer()->broadcastMessage(str_replace("{COUNT}", "" . $cleared, $message));
   	} else {
   		$message = TextFormat::colorize($this->getConfig()->get("message", "Successfully cleared {COUNT} chunks"));
   	    $sender->sendMessage(str_replace("{COUNT}", "" . $cleared, $message));
   	}
    }
    
    public function clearChunk(World|string $world, CommandSender $sender = null): bool{
    	$cleared = 0;
    	if(is_string($world)){
    		if($this->getServer()->getWorldManager()->getWorldByName($world) == null){
    			$sender->sendMessage(TextFormat::RED . "Worlds with name " . TextFormat::WHITE . $world . TextFormat::RED . " Not generated");
    			return false;
    		}
    		$world = $this->getServer()->getWorldManager()->getWorldByName($world);
    	}
    	foreach ($world->getLoadedChunks() as $chunkHash => $chunk) {
			World::getXZ($chunkHash, $chunkX, $chunkZ); // For getting chunk X and Z
            $count = count($world->getChunkPlayers($chunkX, $chunkZ)); //Check if the player is in the chunk
            if($count === 0) {
                $cleared += 1;
                $world->unloadChunk($chunkX, $chunkZ); // Unload Chunk
            }
        }
        $message = TextFormat::colorize($this->getConfig()->get("message", "Successfully cleared {COUNT} chunks"));
        $sender->sendMessage(str_replace("{COUNT}", "" . $cleared, $message));
        return true;
    }
}
