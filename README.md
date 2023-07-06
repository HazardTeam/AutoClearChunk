# AutoClearChunk

[![](https://poggit.pmmp.io/shield.state/AutoClearChunk)](https://poggit.pmmp.io/p/AutoClearChunk)
[![](https://poggit.pmmp.io/shield.dl.total/AutoClearChunk)](https://poggit.pmmp.io/p/AutoClearChunk)

A PocketMine-MP plugin that automatically clears chunks in specified worlds at regular intervals.

# Features
- Automatic clearing of chunks in configured worlds.
- Configurable clearing interval to suit your server's needs.
- Command-based chunk clearing options for manual control.
- Customizable messages for different events to enhance player experience.
- Blacklist support for excluding specific worlds from chunk clearing.
- Auto-update notifier to keep the plugin up to date with the latest releases.

# Default Config
``` yaml
# AutoClearChunk Configuration

# Enable Auto Schedule
# Determines whether the plugin automatically schedules the task to clear unloaded chunks at the specified interval.
# Set to 'true' to enable auto schedule, 'false' to disable.
enable-auto-schedule: true

# Clear Interval Duration
# Determines the time interval at which unloaded chunks are automatically cleared.
# The value should be specified as a duration format string.
# Examples: 1h (1 hour), 30m (30 minutes), 15s (15 seconds)
clear-interval-duration: 5m30s

# Message displayed when chunks are cleared using the ClearChunk command
# Use %d placeholder for the number of cleared chunks and %s placeholder for the world name.
# You can customize the message using color codes.
# Default: "Successfully cleared %d chunks in world %s"
clearchunk-message: "&aSuccessfully cleared %d chunks in world %s"

# Message broadcasted to all players when chunks are cleared using the ClearChunk command
# Use %d placeholder for the number of cleared chunks and %s placeholder for the world name.
# You can customize the message using color codes.
# Default: "&e%d chunks have been cleared in world %s"
clearchunk-broadcast-message: "&e%d chunks have been cleared in world %s"

# Message displayed when chunks are cleared using the ClearAllChunk command
# Use %d placeholder for the number of cleared chunks.
# You can customize the message using color codes.
# Default: "Successfully cleared %d chunks in all worlds"
clearallchunk-message: "&aSuccessfully cleared %d chunks in all worlds"

# Message broadcasted to all players when chunks are cleared using the ClearAllChunk command
# Use %d placeholder for the number of cleared chunks.
# You can customize the message using color codes.
# Default: "&e%d chunks have been cleared in all worlds"
clearallchunk-broadcast-message: "&e%d chunks have been cleared in all worlds"

# List of worlds that are blacklisted and won't be cleared
# Add the names of any worlds you want to exclude from the clearing process.
# Default: []
blacklisted-worlds:
  - your_world
  - another_world

```

# Configuration
The plugin configuration file (`config.yml`) allows you to customize various aspects of the AutoClearChunk plugin. Here are the configurable options:

- `enable-auto-schedule` (boolean): Set this option to `true` if you want to enable the automatic chunk clearing schedule. If set to `false`, chunks will only be cleared manually using commands.
- `clear-interval-duration` (string): Specify the duration interval at which chunks should be cleared automatically. The duration should be specified in the format of `1h30m` for 1 hour and 30 minutes.
- `clearchunk-message` (string): Customize the message sent to players when chunks are cleared using the `/clearchunk` command.
- `clearchunk-broadcast-message` (string): Customize the message broadcasted to all players when chunks are cleared using the `/clearchunk` command.
- `clearallchunk-message` (string): Customize the message sent to players when chunks are cleared using the `/clearallchunk` command.
- `clearallchunk-broadcast-message` (string): Customize the message broadcasted to all players when chunks are cleared using the `/clearallchunk` command.
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
