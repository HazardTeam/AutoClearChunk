# AutoClearChunk

A PocketMine-MP plugin that can reduce or clean up the chunks in your world

# Features
- Custom Clear Message
- Custom Clear Interval Time
- Per world clear chunk

# Config

``` YAML
---
# The time used to run the clear chunk task.
# Default: 600 seconds.
clear-interval: 600

# The message used when successfully clearing the chunk.
# Use {COUNT} to get chunk count cleared.
message: "&aSuccessfully cleared &b{COUNT} chunks"

# The name of the world folder that you don't want to clear the chunk.
blacklisted-worlds:
  - "Lobby"
...
```

# Additional Notes

- If you find bugs or want to give suggestions, please visit [here](https://github.com/HazardTeam/AutoClearChunk/issues)
- Icons By [icons8.com](https://icons8.com)
