<form action={concat($module.functions.workflowlist.uri,"/",$group_id)|ezurl} method="post" name="WorkflowList">

<div class="maincontentheader">
<h1>{"Workflows in %1"|i18n("design/standard/workflow","%1 is workflow group",array($group_name))}</h1>
</div>

{section show=$workflow_list}
   <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <th>{"Name"|i18n("design/standard/workflow")}</th>
    <th>{"Modifier"|i18n("design/standard/workflow")}</th>
    <th>{"Modified"|i18n("design/standard/workflow")}</th>
    <th width="1%">{"Edit"|i18n("design/standard/workflow")}</th>
    <th width="1%">{"Remove"|i18n("design/standard/workflow")}</th>
</tr>

    {sequence name=WorkflowSequence loop=array(bglight,bgdark)}

   {section name=Workflow loop=$workflow_list}
    <tr>
    <td class="{$WorkflowSequence:item}">{$Workflow:item.name}</td>
    <td class="{$WorkflowSequence:item}">{$Workflow:item.modifier_id}</td>
    <td class="{$WorkflowSequence:item}"><span class="small">{$Workflow:item.modified|l10n(shortdatetime)}</span></td>
    <td class="{$WorkflowSequence:item}"><div class="listbutton"><a href={concat($module.functions.edit.uri,"/",$Workflow:item.id)|ezurl}><img name="edit"  src={"edit.png"|ezimage} width="16" height="16" align="{'Edit'|i18n('design/standard/workflow')}" /></a></div></td>
    <td class="{$WorkflowSequence:item}" align="right"><input type="checkbox" name="Workflow_id_checked[]" value="{$Workflow:item.id}"></td>
</tr>
    {sequence name=WorkflowSequence}
   {/section}


<tr>
  <td colspan="4">

    {include uri="design:gui/button.tpl" name=new id_name=NewWorkflowButton value="New workflow"|i18n("design/standard/workflow")}
  </td>
  <td align="right">
    <input type="image" name="DeleteButton" value="{'Remove'|i18n('design/standard/workflow')}" src={"trash.png"|ezimage} />
  </td>
</tr>
</table>
{section-else}
<div class="buttonblock">
  {include uri="design:gui/button.tpl" name=new id_name=NewWorkflowButton value="New workflow"|i18n("design/standard/workflow")}
</div>
{/section}
<input type="hidden" name="CurrentGroupID" value="{$group_id}" />
<input type="hidden" name="CurrentGroupName" value="{$group_name}" />
</form>
