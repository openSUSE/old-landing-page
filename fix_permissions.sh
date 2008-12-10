#!/bin/bash

set -e

if ! test -e ./fix_permissions.sh; then
	echo this script must be run with working directory being its svn checkout.
	exit 1
fi

# fix permissions of "what's hot" files
find ./ -name whats_hot.html -exec chown wwwrun {} \;
find ./ -name whats_hot.html -exec chmod +w {} \;

# fix permission of news file
chown wwwrun news.html
chmod u+w news.html

# fix permissions of screenshot cache file
chown wwwrun screenshot_gallery/cached_screenshots.json
chmod u+w screenshot_gallery/cached_screenshots.json
