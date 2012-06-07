#!/bin/bash
#
# Author(s): Guillaume GARDET <guillaume.gardet@opensuse.org>
#
# History:
##	- 2012-06-07:	Fix package name (po4a) deps
##	- 2012-06-06:	Initial release
##
##
# Deps:	- po4a-gettextize (from po4a)

# Some vars
HTML_file0="./en/index.shtml"
HTML_file1="./en/whats_hot.html"
POT_files_folder="./50-pot"
POT_name="opensuse_org.pot"


# Create POT folder if does not exist
mkdir -p $POT_files_folder

#Generate a POT file from all *html files in ./en folder
cmd="po4a-gettextize -f xhtml -m $HTML_file0 -m $HTML_file1 -p $POT_files_folder/$POT_name"
echo "Generating POT file:"
echo $cmd
$cmd
