#!/bin/bash
#
# Author(s): Guillaume GARDET <guillaume.gardet@opensuse.org>
#
# History:
##	- 2012-06-07:	Fix package name (po4a) deps
##	- 2012-06-06:	Initial release
##
##
# Deps:	- po4a-translate (from po4a)
# 	- svn (from subversion) to get the PO files from i18n opensuse svn server

# Some vars
HTML_files_folder="./en"
POT_files_folder="./50-pot/"
langs="cs de el es fi fr hu it jp lt nl pl pt-br ru sk th zh-cn zh-tw" # without en, of course
PO_filename="opensuse_org.po"
translation_limit="50"	# Minimal translation percentage. Under this limit, no HTML file is output.

for lang in $langs; do
	output_folder=$lang
	PO_folder="$lang/po"
	
	case $lang in
	pt-br )
		svn_server_lang_code=pt_BR
		;;
	zh-cn )
		svn_server_lang_code=zh_CN
		;;
	zh-tw )
		svn_server_lang_code=zh_TW
		;;
	* )
		svn_server_lang_code=$lang
		;;
	esac

	
# Sync PO files from opensuse-i18n SVN server
	echo "* Syncing PO file for $lang ($svn_server_lang_code)"
	
	for file in $(ls $POT_files_folder/*.pot); do
		filename=$(basename ${file%.pot}).$svn_server_lang_code.po
		mkdir -p $PO_folder
		cd $PO_folder
		svn export https://svn.opensuse.org/svn/opensuse-i18n/trunk/lcn/$svn_server_lang_code/po/$filename
		cd -
	done
	
	
# Generate translated HTML files
	echo "* Generating HTML files for $lang ($svn_server_lang_code)"
	
	for file in $(ls $HTML_files_folder/*html); do
		HTML_file=$(basename $file)
		cmd="po4a-translate --keep $translation_limit -f xhtml -m $HTML_files_folder/$HTML_file -p $PO_folder/$PO_filename -l $output_folder/$HTML_file"
		echo $cmd
		$cmd
	done
	
	
# Some hacks to fix some things not translatable (not in PO files)
# Fixes for ./$lang/index.shtml
	echo "* Some hacks on HTML files for $lang ($svn_server_lang_code)"
	
	file="$lang/index.shtml"
	
	# Replace <meta name="language" content="en" /> with the right lang code
	sed -i -e "s/<meta name=\"language\" content=\"en\" \/>/<meta name=\"language\" content=\"$lang\" \/>/g" "$file"
	

	# Replace <img src="http://static.opensuse.org/hosts/www.o.o/images/flags/gb.png" * with the right flag code
	# Compute the right flag code
	case $lang in
	pt-br )
		flag=br
		;;
        cs )
		flag=cz
		;;
	el )
		flag=gr
		;;
	en )
		flag=gb
		;;
	zh-cn )
		flag=cn
		;;
	zh-tw )
		flag=tw
		;;
        * )
		# Most use the same code for lang and flag
	 	flag=$lang
	 	;;
	esac
	sed -i -e "s/<img src=\"http:\/\/static.opensuse.org\/hosts\/www.o.o\/images\/flags\/gb.png\"/<img src=\"http:\/\/static.opensuse.org\/hosts\/www.o.o\/images\/flags\/$flag.png\"/g" "$file"
	

	# Replace <option value="en" selected="selected"> with the right lang code
	sed -i -e "s/<option value=\"en\" selected=\"selected\">/<option value=\"$lang\" selected=\"selected\">/g" "$file"


	
done
