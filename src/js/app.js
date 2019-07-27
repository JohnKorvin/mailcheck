
jQuery(document).ready(function($){
	"use strict";
	
	// хотя я бы лучше использовал библиотеку select2, как я использовал тут https://kanzoboz.ru/edit.php?new=1 в поиске города
	$(".check-list .search").keyup(function(){
		var val = $(this).val(),
			select = $(this).next("select");
		
		if( !select.is("select") ) {
			select = $("<select size=10 />");
			$(this).parent().append(select);
		}
		
		select.html("").hide();
		
		if( val ) {
			$.get("/",{type:$(this).attr("name"),val:val},function(resp){
				if( resp && resp.size > 0 ) {
					$.each(resp.items,function(k, v) {
						select.append( "<option value='" +k+ "'>" +v+ "</option>" );
					});
					select.show();
				}
			});
		}
	});
	
	$(document).on("dblclick",".check-list select > option",function(){
		var select = $(this).closest("select"),
			input = select.prev();
		input.val($(this).text()).data("id",$(this).val());
		select.hide();
		return false;
	});
	
	$(".check-list :submit").click(function(){
		$(this).parent().find('.search').each(function(){
			if( !$(this).data('id') ) $(this).val( '' );
		});
		return true;
	});
	
	$(".check-list form").submit(function(){
		var block = $(this).parent();
		$(this).hide();
		$(this).find('.search').each(function(){
			if( $(this).data('id') ) $(this).val( $(this).data('id') );
		});
		
		$.get('/?add-check=1&' + $(this).serialize(),function(ret){
			console.log(ret);
			block.addClass('added');
			block.fadeOut(3000);
		});
		
		return false;
	});
});