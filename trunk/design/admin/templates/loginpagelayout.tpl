{*?template charset=latin1?*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />

{include uri="design:page_head.tpl"}

</head>

<body>

<table class="layout" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="headlogo" colspan="2">

    <img src={"logo.gif"|ezimage} alt="" />&nbsp;&nbsp;<img src={"admin.gif"|ezimage} alt="" />

   </td>
</tr>
<tr>
    <td colspan="2">

<table width="100%" border="0" cellspacing="0" cellpadding="0">

{* Header *}
<tr>
	<td>

<table width="750"  cellspacing="0" cellpadding="4">
{section show=$warning_list}
<tr>
  <td colspan="3">
   {include uri="design:page_warning.tpl"}
  </td>
</tr>
{/section}

<tr>
    {* This is the main content *}
    <td width="20%" bgcolor="#ffffff" valign="top">
    </td>
    <td width="30%" bgcolor="#ffffff">
    {$module_result.content}
    </td>
    <td width="50%" bgcolor="#ffffff" valign="top">
    <h2>{"Welcome to eZ publish administration"|i18n("design/standard/layout")}</h2>
    <p>{"To log in enter a valid login and password."|i18n("design/standard/layout")}</p>
    </td>
</tr>
</table>

    </td>
</tr>
</table>

{include uri="design:page_copyright.tpl"}

<!--DEBUG_REPORT-->

</body>
</html>