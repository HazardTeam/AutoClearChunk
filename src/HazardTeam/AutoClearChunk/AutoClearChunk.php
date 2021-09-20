<?php
declare(strict_types=1);

namespace HazardTeam\AutoClearChunk;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\scheduler\ClosureTask;

class AutoClearChunk extends PluginBase implements Listener{
	
	private $worlds = [];
    
    public function onEnable(){
        $this->saveDefaultConfig();
        $interval = $this->getConfig()->get("clear-interval") ?? 600;
        foreach (array_diff(scandir($this->getServer()->getDataPath() . "worlds"), ["..", "."]) as $levelName) {
            if(!in_array($levelName, $this->getConfig()->get("blacklisted-worlds"))){
                $this->worlds[] = $levelName;
            }
        }
        $this->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask(
			function(int $currentTick): void{
				$this->clearChunk();
			}
    	), 20 * $interval, 20 * $interval);
    	$this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function onLevelChange(EntityLevelChangeEvent $event) {
        $entity = $event->getEntity();
        if($entity instanceof Player) {
            $name = $event->getOrigin()->getFolderName();
			if(!in_array($name, $this->worlds)){
				if(!in_array($name, $this->getConfig()->get("blacklisted-worlds"))){
             	   $this->worlds[] = $name;
             	}
            }
        }
    }
    
	public function clearChunk(){
		foreach($this->worlds as $world){
			$worlds = $this->getServer()->getLevelByName($world);
			$cleared = 0;
			if($worlds !== null){
				foreach($worlds->getChunks() as $chunk){
					$count = count($worlds->getChunkPlayers($chunk->getX(), $chunk->getZ())); // check if the player is in the chunk
       			 if($count == 0){
       			 	$cleared += 1;
            			$worlds->unloadChunk($chunk->getX(), $chunk->getZ());
            		 }
             	}
			}
		}
		$msg = $this->getConfig()->get("message") ?? "cleared total {COUNT} chunks";
    	$this->getServer()->broadcastMessage(str_replace("{COUNT}", $cleared, $msg));
		return true;
	}
}
