<?php

declare(strict_types=1);

namespace HazardTeam\AutoClearChunk;

use JackMD\UpdateNotifier\UpdateNotifier;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat;

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
            function (int $currentTick): void {
                $this->clearChunk();
            }
        ), 20 * $interval, 20 * $interval);

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

    public function onLevelChange(EntityLevelChangeEvent $event): void
    {
        $entity = $event->getEntity();
        $levelName = $event->getOrigin()->getFolderName();

        if ($event->isCancelled()) {
            return;
        }

        if (!$entity instanceof Player) {
            return;
        }

        if (!in_array($levelName, $this->getConfig()->getAll()["blacklisted-worlds"])) {
            $this->worlds[] = $levelName;
        }
    }

    public function clearChunk(): void
    {
        $cleared = 0;

        foreach ($this->worlds as $world) {
            $worlds = $this->getServer()->getLevelByName($world);

            if ($worlds !== null) {
                foreach ($worlds->getChunks() as $chunk) {
                    $count = count($worlds->getChunkPlayers($chunk->getX(), $chunk->getZ())); //Check if the player is in the chunk

                    if ($count === 0) {
                        $cleared += 1;
                        $worlds->unloadChunk($chunk->getX(), $chunk->getZ());
                    }
                }
            }
        }

        $message = TextFormat::colorize($this->getConfig()->get("message", "Successfully cleared {COUNT} chunks"));
        $this->getServer()->broadcastMessage(str_replace("{COUNT}", (string) $cleared, $message));
    }
}
