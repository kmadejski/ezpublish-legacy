<h1>{"Group view"|i18n("design/standard/shop")}</h1>

<form action={concat($module.functions.discountgroupview.uri,"/",$discountgroup.id)|ezurl} method="post" name="DiscountGroupView">

<div class="objectheader">
    <h2>{"Group Name"|i18n("design/standard/shop")}</h2>
</div>
<div class="object">
    {$discountgroup.name} <a href={concat("/shop/discountgroupedit/",$discountgroup.id,"/")|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="{'Edit'|i18n('design/standard/shop')}" /></a><br/>
</div>

<h2>{"Defined rules"|i18n("design/standard/shop")}</h2> 

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>
        {"Name"|i18n("design/standard/shop")}
    </th>
    <th>
        {"Percent"|i18n("design/standard/shop")}
    </th>
    <th>
        {"Apply to"|i18n("design/standard/shop")}
    </th>
    <th>
        {"Edit"|i18n("design/standard/shop")}
    </th>
    <th>
    </th>
</tr>
{section name=Rule loop=$rule_list sequence=array(bglight,bgdark)}
<tr class="{$:sequence}">
    <td>
        {$:item.name|wash}
    </td>
    <td>
        {$:item.discount_percent}%
    </td>
    <td>
        {$:item.limitation}
    </td>
    <td width="1">
        <div class="listbutton">
            <a href={concat($module.functions.discountruleedit.uri,"/",$discountgroup.id,"/",$Rule:item.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="Edit" /></a>
        </div>
    </td>
    <td width="1">
        <input type="checkbox" name="removeRuleList[]" value="{$Rule:item.id}" />
    </td>
</tr>
{/section}
</table>

<div class="buttonblock">
    <input class="button" type="submit" name="AddRuleButton" value="{'Add Rule'|i18n('design/standard/shop')}" /> &nbsp;
    <input class="button" type="submit" name="RemoveRuleButton" value="{'Remove Rule'|i18n('design/standard/shop')}" />
</div>

<h2>{"Customers"|i18n("design/standard/shop")}</h2>

<table class="list" width="100%" cellspacing="0">
<tr>
    <th>
        {"Name"|i18n("design/standard/shop","Customer name")}
    </th>
    <th>
    </th>
</tr>
{section name=Customer loop=$customers sequence=array(bglight,bgdark)}
<tr class="{$:sequence}">
    <td>
        {content_view_gui view=text_linked content_object=$:item}
    </td>
    <td width="1">
        <input type="checkbox" value="{$Customer:item.id}" name="CustomerIDArray[]" />
    </td>
</tr>
{/section}
</table>

<div class="buttonblock">
    <input class="button" type="submit" name="AddCustomerButton" value="{'Add customer'|i18n('design/standard/shop')}" /> &nbsp;
    <input class="button" type="submit" name="RemoveCustomerButton" value="{'Remove customer'|i18n('design/standard/shop')}" />
</div>
</form>
