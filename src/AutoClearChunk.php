<?php

/*
 * Copyright (c) 2021-2023 HazardTeam
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/HazardTeam/AutoClearChunk
 */

declare(strict_types=1);

namespace hazardteam\autoclearchunk;

use hazardteam\autoclearchunk\commands\ClearAllChunkCommand;
use hazardteam\autoclearchunk\commands\ClearChunkCommand;
use JackMD\UpdateNotifier\UpdateNotifier;
use pocketmine\event\Listener;
use pocketmine\event\world\WorldLoadEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;
use function class_exists;
use function count;
use function in_array;
use function is_array;
use function is_int;
use function is_string;
use function sprintf;
use function trim;

class AutoClearChunk extends PluginBase implements Listener {
	private int $clearInterval;
	private string $clearChunkMessage;
	private string $clearChunkBroadcastMessage;
	private string $clearAllChunkMessage;
	private string $clearAllChunkBroadcastMessage;
	private array $blacklistedWorlds;

	private array $worlds = [];

	public function onEnable() : void {
		if (!class_exists(UpdateNotifier::class)) {
			$this->getLogger()->error("The 'UpdateNotifier' virion is missing. Please download it from the plugin's page on Poggit: https://poggit.pmmp.io/p/AutoClearChunk");
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}

		$this->loadConfig();

		$worldsDirectory = new \DirectoryIterator($this->getServer()->getDataPath() . 'worlds');
		foreach ($worldsDirectory as $fileInfo) {
			if (!$fileInfo->isDot() && $fileInfo->isDir()) {
				$worldName = $fileInfo->getFilename();
				if (!in_array($worldName, $this->getBlacklistedWorlds(), true)) {
					$this->worlds[] = $worldName;
				}
			}
		}

		$this->getScheduler()->scheduleDelayedRepeatingTask(
			new ClosureTask(fn () => $this->clearAllChunk(function (int $cleared) {
				$broadcastMessage = sprintf(
					TextFormat::colorize($this->getClearAllChunkBroadcastMessage()),
					$cleared
				);
				$this->getServer()->broadcastMessage($broadcastMessage);
			})),
			20 * $this->clearInterval,
			20 * $this->clearInterval
		);

		$commandMap = $this->getServer()->getCommandMap();
		$commandMap->registerAll('AutoClearChunk', [
			new ClearChunkCommand($this),
			new ClearAllChunkCommand($this),
		]);

		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());
	}

	private function loadConfig() : void {
		$this->saveDefaultConfig();

		$config = $this->getConfig();

		// Validate clear-interval option
		$clearInterval = $config->get('clear-interval');
		if (!is_int($clearInterval) || $clearInterval <= 0) {
			throw new \InvalidArgumentException("Config error: 'clear-interval' must be a positive integer");
		}
		$this->clearInterval = $clearInterval;

		// Validate clearchunk-message option
		$clearChunkMessage = $config->get('clearchunk-message');
		if (!is_string($clearChunkMessage) || trim($clearChunkMessage) === '') {
			throw new \InvalidArgumentException("Config error: 'clearchunk-message' must be a non-empty string");
		}
		$this->clearChunkMessage = $clearChunkMessage;

		// Validate clearchunk-broadcast-message option
		$clearChunkBroadcastMessage = $config->get('clearchunk-broadcast-message');
		if (!is_string($clearChunkBroadcastMessage) || trim($clearChunkBroadcastMessage) === '') {
			throw new \InvalidArgumentException("Config error: 'clearchunk-broadcast-message' must be a non-empty string");
		}
		$this->clearChunkBroadcastMessage = $clearChunkBroadcastMessage;

		// Validate clearallchunk-message option
		$clearAllChunkMessage = $config->get('clearallchunk-message');
		if (!is_string($clearAllChunkMessage) || trim($clearAllChunkMessage) === '') {
			throw new \InvalidArgumentException("Config error: 'clearallchunk-message' must be a non-empty string");
		}
		$this->clearAllChunkMessage = $clearAllChunkMessage;

		// Validate clearallchunk-broadcast-message option
		$clearAllChunkBroadcastMessage = $config->get('clearallchunk-broadcast-message');
		if (!is_string($clearAllChunkBroadcastMessage) || trim($clearAllChunkBroadcastMessage) === '') {
			throw new \InvalidArgumentException("Config error: 'clearallchunk-broadcast-message' must be a non-empty string");
		}
		$this->clearAllChunkBroadcastMessage = $clearAllChunkBroadcastMessage;

		// Validate blacklisted-worlds option
		$blacklistedWorlds = $config->get('blacklisted-worlds');
		if (!is_array($blacklistedWorlds)) {
			throw new \InvalidArgumentException("Config error: 'blacklisted-worlds' must be an array");
		}
		foreach ($blacklistedWorlds as $world) {
			if (!is_string($world) || trim($world) === '') {
				throw new \InvalidArgumentException("Config error: 'blacklisted-worlds' must contain non-empty strings");
			}
		}
		$this->blacklistedWorlds = $blacklistedWorlds;
	}

	public function onWorldLoad(WorldLoadEvent $event) : void {
		$worldName = $event->getWorld()->getFolderName();

		if (!in_array($worldName, $this->getBlacklistedWorlds(), true)) {
			$this->worlds[] = $worldName;
		}
	}

	public function clearAllChunk(callable $callback = null) : void {
		$cleared = 0;

		foreach ($this->worlds as &$world) {
			$worlds = $this->getServer()->getWorldManager()->getWorldByName($world);

			if ($worlds !== null) {
				foreach ($worlds->getLoadedChunks() as $chunkHash => $chunk) {
					World::getXZ($chunkHash, $chunkX, $chunkZ); // For getting chunk X and Z
					if (count($worlds->getChunkPlayers($chunkX, $chunkZ)) === 0) {
						++$cleared;
						$worlds->unloadChunk($chunkX, $chunkZ); // Unload Chunk
					}
				}
			}
		}

		if ($callback !== null) {
			$callback($cleared);
		}
	}

	public function clearChunk(World|string $world, callable $callback = null) : bool {
		$cleared = 0;

		if (is_string($world)) {
			$world = $this->getServer()->getWorldManager()->getWorldByName($world);
			if ($world === null) {
				if ($callback !== null) {
					$callback(0);
				}
				return false;
			}
		}

		foreach ($world->getLoadedChunks() as $chunkHash => $chunk) {
			World::getXZ($chunkHash, $chunkX, $chunkZ); // For getting chunk X and Z
			if (count($world->getChunkPlayers($chunkX, $chunkZ)) === 0) {
				++$cleared;
				$world->unloadChunk($chunkX, $chunkZ); // Unload Chunk
			}
		}

		if ($callback !== null) {
			$callback($cleared);
		}

		return true;
	}

	public function getClearInterval() : int {
		return $this->clearInterval;
	}

	public function getClearChunkMessage() : string {
		return $this->clearChunkMessage;
	}

	public function getClearChunkBroadcastMessage() : string {
		return $this->clearChunkBroadcastMessage;
	}

	public function getClearAllChunkMessage() : string {
		return $this->clearAllChunkMessage;
	}

	public function getClearAllChunkBroadcastMessage() : string {
		return $this->clearAllChunkBroadcastMessage;
	}

	public function getBlacklistedWorlds() : array {
		return $this->blacklistedWorlds;
	}
}
