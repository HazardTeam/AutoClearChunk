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
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use function count;
use function implode;
use function sprintf;

class ClearChunkCommand extends Command implements PluginOwned {
	public function __construct(
		private AutoClearChunk $plugin
	) {
		parent::__construct('clearchunk', 'Clears all unloaded chunks in a specific world');
		$this->setPermission('autoclearchunk.command.clearchunk');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
		if (!$this->testPermission($sender)) {
			return false;
		}

		$world = (count($args) > 0) ? implode(' ', $args) : (($sender instanceof Player) ? $sender->getWorld()->getFolderName() : null);

		if ($world === null) {
			$sender->sendMessage(TextFormat::RED . 'Please input a world name.');
			return false;
		}

		$plugin = $this->getOwningPlugin();
		$plugin->clearChunk($world, function (int $cleared) use ($sender, $plugin, $world) : void {
			$message = sprintf(
				TextFormat::colorize($plugin->getClearChunkMessage()),
				$cleared,
				$world
			);
			$sender->sendMessage($message);

			if ($plugin->isBroadcastEnabled()) {
				$broadcastMessage = sprintf(
					TextFormat::colorize($plugin->getClearChunkBroadcastMessage()),
					$cleared,
					$world
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
