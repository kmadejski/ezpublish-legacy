{let  handler_items=fetch(notification,digest_items,hash(date,$date.timestamp,address,$address,handler,$handler.id_string))}
  {section name=Items loop=$handler_items}
    {append-block scope=global variable=collection_item_id_list}{$Items:item.id}{/append-block}
    {include handler=$handler collection_item=$Items:item uri=concat( "design:notification/handler/",$handler.id_string,"/view/digest_element_plain.tpl")}
  {/section}
{let}
