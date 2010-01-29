#!/bin/bash

set -e

if ! test -e ./fix_permissions.sh; then
	echo this script must be run with working directory being its svn checkout.
	exit 1
fi

# fix permissions of "what's hot" files
find ./ -name whats_hot.html -exec chown wwwrun.www {} \;
find ./ -name whats_hot.html -exec chmod +w {} \;

# fix permission of news file
chown wwwrun.www news.html
chmod u+w news.html

# fix permissions of screenshot cache file
chown wwwrun.www screenshot_gallery/cached_screenshots.json
chmod u+w screenshot_gallery/cached_screenshots.json

# fix permissions of README file
chown root.root README
chmod 400 README

# fix permissions of fix_permissions.sh
chown root.root fix_permissions.sh
chmod 700 fix_permissions.sh
