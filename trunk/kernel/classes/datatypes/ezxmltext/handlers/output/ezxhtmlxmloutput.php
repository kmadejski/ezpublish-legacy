<?php
//
// Definition of eZXHTMLXMLOutput class
//
// Created on: <28-Jan-2003 15:05:00 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

/*!
*/

include_once( 'kernel/classes/datatypes/ezxmltext/ezxmloutputhandler.php' );

class eZXHTMLXMLOutput extends eZXMLOutputHandler
{
    function eZXHTMLXMLOutput( &$xmlData, $aliasedType )
    {
        $this->eZXMLOutputHandler( $xmlData, $aliasedType );
    }

    /*!
     \reimp
    */
    function &outputText()
    {
        return $this->xhtml();
    }

    /*!
     \return the XHTML rendered value of the XML data
    */
    function &xhtml()
    {
        $tpl =& templateInit();
        $xml = new eZXML();
        $dom =& $xml->domTree( $this->XMLData );
        if ( $dom )
        {
            $node =& $dom->elementsByName( "section" );

            $sectionNode =& $node[0];
            $output = "";
            if ( get_class( $sectionNode ) == "ezdomnode" )
            {
                $output =& $this->renderXHTMLSection( $tpl, $sectionNode, 0 );
            }
        }
        return $output;
    }

    /*!
     \private
     \return the XHTML rendered version of the section
    */
    function &renderXHTMLSection( &$tpl, &$section, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = "";
        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', "level " . $section->toString( 0 ) );
        foreach ( $section->children() as $sectionNode )
        {
            if ( $tdSectionLevel == null )
            {
                $sectionLevel = $currentSectionLevel;
            }
            else
            {
                $sectionLevel = $tdSectionLevel;
                $currentSectionLevel = $currentSectionLevel;
            }
            $tagName = $sectionNode->name();
            switch ( $tagName )
            {
                // tags with parameters
                case 'header' :
                {
                   // Add the anchor tag before the header.
                   $name = $sectionNode->attributeValue( 'anchor_name' );

                   if ( $name )
                   {
                       $tpl->setVariable( 'name', $name, 'xmltagns' );

                       $uri = "design:content/datatype/view/ezxmltags/anchor.tpl";

                       eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                       $output .= implode( '', $textElements );
                   }

                   $level = $sectionLevel;
                   $tpl->setVariable( 'content', $sectionNode->textContent(), 'xmltagns' );
                   $tpl->setVariable( 'level', $level, 'xmltagns' );
                   $uri = "design:content/datatype/view/ezxmltags/header.tpl";
                   $textElements = array();
                   eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                   $output .= implode( '', $textElements );
                }break;

                case 'paragraph' :
                {
                    $output .= $this->renderXHTMLParagraph( $tpl, $sectionNode, $currentSectionLevel, $tdSectionLevel );
                }break;

                case 'section' :
                {
                    $sectionLevel += 1;
                    eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', "level ". $sectionLevel );
                    if ( $tdSectionLevel == null )
                        $output .= $this->renderXHTMLSection( $tpl, $sectionNode, $sectionLevel );
                    else
                        $output .= $this->renderXHTMLSection( $tpl, $sectionNode, $currentSectionLevel, $sectionLevel );
                }break;

                default :
                {
                    eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZXMLTextType::inputSectionXML()" );
                }break;
            }
        }
        return $output;
    }

    /*!
     \private
     \return XHTML rendered version of the paragrph
    */
    function &renderXHTMLParagraph( &$tpl, $paragraph, $currentSectionLevel, $tdSectionLevel = null )
    {
        $insideParagraph = true;
        $paragraphCount = 0;
        $paragraphContentArray = array();

        $sectionLevel = $currentSectionLevel;
        foreach ( $paragraph->children() as $paragraphNode )
        {
            $isBlockTag = false;
            $content =& $this->renderXHTMLTag( $tpl, $paragraphNode, $sectionLevel, $isBlockTag, $tdSectionLevel );
            if ( $isBlockTag === true )
            {
                $paragraphCount++;
            }

            if ( !isset( $paragraphContentArray[$paragraphCount]['Content'] ) )
                $paragraphContentArray[$paragraphCount] = array( "Content" => $content, "IsBlock" => $isBlockTag );
            else
                $paragraphContentArray[$paragraphCount] = array( "Content" => $paragraphContentArray[$paragraphCount]['Content'] . $content, "IsBlock" => $isBlockTag );
            if ( $isBlockTag === true )
            {
                $paragraphCount++;
            }
        }
        $output = "";
        foreach ( $paragraphContentArray as $paragraphContent )
        {
            if ( !$paragraphContent['IsBlock'] )
            {
                $tpl->setVariable( 'content', $paragraphContent['Content'], 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/paragraph.tpl";
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $output .= implode( '', $textElements );
            }
            else
            {
                $output .= $paragraphContent['Content'];
            }

        }
        if ( $paragraph->children() == null )
            $output = "<p></p>";
        return $output;
    }

    /*!
     \private
     Will render a tag and return the rendered text.
    */
    function &renderXHTMLTag( &$tpl, &$tag, $currentSectionLevel, &$isBlockTag, $tdSectionLevel = null )
    {
        // Set to true if tag breaks paragraph flow
        $isBlockTag = false;
        $tagText = "";
        $childTagText = "";
        $tagName = $tag->name();
        // render children tags
        $tagChildren = $tag->children();
        foreach ( $tagChildren as $childTag )
        {
            if ( $tag->name() == "literal" )
                $childTagText .= $childTag->content();
            else
                $childTagText .= $this->renderXHTMLTag( $tpl, $childTag, $currentSectionLevel, $isBlockTag, $tdSectionLevel );
        }


        switch ( $tagName )
        {
            case '#text' :
            {
                $tagText .= $tag->content();
                // Get rid of linebreak and spaces stored in xml file
                $tagText = preg_replace( "#[\n]+#", "", $tagText );
                $tagText = preg_replace( "#    #", "", $tagText );
            }break;

            case 'object' :
            {
                $isBlockTag = true;
                $objectID = $tag->attributeValue( 'id' );
                // fetch attributes
                $objectAttributes =& $tag->attributes();
                $object =& eZContentObject::fetch( $objectID );
                $view = $tag->attributeValue( 'view' );
                $alignment = $tag->attributeValue( 'align' );
                $size = $tag->attributeValue( 'size' );
                $src = "";
                $classID = $object->attribute( 'contentclass_id' );

                $hasLink = false;
                $linkID = $tag->attributeValueNS( 'ezurl_id', "http://ez.no/namespaces/ezpublish3/image/" );

                if ( $linkID != null )
                {
                    $href =& eZURL::url( $linkID );
                    $target = $tag->attributeValueNS( 'ezurl_target', "http://ez.no/namespaces/ezpublish3/image/" );
                    if ( $target == null )
                        $target = "_self";
                    $hasLink = true;
                }

                $objectParameters = array();
                foreach ( $objectAttributes as $attribute )
                {
                    if ( $attribute->name() == "ezurl_id" )
                        $objectParameters['href'] = $href;
                    else if ( $attribute->name() == "ezurl_target" )
                        $objectParameters['target'] = $target;
                    else
                        $objectParameters[$attribute->name()] = $attribute->content();
                }

                if ( strlen( $view ) == 0 )
                    $view = "embed";
                $tpl->setVariable( 'object', $object, 'xmltagns' );
                $tpl->setVariable( 'view', $view, 'xmltagns' );
                $tpl->setVariable( 'object_parameters', $objectParameters, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                $tagText = implode( '', $textElements );
            }break;

            case 'table' :
            {
                $tableRows = "";
                $border = $tag->attributeValue( 'border' );
                if ( $border === null )
                    $border = 1;

                $width = $tag->attributeValue( 'width' );
                if ( $width === null )
                    $width = "100%";

                $rowCount = 0;
                // find all table rows
                foreach ( $tag->children() as $tableRow )
                {
                    $tableData = "";
                    foreach ( $tableRow->children() as $tableCell )
                    {
                        $cellContent = "";
                        $tdSctionLevel = $currentSectionLevel;
                        $cellContent .= $this->renderXHTMLSection( $tpl, $tableCell, $currentSectionLevel, $tdSctionLevel );

                        $tpl->setVariable( 'content', $cellContent, 'xmltagns' );
                        $className = $tableCell->attributeValue( 'class' );
                        $width = $tableCell->attributeValueNS( 'width', "http://ez.no/namespaces/ezpublish3/xhtml/" );
                        $colspan = $tableCell->attributeValueNS( 'colspan', "http://ez.no/namespaces/ezpublish3/xhtml/" );
                        $rowspan = $tableCell->attributeValueNS( 'rowspan', "http://ez.no/namespaces/ezpublish3/xhtml/" );

                        if ( $tableCell->Name == "th" )
                            $uri = "design:content/datatype/view/ezxmltags/th.tpl";
                        else
                            $uri = "design:content/datatype/view/ezxmltags/td.tpl";
                        $textElements = array();
                        $tpl->setVariable( 'class', $className, 'xmltagns' );
                        $tpl->setVariable( 'colspan', $colspan, 'xmltagns' );
                        $tpl->setVariable( 'rowspan', $rowspan, 'xmltagns' );
                        $tpl->setVariable( 'width', $rowspan, 'xmltagns' );
                        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                        $tableData .= implode( '', $textElements );
                    }
                    $tpl->setVariable( 'content', $tableData, 'xmltagns' );
                    $tpl->setVariable( 'row_count', $rowCount, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/tr.tpl";
                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                    $tableRows .= implode( '', $textElements );
                    $rowCount++;
                }
                $tpl->setVariable( 'rows', $tableRows, 'xmltagns' );
                $tpl->setVariable( 'border', $border, 'xmltagns' );
                $tpl->setVariable( 'width', $width, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/table.tpl";
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                $tagText .= implode( '', $textElements );
                $isBlockTag = true;
            }break;

            case 'ul' :
            case 'ol' :
            {
                $isBlockTag = true;

                $listContent = "";
                // find all list elements
                foreach ( $tag->children() as $listItemNode )
                {
                    $listItemContent = "";
                    foreach ( $listItemNode->children() as $itemChildNode )
                    {
                        $listItemContent .= $this->renderXHTMLTag( $tpl, $itemChildNode, 0 );
                    }
                    $tpl->setVariable( 'content', $listItemContent, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/li.tpl";

                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $listContent .= implode( '', $textElements );
                }

                $className = $tag->attributeValue( 'class' );
                $tpl->setVariable( 'content', $listContent, 'xmltagns' );
                $tpl->setVariable( 'class', $className, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
            }break;

            // Literal text which allows xml specific caracters < >
            case 'literal' :
            {
                // Get class of literal tag.
                $className = $tag->attributeValue( 'class' );

                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );
                $tpl->setVariable( 'class', $className, 'xmltagns' );
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
            }break;

            // normal content tags
            case 'emphasize' :
            case 'strong' :
            case 'line' :
            {
                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
                $tagText = trim( $tagText );
            }break;

            // custom tags which could added for special custom needs.
            case 'custom' :
            {
                // Get the name of the custom tag.
                $name = $tag->attributeValue( 'name' );
                $isInline = false;
                include_once( "lib/ezutils/classes/ezini.php" );
                $ini =& eZINI::instance( 'content.ini' );

                $isInlineTagList =& $ini->variable( 'CustomTagSettings', 'IsInline' );
                foreach ( array_keys ( $isInlineTagList ) as $key )
                {
                    $isInlineTagValue =& $isInlineTagList[$key];
                    if ( $isInlineTagValue )
                    {
                        if ( $name == $key )
                            $isInline = true;
                    }
                }

                if ( $isInline )
                {
                    $childContent = $childTagText;
                }
                else
                {
                    $childContent = $this->renderXHTMLSection( $tpl, $tag, $currentSectionLevel, $tdSectionLevel );
                    $isBlockTag = true;
                }
                $tpl->setVariable( 'content',  $childContent, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$name.tpl";

                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
            }
            case 'link' :
            {
                include_once( 'lib/ezutils/classes/ezmail.php' );
                $linkID = $tag->attributeValue( 'id' );
                $target = $tag->attributeValue( 'target' );
                if ( $target == '_self' )
                    $target = false;
                if ( $linkID != null )
                    $href =& eZURL::url( $linkID, true );
                else
                    $href = $tag->attributeValue( 'href' );
                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );

                if ( eZMail::validate( $href ) )
                    $href = "mailto:" . $href;

                $tpl->setVariable( 'href', $href, 'xmltagns' );
                $tpl->setVariable( 'target', $target, 'xmltagns' );

                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
            }break;

            case 'anchor' :
            {
                $name = $tag->attributeValue( 'name' );

                $tpl->setVariable( 'name', $name, 'xmltagns' );

                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
            }break;

            default :
            {
                // unsupported tag
            }break;
        }
        return $tagText;
    }

}

?>
