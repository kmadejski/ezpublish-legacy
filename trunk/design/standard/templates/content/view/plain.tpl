{$object.name|wash}

{"Current version"|i18n("design/standard/content/view")}-{$object.current_version}
{let version_attributes=$object.contentobject_attributes}
{section name=ContentObjectAttribute loop=$version_attributes}

{$ContentObjectAttribute:item.contentclass_attribute.name|wash}:

{attribute_view_gui view='plain' attribute=$ContentObjectAttribute:item}

{/section}
{/let}