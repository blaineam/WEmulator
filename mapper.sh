#! /bin/bash
cd ./roms
tree . -J -f -I \*.jpg --noreport > map.json
cd ../