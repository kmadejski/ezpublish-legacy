<select name="{$id_name}">
{section name=WorkflowType loop=$workflowtypes}
<option value="{$WorkflowType:item.type_string}" {switch name=sw match=$WorkflowType:item.type_string}{case match=$current}selected{/case}{case/}{/switch}>{$WorkflowType:item.group_name}/{$WorkflowType:item.name}</option>
{/section}
</select>
