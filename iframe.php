<?php
/* Copyright (C) 2001-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2015      Jean-François Ferry	<jfefe@aternatik.fr>
 * Copyright (C) 2020      Ksar                 <ksar.ksar@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *	\file       dbadmin/dbadminindex.php
 *	\ingroup    dbadmin
 *	\brief      Iframe include of Adminer 
 */
 
ob_start ();

// Load Dolibarr environment
$res = 0;

//Desactivate as much as possible dolibarr objects loading
define ('NOCSRFCHECK', 1);
define ('NOREQUIRETRAN', 1);
define ('NOREQUIREMENU',1);
define ('NOSCANGETFORINJECTION',1);
define ('NOSCANPOSTFORINJECTION',1);
define ('NOREQUIRESOC',1);
define ('NOREQUIREHTML',1);
define ('NOREQUIREAJAX',1);
define ('NOTOKENRENEWAL',1);

// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) { $i--; $j--; }
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) $res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) $res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
// Try main.inc.php using relative path
if (!$res && file_exists("../main.inc.php")) $res = @include "../main.inc.php";
if (!$res && file_exists("../../main.inc.php")) $res = @include "../../main.inc.php";
if (!$res && file_exists("../../../main.inc.php")) $res = @include "../../../main.inc.php";
if (!$res) die("Include of main fails");

// Security check
if (! $user->rights->dbadmin->access) accessforbidden();

//Create the Adminer session token in order to avoid php waring
//TO DO : Understand why it is necessary
$zd=$_SESSION["token"];
if(!is_numeric($zd)) {
	$_SESSION["token"]=rand(1,1e6);
}
$_GET["username"] = "";
if ($_SESSION["db"]["server"][""][""][""] != true){
	$_POST["auth"] = array ("driver"=>"server","server"=>"","username"=>"","password"=>"","db"=>"");
}

function adminer_object()
{
		
    // Required to run any plugin.
    include_once "./adminer/plugins/plugin.php";

    // Plugins auto-loader.
    foreach (glob("adminer/plugins/*.php") as $filename) {
        include_once "./$filename";
    }

    // Specify enabled plugins here.
    $plugins = [
		new AdminerDoliLogin(),
        new AdminerTablesFilter(),
        new AdminerSimpleMenu(),
        new AdminerCollations(),
        new AdminerDumpDate(),
		new AdminerDumpZip(),
		new AdminerFrames(true),

        // AdminerTheme has to be the last one.
        new AdminerTheme('default-blue'),
    ];
	
    return new AdminerPlugin($plugins);
}

// Include original Adminer.
include "./adminer/adminer.php";
ob_end_flush();
$db->close();
