<?php

/*
 * Copyright (c) 2021-2025 HazardTeam
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/HazardTeam/AutoClearChunk
 */

declare(strict_types=1);

namespace hazardteam\autoclearchunk\commands;

use hazardteam\autoclearchunk\AutoClearChunk;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use function sprintf;

class ClearAllChunkCommand extends Command implements PluginOwned {
	public function __construct(
		private AutoClearChunk $plugin
	) {
		parent::__construct('clearallchunk', 'Clears all unloaded chunks in all worlds');
		$this->setPermission('autoclearchunk.command.clearallchunk');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
		if (!$this->testPermission($sender)) {
			return false;
		}

		$plugin = $this->getOwningPlugin();
		$plugin->clearAllChunk(function (int $cleared) use ($sender, $plugin) : void {
			$message = sprintf(
				TextFormat::colorize($plugin->getClearAllChunkMessage()),
				$cleared
			);
			$sender->sendMessage($message);

			if ($plugin->isBroadcastEnabled()) {
				$broadcastMessage = sprintf(
					TextFormat::colorize($plugin->getClearAllChunkBroadcastMessage()),
					$cleared
				);
				$plugin->getServer()->broadcastMessage($broadcastMessage);
			}
		});

		return true;
	}

	public function getOwningPlugin() : AutoClearChunk {
		return $this->plugin;
	}
}
