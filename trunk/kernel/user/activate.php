1<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/datatypes/ezuser/ezusersetting.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuseraccountkey.php" );

$Module =& $Params['Module'];
$http =& eZHTTPTool::instance();
$hash =& $Params['Hash'];

// Check if key exists
$accountActivated = false;
$accountKey = eZUserAccountKey::fetchByKey( $hash );
if ( $accountKey )
{
    $accountActivated = true;
    $userID = $accountKey->attribute( 'user_id' );

    // Enable user account
    $userSetting = eZUserSetting::fetch( $userID );
    $userSetting->setAttribute( 'is_enabled', 1 );
    $userSetting->store();

    // Log in user
    $user =& eZUser::fetch( $userID );
    $user->loginCurrent();

    // Remove key
    $accountKey->remove();
}


// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

$tpl->setVariable( "module", $Module );
$tpl->setVariable( "account_avtivated", $accountActivated );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:user/activate.tpl" );

?>
