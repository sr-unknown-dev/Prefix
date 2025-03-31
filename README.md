# Prefix Plugin

> [!NOTE]
> Para modificar los prefix sigue esta ruta "src\unknown\prefix\manager\PrefixManager"
> Aqui podras editar los prefix que hay, tambien si lo gustas podras agregar mas
>
> Se utilizó Copilot AI para este proyecto, el código es 15% Copilot AI ya que se utilizo para el menú y optimización, y 85% Creado por mi, osea comandos, manager y clases echas por mi

> [!IMPORTANT]
> Necesitas tener la core hcf puesta porque sino no se veran los prefix en el chat

## Description
The Prefix Plugin allows players to set custom prefixes in the game. Players can choose from a predefined list of tags representing countries, PvP ranks, player states, and funny memes.

## VIDEO
![Prefix](https://img.youtube.com/vi/iWlKI3OnBEk/maxresdefault.jpg)
https://www.youtube.com/watch?v=iWlKI3OnBEk

## Features
- Set custom prefixes for players.
- Predefined tags for countries, PvP ranks, player states, and memes.
- Commands to set and manage prefixes.
- Event listeners to update player display names and chat messages with prefixes.

## Commands Admin
- `/prefix setprefix <prefix> <player>`: Set your prefix or another player's prefix. Requires `prefix.admin` permission.
- `/prefix removeprefix <prefix>`: Remove your prefix or another player's prefix. Requires `prefix.admin` permission.
- `/prefix list`: See list of prefixes. Requires `prefix.admin` permission.
- /prefix setnpc`: Add npc from the prefix. Requires `prefix.admin` permission.
- /prefix removenpc`: Remove npc from the prefix. Requires `prefix.admin` permission.

## Permissions
- `prefix.admin`: Allows the player to use the `/setprefix` command.
- `prefix.user`: Allows the player to use the prefix.

## Installation
1. Download the plugin.
2. Place the plugin file in the `plugins` folder of your server.
3. Restart the server.

## Usage
1. Use the `/setprefix <prefix>` command to set your prefix.
2. Use the `/prefix` command to open menu prefixs
   The prefix will be displayed in your name and chat messages.

## Configuration
The predefined tags can be found and modified in the `PrefixManager` class. The tags are categorized into countries, PvP ranks, player states, and memes.

## Example Tags
- Countries: `MEX`, `USA`, `BRA`, `COL`, `ARG`, `PER`, `ESP`
-
- PvP Ranks: `CHEATER`, `MONEY`, `KILLER`, `GOD`, `HUNTER`, `NOOB`, `TRYHARD`, `DESTROYER`, `SNIPER`, `BLOOD`, `COMBO`, `ASSASSIN`, `GHOST`
-
- Player States: `RICH`, `POOR`, `WARRIOR`, `KING`, `QUEEN`, `RANDOM`
-
- Memes: `LAGGER`, `BOT`, `EZ`, `SIMP`

## License
This project is licensed under the MIT License.
