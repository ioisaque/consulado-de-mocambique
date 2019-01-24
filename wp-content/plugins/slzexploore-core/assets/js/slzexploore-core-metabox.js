jQuery(document).ready(function($) {
	"use strict";
	var slzAddSection = $(".slz-add-section"),
		slzAddSectionItem = $(".slz-add-section-item"),
		slzRemoveSectionRow = $(".slz-row-remove") ;
	
	slzAddSection.live("click", function(){
		var divCont, regEx, strSec, objClone, objName, item, objSecName, objItemName;
		divCont  = $(this).attr("data-container");
		objName  = $(this).attr("data-name");
		item     = $(this).attr("data-item");

		if( divCont == undefined) return;

		item = jQuery.fn.slzCom.cnvInt( item ) + 1;
		objSecName = objName + "[" + item + "][section]";
		objItemName = objName + "[" + item + "][item][]";
		// add section
		objClone = $(".slz-section-clone").html();
		regEx    = new RegExp("section_name","g");
		objClone = objClone.replace( regEx, objSecName );
		// replace data-name
		regEx    = new RegExp("section_item_name","g");
		objClone = objClone.replace( regEx, objItemName );
		
		$(divCont + " .content").append(objClone);
		$(this).attr("data-item", item);
	});
	slzAddSectionItem.live("click", function(){
		var divCont, objItem, objClone, regEx, objName;
		divCont = $(this).parent().parent();
		objName = $(this).attr("data-name");
		objItem = $(this).parent().find("select option:selected");

		if( divCont == undefined) return;
		if( objItem == undefined) return;
		if( objItem.val() == '' ) return;
		// add section item
		objClone = $(".slz-section-item-clone").html();
		regEx    = new RegExp("item_id","g");
		objClone = objClone.replace( regEx, objItem.val() );
		regEx    = new RegExp("item_val","g");
		objClone = objClone.replace( regEx, objItem.text() );
		regEx    = new RegExp("section_name","g");
		objClone = objClone.replace( regEx, objName );

		divCont.append(objClone);
		console.log(objClone);
	});
	slzRemoveSectionRow.live("click", function(){
		$(this).parent().remove();
	});
});