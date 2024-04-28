<p align="center">
  <img src="https://github.com/blaineam/WEmulator/blob/main/apple-touch-icon.png?raw=true" alt="WEmulator Icon of a Game Controller"/>
</p>

# WEmulator

WEmulator is a simple way to setup a static site that acts as a game library for [EmulatorJS](https://github.com/EmulatorJS/EmulatorJS). This has no requirement of a backend server language like PHP or NodeJS since it statically compiles the list of games available to it.

![Screenshot](https://github.com/blaineam/WEmulator/blob/main/screenshot.png?raw=true)

## Requirements

- An ability to run bash scripts on the machine with your rom files to build a map of what games you have.
- Games must be stored in a `roms` folder structure such as:
  - `nes/gameTitle.nes`
  - `nes/gameTitle.nes.jpg`
  - `snes/gameTitle.smc`
  - `snes/gameTitle.smc.jpg`
  - `snes/gameTitle.sfc`
  - `snes/gameTitle.sfc.jpg`
  - `n64/gameTitle.z64`
  - `n64/gameTitle.z64.jpg`
  - `nds/gameTitle.nds`
  - `nds/gameTitle.nds.jpg`
  - `gb/gameTitle.gb`
  - `gb/gameTitle.gb.jpg`
  - `gba/gameTitle.gba`
  - `gba/gameTitle.gba.jpg`
  - `gbc/gameTitle.gbc`
  - `gbc/gameTitle.gbc.jpg`
  - `psx/gameTitle.psx.zip`
  - `psx/gameTitle.psx.jpg`
  - `segaMD/gameTitle.md`
  - `segaMD/gameTitle.md.jpg`
- Each game needs a corresponding jpeg image file for the games cover art used in the arcade game list page.
- Consoles that need bios should have their bios zipped up and named by their console name and stored in a `bios` directory
- An understanding of how to restrict access to your self hosted site with your games as no auth will be provided. I recommend using a VPN to your webserver and block public access with a firewall.

## Usage Guide

1. Download or clone the repo
1. Use the `$ ./mapper.sh` file to build the required `map.json` file of your roms directory. Note: the `mapper.sh` file depends on tree being available from your shell, If you are using a mac use `$ brew install tree` to setup tree
1. start a web server or disable your web browsers local file restrictions.
1. Or use `$ docker compose up -d` and point your web browser to `http://localhost:8087`
1. Or use the Lamdi App by Blaine Miller on iOS to load the Wemulator folder from your iCloud Drive.
1. Search for a game and click it to launch the EmulatorJS Player

## Manually update [EmulatorJS](https://github.com/EmulatorJS/EmulatorJS)

1. goto the [EmulatorJS](https://github.com/EmulatorJS/EmulatorJS) Github Repo and download the latest release.
1. Delete all the files in the WEmulator `emu` folder
1. Copy the files from the [EmulatorJS](https://github.com/EmulatorJS/EmulatorJS) Release `data` folder into the WEmulator `emu` folder

## Future Improvements

- [ ] Build Electron App so bash and tree are not required to build map.json file
- [ ] Use SubModule for EmulatorJS instead of a static copy of those files.
- [ ] Add support for multiple cover art image formats
- [ ] Add remaining systems supported by EmulatorJS
- [ ] Make the System menu dynamically built based on the results of the map.json file and not a static list
- [ ] Make search extra fuzzy to make finding games easier
- [ ] Use EncRelay to support encrypting game library as a crude form of authentication
- [ ] Add webserver feature to Electron App
- [ ] Launch webpage where people can download the Electron App for their system to quickly start playing their games.

## Join the Effort

I will always welcome constructive PRs that help move this project towards my final goals for it being easier to setup and use.
I want this to be completely open source and I do not wish to make money on this project. I like classic games and so I hope to provide a means for preserving home access to games for future generations.

## F.A.Q.

- If you do not know what a ROM is this project is not for you.
- If you do not have your own game library this project will be of no use to you.
- I will not provide any instructions on where you can legally obtain your own ROMS or BIOS, that is for you to figure out ;)
- If you do not protect your installation of this, that is on yourself for breaking copyright laws and you are solely responsible for your own bad choices, I provide no warranty and by using this project you agree that you are liable for your own actions entirely.
