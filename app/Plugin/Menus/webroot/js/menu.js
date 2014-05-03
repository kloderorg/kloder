var orderBefore = new Array();
$(function() {
	$('ol.sortable').nestedSortable({
		maxLevels: '2',
		forcePlaceholderSize: true,
		helper: 'clone',
		tabSize: 25,
		items: 'li',
		placeholder: 'ui-state-highlight',
		handle: '.handler',
		
			/*handle: '.handler',
			
			opacity: .6,
			
			
			tolerance: 'pointer',
			toleranceElement: '> div',*/
		stop: function(event, ui) {
			var id = $(ui.item[0]).attr('id').substr(5);
			var parent_id = 0;
			if ($(ui.item[0]).parent().parent().attr('id') != undefined)
				parent_id = $(ui.item[0]).parent().parent().attr('id').substr(5); if (parent_id == 'nt') parent_id = 0;
			
			var orderAfter = $('ol.sortable').nestedSortable('toArray');
			
			var old_position = 0;
			for (var i=0;i<orderBefore.length;i++) {
				if (id == orderBefore[i].item_id) {
					old_position = i;
					break;
				}
			}
			
			var new_position = 0;
			for (var i=0;i<orderAfter.length;i++) {
				if (id == orderAfter[i].item_id) {
					new_position = i;
					break;
				}
			}
			
			var delta = 0;
			var url = base_url+'platform/menu_items/move/' + id + '/';
			if (orderBefore[old_position].parent_id == orderAfter[new_position].parent_id) {
				var subitemsBefore = new Array();
				for (var j=0; j<orderBefore.length; j++)
					if (orderBefore[j].parent_id == orderBefore[old_position].parent_id)
						subitemsBefore.push(orderBefore[j]);
				
				var subitemsAfter = new Array();
				for (var j=0; j<orderAfter.length; j++)
					if (orderAfter[j].parent_id == orderAfter[new_position].parent_id)
						subitemsAfter.push(orderAfter[j]);
				
				for (var i=0;i<subitemsBefore.length;i++) {
					if (id == subitemsBefore[i].item_id) {
						old_position = i;
						break;
					}
				}
				
				for (var i=0;i<subitemsAfter.length;i++) {
					if (id == subitemsAfter[i].item_id) {
						new_position = i;
						break;
					}
				}
			
				delta = new_position - old_position;
				url += delta;
			} else {
				var subitems = new Array();
				for (var j=0; j<orderAfter.length; j++)
					if (orderAfter[j].parent_id == orderAfter[new_position].parent_id)
						subitems.push(orderAfter[j]);
				
				old_position = subitems.length - 1;
				for (var k=0;k<subitems.length;k++) {
					if (id == subitems[k].item_id) {
						new_position = k;
						break;
					}
				}
				
				delta = new_position - old_position;
				url += delta + '/' + parent_id;
			}
			window.location = url;
			
		}
	});
	orderBefore = $('ol.sortable').nestedSortable('toArray');
});