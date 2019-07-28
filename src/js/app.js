
jQuery(document).ready(function($){
	"use strict";
	
	$(".check-list .search").each(function(){ $(this).val(''); });
	
	// хотя я бы лучше использовал библиотеку select2, как я использовал тут https://kanzoboz.ru/edit.php?new=1 в поиске города
	$(".check-list .search").keyup(function(event){
		var val = $(this).val() || '',
			select = $(this).next("select"),
			dataval = $(this).data('val') || '';
		
		if( !select.is("select") ) {
			select = $("<select size=6 />").hide();
			$(this).parent().append(select);
		}
		
		if( event.which == 38 && select.is(':visible') ) {
			select.focus().find('option:last').attr('selected',true).trigger('keyup');
			return false;
		} else if( event.which == 40 && select.is(':visible') ) {
			select.focus().find('option:first').attr('selected',true).trigger('keyup');
			return false;
		} else if( event.which == 9 ) {
			return false;
		}
		
		$(this).data('id','');
		
		if( val != '' && val != dataval ) {
			$(this).data('val', val);
			select.html('').hide();
			
			$.get("/",{type:$(this).attr("name"),val:val},function(resp){
				if( resp && resp.size > 0 ) {
					$.each(resp.items,function(k, v) {
						select.append( "<option value='" +k+ "'>" +v+ "</option>" );
					});
					select.show();
				}
			});
		} else if( val == '' ) {
			$(this).data('id','');
		}
	});
	
	$(document).on("keyup",".check-list select",function(event){
		var val = $(this).val(),
			selected = $(this).find(':selected'),
			name = selected.text(),
			input = $(this).prev();
		//console.log(selected);
		if( event.which == 13 ) {
			input.val(name).data('id',val);
			$(this).hide();
			input.focus();
			
		} else if( event.which == 38 ) {
			if( $(this).data('id') == $(this).find('option:first').val() ) {
				selected.attr('selected',false);
				input.focus();
				return true;
			}
			$(this).data('id',val);
			
		} else if( event.which == 40 ) {
			if( $(this).data('id') == $(this).find('option:last').val() ) {
				selected.attr('selected',false);
				input.focus();
				return true;
			}
			$(this).data('id',val);
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
		var block = $(this).parent(),
			blocks = block.closest('.check-list');
		$(this).hide();
		$(this).find('.search').each(function(){
			if( $(this).data('id') ) $(this).val( $(this).data('id') );
		});
		
		$.post('/?' + $(this).serialize(),{"update-check":1},function(resp){
			block.addClass('added');
			block.animate({opacity:0,width:0,padding:0,margin:0},800,function(){ $(this).remove(); });
		});
		
		return false;
	});
});