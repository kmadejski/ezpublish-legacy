{let settings=$handler.settings}


{'Do you want to receive messages combined in digest'|i18n('design/standard/notification')}
  <input type="checkbox" name="ReceiveDigest_{$handler.id_string}" value="{$settings.receive_digest}" /><br/>

{'Digest settings'|i18n('design/standard/notification')}
<br/>
{'Day the week'|i18n('design/standard/notification')}

<select name="Weekday_{$handler.id_string}">
{section name=WeekDays loop=$handler.all_week_days}
<option value="{$WeekDays:item}" {section show=eq($WeekDays:item,$settings.day)}selected="selected"{/section}>{$WeekDays:item}</option>
{/section}
</select>

<select name="Time_{$handler.id_string}">
{section name=Time loop=$handler.available_hours}
<option value="{$Time:item}" {section show=eq($Time:item,$settings.time)}selected="selected"{/section}>{$Time:item}</option>
{/section}
</select>


{/let}