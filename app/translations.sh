#!/bin/sh
BASEDIR="/var/www/kloder"
COREDIR="$BASEDIR/lib/cake"
PLUGINSDIR="$BASEDIR/app/Plugin"
CONSOLE="$BASEDIR/app/Console/cake"

PARAMS="--validation-domain validation --extract-core no --merge no"

rm $PLUGINSDIR/Bank/Locale/*
$CONSOLE i18n extract --plugin Bank $PARAMS

rm $PLUGINSDIR/Comments/Locale/*
$CONSOLE i18n extract --plugin Comments $PARAMS

rm $PLUGINSDIR/Contacts/Locale/*
$CONSOLE i18n extract --plugin Contacts $PARAMS

# Ignore Debugkit

rm $PLUGINSDIR/Domains/Locale/*
$CONSOLE i18n extract --plugin Domains $PARAMS

rm $PLUGINSDIR/Finances/Locale/*
$CONSOLE i18n extract --plugin Finances $PARAMS --exclude Vendor

rm $PLUGINSDIR/Fliwik/Locale/*
$CONSOLE i18n extract --plugin Fliwik $PARAMS

rm $PLUGINSDIR/Games/Locale/*
$CONSOLE i18n extract --plugin Games $PARAMS

rm $PLUGINSDIR/Kloder/Locale/*
$CONSOLE i18n extract --plugin Kloder $PARAMS

rm $PLUGINSDIR/Languages/Locale/*
$CONSOLE i18n extract --plugin Languages $PARAMS

rm $PLUGINSDIR/Mails/Locale/*
$CONSOLE i18n extract --plugin Mails $PARAMS

rm $PLUGINSDIR/Meetings/Locale/*
$CONSOLE i18n extract --plugin Meetings $PARAMS

rm $PLUGINSDIR/Menus/Locale/*
$CONSOLE i18n extract --plugin Menus $PARAMS

rm $PLUGINSDIR/Network/Locale/*
$CONSOLE i18n extract --plugin Network $PARAMS

rm $PLUGINSDIR/News/Locale/*
$CONSOLE i18n extract --plugin News $PARAMS

rm $PLUGINSDIR/Plugins/Locale/*
$CONSOLE i18n extract --plugin Plugins $PARAMS

rm $PLUGINSDIR/Products/Locale/*
$CONSOLE i18n extract --plugin Products $PARAMS

rm $PLUGINSDIR/Projects/Locale/*
$CONSOLE i18n extract --plugin Projects $PARAMS

rm $PLUGINSDIR/Ratings/Locale/*
$CONSOLE i18n extract --plugin Ratings $PARAMS

rm $PLUGINSDIR/Resources/Locale/*
$CONSOLE i18n extract --plugin Resources $PARAMS

rm $PLUGINSDIR/ResourcesCodes/Locale/*
$CONSOLE i18n extract --plugin ResourcesCodes $PARAMS

rm $PLUGINSDIR/ResourcesDiagrams/Locale/*
$CONSOLE i18n extract --plugin ResourcesDiagrams $PARAMS

rm $PLUGINSDIR/ResourcesElearnings/Locale/*
$CONSOLE i18n extract --plugin ResourcesElearnings $PARAMS

rm $PLUGINSDIR/ResourcesEpubs/Locale/*
$CONSOLE i18n extract --plugin ResourcesEpubs $PARAMS

rm $PLUGINSDIR/ResourcesMedia/Locale/*
$CONSOLE i18n extract --plugin ResourcesMedia $PARAMS

rm $PLUGINSDIR/ResourcesOffice/Locale/*
$CONSOLE i18n extract --plugin ResourcesOffice $PARAMS

rm $PLUGINSDIR/Rowaccess/Locale/*
$CONSOLE i18n extract --plugin Rowaccess $PARAMS

rm $PLUGINSDIR/Schedule/Locale/*
$CONSOLE i18n extract --plugin Schedule $PARAMS

rm $PLUGINSDIR/Search/Locale/*
$CONSOLE i18n extract --plugin Search $PARAMS

rm $PLUGINSDIR/Team/Locale/*
$CONSOLE i18n extract --plugin Team $PARAMS

rm $PLUGINSDIR/Trading/Locale/*
$CONSOLE i18n extract --plugin Trading $PARAMS

rm $PLUGINSDIR/Users/Locale/*
$CONSOLE i18n extract --plugin Users $PARAMS

# Root

rm $BASEDIR/app/Locale/*
$CONSOLE i18n extract --exclude Plugin --paths $BASEDIR/app/ --output $BASEDIR/app/Locale $PARAMS

