{* DO NOT EDIT THIS FILE! Use an override template instead. *}
<form method="post" action={"settings/view"|ezurl}>

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'View settings'|i18n('design/admin/settings')}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-attributes">

<div class="object">
{section show=$settings}
<p>{'Using siteaccess'|i18n('design/admin/settings')}: <strong>{$current_siteaccess}</strong></p>
<p>{'%ini_file consist of %blocks section(s) and %setting_count different setting(s)'|i18n( 'design/admin/settings','', hash( '%ini_file', $ini_file, '%blocks', $block_count, '%setting_count', $setting_count) )}</p>
{section-else}
<p>{'Please select an ini file from the dropdown below'|i18n('design/admin/settings')}</p>
{/section}
<br />
    <div class="element">
        <label>{'Select ini file to view'|i18n('design/admin/settings')}</label><div class="labelbreak"></div>
        <select name="selectedINIFile">
            {section var=Files loop=$ini_files}
                {section show=eq( $Files.item, $ini_file )}
                    <option value="{$Files.item}" selected="selected">{$Files.item}</option>
                {section-else}
                    <option value="{$Files.item}">{$Files.item}</option>
                {/section}
            {/section}
        </select>
    </div>

    <div class="element">
        <label>{'Select siteaccess'|i18n('design/admin/settings')}</label><div class="labelbreak"></div>
        <select name="CurrentSiteAccess">
        {section name=SiteAccess loop=$siteaccess_list}
            {section show=eq( $current_siteaccess, $:item )}
                <option value="{$:item}" selected="selected">{$:item}</option>
            {section-else}
                <option value="{$:item}">{$:item}</option>
            {/section}
        {/section}
        </select>
    </div>
</div>
</div>
{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="ChangeINIFile" value="{'Select'|i18n('design/admin/settings')}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</form>


<br />

{section show=$settings}

<form method="post" action={concat("settings/view/",$current_siteaccess,"/",$ini_file)|ezurl}>



{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Settings for %inifile in siteaccess %siteaccess'|i18n('design/admin/settings',, hash( '%inifile', $ini_file, '%siteaccess', $current_siteaccess))}</h1>

{* DESIGN: subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-attributes">


    <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
{section var=Blocks loop=$settings}
        <tr>
	    <th class="tight">&nbsp;</th>
            <th width="50%">
	        {$Blocks.key} ({$Blocks.item.count})&nbsp;&nbsp;&nbsp;<a href={concat( '/settings/edit/', $current_siteaccess, '/', $ini_file, '/', $Blocks.key)|ezurl}>{'[add setting]'|i18n('design/admin/settings')}</a>
            </th>
	    <th class="tight">
	        {'Placement'|i18n( 'design/admin/settings' )}
	    </th>
            <th width="50%">
                {'Value'|i18n( 'design/admin/settings' )}
            </th>
            <th class="tight">&nbsp;</th>
    </tr>
    {section var=Settings loop=$Blocks.item.content sequence=array( 'bgdark', 'bglight' )}
        <tr valign="top" class="{$Settings.sequence}">
            <td width="1">
            {section show=$Settings.item.removeable}
                <input type="checkbox" name="RemoveSettingsArray[]" value="{$Blocks.key}:{$Settings.key|wash}"/>
            {section-else}
                <input type="checkbox" disabled="disabled" />
            {/section}
            </td>
            <td width="50%">
                {$Settings.key|wash}
            </td>
            <td width="1">
            {section show=eq( $Settings.item.placement, '' )}
                {section var=Placements loop=$Settings.item.content}
                    {$Placements.item.placement}
                {/section}
            {section-else}
                {$Settings.item.placement}
            {/section}
            </td>
            <td width="50%">
                {switch match=$Settings.item.type}
                {case match='array'}
                    {section show=ne($Settings.item.placement,'undefined')}
	                {section var=Placements loop=$Settings.item.content}
                        <div class="array">[{$Placements.key}] {$Placements.item.content|wash}</div>
                    {/section}
                    {/section}
                {/case}

                {case in=array( 'enable/disable', 'true/false' )}
                    {section show=or( eq( $Settings.item.content, 'true' ), eq( $Settings.item.content, 'enabled' ) )}
                        <div class="enabled">{$Settings.item.content}</div>
                    {section-else}
                        <div class="disabled">{$Settings.item.content}</div>
                    {/section}
                {/case}
                {case match='string'}
                    <div class="string">"{$Settings.item.content|wash}"</div>
                {/case}
                {case match='numeric'}
                    <div class="numeric">{$Settings.item.content|wash}</div>
                {/case}
                {case}
                    <div class="{$Settings.item.type}">{$Settings.item.content|wash}</div>
                {/case}
                {/switch}
            </td>
            <td align="right" width="1">
                {switch match=$Settings.item.type}
                    {case match='array'}
	                    <a href={concat('settings/edit/', $current_siteaccess, '/', $ini_file, '/', $Blocks.key, '/', $Settings.key, '/', 'siteaccess')|ezurl}>
                        <img src={"edit.png"|ezimage} alt="{'Edit'|i18n('design/admin/settings')}" /></a>
                    {/case}
                    {case}
                        <a href={concat('settings/edit/', $current_siteaccess, '/', $ini_file, '/', $Blocks.key, '/', $Settings.key, '/', $Settings.item.placement)|ezurl}>
                        <img src={"edit.png"|ezimage} alt="{'Edit'|i18n('design/admin/settings')}" /></a>
                    {/case}
                {/switch}
            </td>
        </tr>
    {/section}
{/section}
</table>

</div>
{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n('design/admin/settings')}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</form>
{/section}