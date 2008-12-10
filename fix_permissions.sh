#!/bin/bash

# fix permissions of "what's hot" files
find ./ -name whats_hot.html -exec chown wwwrun {} \;
find ./ -name whats_hot.html -exec chmod +w {} \;

# fix permission of news file
chown wwwrun news.html
chmod u+w news.html

# fix permissions of screenshot cache file
chown wwwrun screenshot_gallery/cached_screenshots.json
chmod u+w screenshot_gallery/cached_screenshots.json
