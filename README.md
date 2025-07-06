# AutoClearChunk

[![](https://poggit.pmmp.io/shield.state/AutoClearChunk)](https://poggit.pmmp.io/p/AutoClearChunk)
[![](https://poggit.pmmp.io/shield.dl.total/AutoClearChunk)](https://poggit.pmmp.io/p/AutoClearChunk)

A PocketMine-MP plugin that automatically clears chunks in specified worlds at regular intervals.

# Features
- **Automated Chunk Management**: Automatically clears unloaded chunks in configured worlds to optimize server performance.
- **Flexible Scheduling**: Allows for a configurable interval to automatically clear chunks, adapting to your server's specific needs.
- **Graceful Unloading**: Includes a configurable grace period, ensuring chunks without players remain loaded for a specified duration before being considered for unloading.
- **Manual Control**: Provides commands to manually trigger chunk clearing for immediate action.
- **Customizable Player Feedback**: Offers configurable messages for different events, enhancing the player experience with clear notifications.
- **World Exclusion**: Supports a blacklist to exclude specific worlds from the automatic chunk clearing process.
- **Stay Up-to-Date**: Features an auto-update notifier to inform you of the latest plugin releases.

# Default Config
``` yaml
# AutoClearChunk Configuration

# Enable Auto-Schedule
# Determines whether the plugin automatically schedules the task to clear unloaded chunks at the specified interval.
# Set to 'true' to enable auto-scheduling, 'false' to disable.
enable-auto-schedule: true

# Clear Interval Duration
# Defines the time interval at which unloaded chunks are automatically cleared.
# The value should be specified in a duration format.
# Examples: 1h (1 hour), 30m (30 minutes), 15s (15 seconds)
clear-interval-duration: 5m30s

# Chunk Unload Grace Period Duration
# Specifies how long a chunk without players should wait before being considered for unloading.
# Set to "0s" to disable the grace period and unload instantly (reverts to old behavior).
# Examples: 30s, 5m, 1h
chunk-unload-grace-period-duration: "30s"

# Clear Chunk Message
# Message displayed when chunks are cleared using the /clearchunk command.
# Use %d as a placeholder for the number of cleared chunks and %s for the world name.
# Color codes are supported.
# Default: "&aSuccessfully cleared %d chunks in world %s"
clearchunk-message: "&aSuccessfully cleared %d chunks in world %s"

# Clear Chunk Broadcast Message
# Message broadcast to all players when chunks are cleared using the /clearchunk command.
# Use %d as a placeholder for the number of cleared chunks and %s for the world name.
# Color codes are supported.
# Default: "&e%d chunks have been cleared in world %s"
clearchunk-broadcast-message: "&e%d chunks have been cleared in world %s"

# Clear All Chunk Message
# Message displayed when chunks are cleared using the /clearallchunk command.
# Use %d as a placeholder for the number of cleared chunks.
# Color codes are supported.
# Default: "&aSuccessfully cleared %d chunks in all worlds"
clearallchunk-message: "&aSuccessfully cleared %d chunks in all worlds"

# Clear All Chunk Broadcast Message
# Message broadcast to all players when chunks are cleared using the /clearallchunk command.
# Use %d as a placeholder for the number of cleared chunks.
# Color codes are supported.
# Default: "&e%d chunks have been cleared in all worlds"
clearallchunk-broadcast-message: "&e%d chunks have been cleared in all worlds"

# Enable Broadcast Messages
# Set to 'true' to enable broadcasting messages to all players when chunks are cleared, 'false' to disable.
broadcast-message: true

# Blacklisted Worlds
# List of world names that will be excluded from the chunk clearing process.
# Add the exact names of any worlds you want to exclude.
# Default: []
blacklisted-worlds:
  - your_world
  - another_world

```

# Configuration
The plugin configuration file (`config.yml`) allows you to customize various aspects of the AutoClearChunk plugin. Here are the configurable options:

- `enable-auto-schedule` (boolean): Set this option to `true` if you want to enable the automatic chunk clearing schedule. If set to `false`, chunks will only be cleared manually using commands.
- `clear-interval-duration` (string): Specify the duration interval at which chunks should be cleared automatically. The duration should be specified in the format of `1h30m` for 1 hour and 30 minutes.
- `chunk-unload-grace-period-duration` (string): Defines how long a chunk without players should wait before being considered for unloading. Set to "0s" to disable the grace period and unload instantly. Examples: `30s, 5m, 1h`.
- `clearchunk-message` (string): Customize the message sent to players when chunks are cleared using the `/clearchunk` command.
- `clearchunk-broadcast-message` (string): Customize the message broadcasted to all players when chunks are cleared using the `/clearchunk` command.
- `clearallchunk-message` (string): Customize the message sent to players when chunks are cleared using the `/clearallchunk` command.
- `clearallchunk-broadcast-message` (string): Customize the message broadcasted to all players when chunks are cleared using the `/clearallchunk` command.
- `broadcast-message` (boolean): Set this option to `true` if you want to enable the broadcasting message when Chunk Cleared
- `blacklisted-worlds` (array): Specify a list of worlds to exclude from chunk clearing. Add world names as individual array items.

You can edit the `config.yml` file using a text editor to adjust the plugin's behavior, customize messages, and define the worlds where chunk clearing should be applied.

# Commands
The AutoClearChunk plugin provides the following commands for chunk clearing:

- `/clearchunk`: Clears all chunks in the current world. Players receive a notification message.
  - Permission: `autoclearchunk.command.clearchunk`
- `/clearallchunk`: Clears all chunks in all configured worlds. Players receive a notification message.
  - Permission: `autoclearchunk.command.clearallchunk`

# Permissions
To control access to the commands provided by the AutoClearChunk plugin, the following permissions are available:

- `autoclearchunk.command.clearchunk`: Allows players to use the `/clearchunk` command.
- `autoclearchunk.command.clearallchunk`: Allows players to use the `/clearallchunk` command.

Grant these permissions to specific player groups or individuals using a permissions management plugin of your choice.

# Upcoming Features

- Currently none planned. You can contribute or suggest for new features.

# Additional Notes

- If you find bugs or want to give suggestions, please visit [here](https://github.com/HazardTeam/AutoClearChunk/issues).
- We accept all contributions! If you want to contribute, please make a pull request in [here](https://github.com/HazardTeam/AutoClearChunk/pulls).
- Icons made from [icons8.com](https://icons8.com)
