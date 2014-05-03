/*
 * Kloder 0.1.0 16/08/2013
 * http://www.kloder.com
 * 
 * Copyright (c) 2013 Kloder.com
 * Licensed under GPL license
 * http://www.kloder.com/about/license/
 */

/* > Inheritance by 'Dynamic Super Calling'
 * http://www.ajaxpath.com/javascript-inheritance
 * ************************************************************************************************ */

//Defines the top level Class
function Class() { }
Class.prototype.construct = function() {};
Class.extend = function(def) {
    var classDef = function() { if (arguments[0] !== Class) { this.construct.apply(this, arguments); }};
    var proto = new this(Class);
    var superClass = this.prototype;
    for (var n in def) { var item = def[n]; if (item instanceof Function) item.$ = superClass; proto[n] = item; }
    classDef.prototype = proto;
    classDef.extend = this.extend;
    return classDef;
};

/* > Item Class
 * ************************************************************************************************ */

var ItemClass = Class.extend({
	construct: function(view) {
		this.view = view;
		this.icarus = this.view.icarus;
		this.graphics = null;
		
		this.plugin = 'unknown';
		this.controller = 'unknown';
		
		this.data = null;
		this.id = 0;
		this.title = '';
		this.slug = '';
		this.tags = '';
		this.thumb = '';
		this.thumbError = '';
		this.created = new Date();
		this.modified = new Date();
		this.rateAverage = 0;
	},

	/**
	 * Default parser, it parse the default information about one item.
	 * You can call it with: Item.prototype.parse.call(this, data, model);
	 * The model is a string with the CakePHP model name.
	 */
    parse: function (data, model) {
		//console.log(data);
		this.data = data;
		this.id = eval('data.'+model+'.id');
		this.title = eval('data.'+model+'.title');
		if (this.title == undefined) this.title = eval('data.'+model+'.name');
		this.slug = eval('data.'+model+'.slug');
		this.tags = eval('data.'+model+'.tags');
		
		this.created = new Date();
		this.modified = new Date();
		this.created.parseMySQLDatetime(eval('data.'+model+'.created'));
		this.modified.parseMySQLDatetime(eval('data.'+model+'.modified'));
		
		this.canEdit = eval('data.'+model+'.can_edit') ? true : false;
		this.isOwner = eval('data.'+model+'.is_owner') ? true : false;
		this.isPublic = eval('data.'+model+'.is_public') ? true : false;
		
		this.actions = new Object();
		this.social = false;
		
		this.rateCurrentUser = 0;
		this.calculateRatingsAverage();
	},
	
	// This is for distinguish between folders and items
	isFolder: function () { return false; },
	
	/* > Default HTML code for the item. If you need a diferent visualization only need to
	 * overload this method in your custom item
	 * ************************************************************************************* */
	getHTML: function () {
		var out = '<li class="'+this.plugin+'-'+this.controller+' draggable droppable" id="'+this.plugin+'-'+this.controller+'-'+this.id+'">';
		//out += '<a class="icon" id="icon-'+this.plugin+'-'+this.controller+'-'+this.id+'" href="javascript:void(0);"><img src="'+this.thumb+'" width="62" height="62" /></a>';
		out += '<a style="margin:0 auto;display:block;width:62px;height:62px;background:url('+this.thumb+') top center no-repeat;background-size:100% 100%;" class="icon" id="icon-'+this.plugin+'-'+this.controller+'-'+this.id+'" href="javascript:void(0);"></a>';
		out += '<div class="title"><a href="javascript:void(0);">'+this.title+'</a></div>';
		out += '<div class="tags" style="display:none;">'+this.tags+'</div>';
		out += '</li>';
		return out;
	},
	
	/* > Draw the element and asign behaviours
	 * ************************************************************************************* */
	draw: function () {
		$(this.view.graphics+' ul:last-child').append(this.getHTML());
		this.graphics = this.view.graphics+' #'+this.plugin+'-'+this.controller+'-'+this.id;
		this.setContextMenu();
		this.setInfoBox();
		this.setToolTipText();
		this.setDisableSelection();
		this.setDraggable();
		this.setDroppable();
		this.setThumbError();
		this.setClick();
		this.setDblClick();
	},
	
	/* > Refresh the element and reasign behaviours
	 * ************************************************************************************* */
	refresh: function () {
		$(this.graphics).attr('id', this.plugin+'-'+this.controller+'-'+this.id);
		$(this.graphics).unbind();
		this.graphics = this.view.graphics+' #'+this.plugin+'-'+this.controller+'-'+this.id;
		this.setContextMenu();
		this.setInfoBox();
		this.setToolTipText();
		this.setDisableSelection();
		this.setDraggable();
		this.setDroppable();
		this.setThumbError();
		this.setClick();
		this.setDblClick();
	},
	
	/* > Behaviours
	 * ****************************************************************************** */
	
	setThumbError: function () {
		$(this.graphics+" a.icon img").error($.proxy(function () {
	  		$(this.graphics+" a.icon img").unbind("error").attr("src", this.thumbError);
	  		this.thumb = this.thumbError;
		}, this));
	},
	
	setDisableSelection: function () {
		$(this.graphics).disableSelection();
	},
	
	setDraggable: function () {
		if (this.view.hash.type == 'folder')
			if (this.canEdit) $(this.graphics).draggable({ opacity: 0.7, revert: "invalid", cancel:'.clickable', containment: this.view.graphics, zIndex: 9000 }).data('obj', this);
	},
	
	setDroppable: function () {	},
	
	setContextMenu: function () { },
	
	setClick: function () { },
	
	setDblClick: function () { },
	
	setInfoBox: function () { },
	
	copyToProject: function() {
		$.get(base_url+this.plugin+'/'+this.controller+'/copy_to_project/'+this.id, $.proxy(function (data) {
			if (data == 'OK') addGritter(('Correct').localized('Resources'), ('The resource has been copy').localized("Resources"), 'ok');
			else if (data == 'KO') addGritter(('Error').localized('Resources'), ('Don´t exist project´s folder').localized("Resources"), 'error');
			else {
				$(this.graphics).animate({opacity: 1});
				addGritter(('Error').localized('Resources'), ('There was an error at remove operation').localized("Resources"), 'error');
			}
		}, this));
	},
	
	setToolTipText: function () {
		var tiptext = this.options.toolTipText;
		$(this.graphics).qtip({
			content: { text: tiptext },
			position: { my: 'top right', at: 'bottom middle', viewport: $(window) },
			show: { solo: true, delay: 1000 }
		});
	},
	
	/* > This add the start rating behaviours
	 * ******************************************************************************************* */
	starsCallback: function(ui, type, value) {
		var url = base_url+this.plugin+'/'+this.controller+'/rate/'+this.id+'/'+value;
		if (ui.options.checked == -1) url = base_url+this.plugin+'/'+this.controller+'/remove_rate/'+this.id
		
		$.getJSON(url, $.proxy(function(response) {
			if (response.status == 'ok') {
				this.rateAverage = response.data.rating;
				this.rateCurrentUser = value;
				$('.rate-average', this.view.icarus.graphics).html('('+Math.round(this.rateAverage)+')');
			}
		}, this));
	},
	
	/* > This method calculate the average of the item at the moment
	 * ********************************************************************************************** */
	calculateRatingsAverage: function () {
		var res = 0;
		for (var i=0; i<this.data.Rating.length; i++) {
			if (this.data.Rating[i].user_id == user_id) this.rateCurrentUser = this.data.Rating[i].value;
			res += parseFloat(this.data.Rating[i].value);
		}
		if (res == 0) this.rateAverage = 0;
		else this.rateAverage = res / this.data.Rating.length;
	},
	
	/* > This is the common remove behaviour to be used as callback
	 * *********************************************************************************************** */
	remove: function () {
		$('<p><span class="ui-icon ui-icon-alert" style="z-index:9999;float:left; margin:0 7px 20px 0;"></span>'+("Are you sure to remove").localized("Resources")+' \''+this.title+'\'?</p>').dialog({
			title: ('Remove').localized("Resources")+" "+this.title,
			resizable: false, modal: true,
			buttons: [{
    			text: ("Remove").localized("Resources"),
    			click: $.proxy(function () {
					$(".ui-dialog-content").dialog("close");
					$(this.graphics).animate({opacity: .5});
					$.get(base_url+this.plugin+'/'+this.controller+'/remove/'+this.id, $.proxy(function (data) {
						if (data == 'OK') {
							$(this.graphics).fadeOut('slow', $.proxy(function () {
								$(this.graphics).remove();
							}, this));
						} else {
							$(this.graphics).animate({opacity: 1});
							addGritter('Error', ('There was an error at remove operation!').localized("Resources"), 'error');
						}
					}, this));
				}, this)
    		}, {
    			text: ("Cancel").localized("Resources"),
    			click: function() { $(this).dialog("close"); }
    		}]
		})
	},
	
	/* > This is the common rename behaviour to be used as callback
	 * ************************************************************************************************ */
	rename: function() {
		$(this.graphics).find('.title').html('<input type="text" value="'+this.title+'" maxlength="255" style="width: 95%" />');
		$(this.graphics).find('input').focus();
		$(this.graphics).find('input').bind('focusout keyup', $.proxy(function (e) {
			e.stopImmediatePropagation();
			if (e.type == 'keyup' && e.keyCode != 13) return;
			if (this.title != $(this.graphics).find('input').val()) {
				var title = $(this.graphics).find('input').val();
				var requestUri = base_url+this.plugin+'/'+this.controller+'/ren';
				$(this.graphics).animate({opacity: .5});
				$.post(requestUri, { id: this.id, name: title }, $.proxy(function(response) {
					if (response.status == 'ok') {
						this.title = title;
						$(this.graphics).find('.title').html('<a href="javascript:void(0);">'+this.title+'</a>');
					} else {
						addGritter('Error', 'There was an error at rename operation!', 'error');
						$(this.graphics).find('.title').html('<a href="javascript:void(0);">'+this.title+'</a>');
					}
					$(this.graphics).animate({opacity: 1});
				},this), 'json');
			} else {
				$(this.graphics).find('.title').html('<a href="javascript:void(0);">'+this.title+'</a>');
			}
		}, this));
	},
	
	/* > This is the common permissions behaviour
	 * ************************************************************************************************ */
	permissions: function () {
		var dialog = $('<div></div>');
		dialog.append('<img src="'+base_url+'resources/img/ajax-loader.gif" alt="loading" title="loading">');
		
		var title = ('Permissions').localized('Resources');
		dialog.dialog({ modal: true, title: title+": "+this.title, autoOpen: true, width: 800, height: $(window).height() * 0.9,
			open: $.proxy(function () { dialog.load(base_url+this.plugin+'/'+this.controller+'/rowaccess/'+this.id); }, this),
			close: function(event, ui) { $(this).remove(); }
		});
	},
	
	/* > Check if an image is from images (for scale propose)
	 * ************************************************************************************************ */
	isInnerImage: function(url) {
		var basePath = base_url+'resources/images/get';
		var urlPath = url.slice(0,basePath.length+1);
		if (url.slice(0,basePath.length) == basePath) return true;
		return false;
	}
	
});

/* ************************************************************************************************ *
 * > ResourcesLinkClass
 * ************************************************************************************************ */

var ResourcesLinkClass = ItemClass.extend({
	
	// Constructor
	
	construct: function(view) {
		arguments.callee.$.construct.call(this, view);
		this.plugin = 'resources';
		this.controller = 'resources_links';
	},
    
    parse: function (data) {
    	arguments.callee.$.parse.call(this, data, 'ResourcesLink');    	
		this.link = data.ResourcesLink.link;
		this.comment = data.ResourcesLink.comment;
		this.thumb = data.ResourcesLink.thumb == '' ? 'http://www.thumbshots.de/cgi-bin/show.cgi?url='+this.link : data.ResourcesLink.thumb;
		if (this.isInnerImage(this.thumb)) this.thumb += '/width:128/height:128/mode:fit';
		this.thumbError = base_url+'resources/img/icarus/mime-types/link.png';
	},
    
    getHTML: function () {
		var out = '';
		out += '<li class="'+this.plugin+'-'+this.controller+' draggable droppable" id="'+this.plugin+'-'+this.controller+'-'+this.id+'">';
		//out += '	<a class="icon" id="icon-'+this.plugin+'-'+this.controller+'-'+this.id+'" href="javascript:void(0);"><img src="'+this.thumb+'" width="62" height="62" /></a>';
		out += '	<a style="margin:0 auto;display:block;width:62px;height:62px;background:url('+this.thumb+') top center no-repeat;background-size:100% 100%;" class="icon" id="icon-'+this.plugin+'-'+this.controller+'-'+this.id+'" href="javascript:void(0);"></a>';
		out += '	<div class="title">';
		out += ' 		<a class="icon-type" href="'+this.link+'" target="_blank"><img alt="'+this.title+'" src="'+base_url+'resources/img/icarus/actions/link-small.png" align="top" /></a> <a href="javascript:void(0);">'+this.title+'</a>';
		out += '	</div>';
		out += '<div class="tags" style="display:none;">'+this.tags+'</div>';
		out += '</li>';
		return out;
	},
	
	// Behaviours
	
	setToolTipText: function () {
		var tiptext = '<b>'+this.title+'</b>';
		tiptext += '<br /><b>'+('Link').localized('Resources')+':</b> ' + this.link;
		tiptext += '<br /><b>'+('User').localized('Resources')+':</b> ' + this.data.User.login;
		$(this.graphics).qtip({
			content: { text: tiptext },
			position: { my: 'top right',	at: 'bottom middle', viewport: $(window) },
			show: { solo: true, delay: 1000 }
		});
	},
	
	setContextMenu: function () {
		$.contextMenu({
	        selector: this.graphics, 
	        callback: $.proxy(function(key, options) {
	        	switch(key) {
	        		case 'launch': window.open(this.link); break;
	        		case 'view': window.location.href = base_url+'resources/resources_links/view/'+this.id; break;
	        		case 'rename': this.rename(); break;
	        		case 'copy': this.copyToProject(); break;
	        		case 'permissions': this.permissions(); break;
	        		case 'edit': window.location.href = base_url+'resources/resources_links/edit/'+this.id; break;
	        		case 'remove': this.remove(); break;
	        	}
	        }, this),
	        items: {
	        	"launch": {name: ('Launch').localized('Resources'), icon: "launch" },
	        	"sep1": "---------",
	        	"view": {name: ('View').localized('Resources'), icon: "view"},
	        	"rename": {name: ('Rename').localized('Resources'), icon: "rename", disabled: $.proxy(function(key, opt) { return !this.isOwner; }, this)},
	        	"copy": {name: ('Copy to active project').localized('Resources'), icon: "copy", disabled: $.proxy(function(key, opt) { return this.icarus.settings.embed || this.icarus.settings.project_slug; }, this)},
	        	"sep2": "---------",
	        	"permissions": {name: ('Permissions').localized('Resources'), icon: "permissions"},
	        	"edit": {name: ('Edit').localized('Resources'), icon: "edit", disabled: $.proxy(function(key, opt) { return !this.canEdit; }, this)},
	        	"remove": {name: ('Remove').localized('Resources'), icon: "remove", disabled: $.proxy(function(key, opt) { return !this.isOwner; }, this)}
	        }
	    });
	},
	
	setInfoBox: function () {
		this.actions = new Array();
		this.actions['launch'] = { target: '_blank', href: this.link, icon: base_url+'resources/img/icarus/actions/link.png', text: ('Launch').localized('Resources') };
		this.actions['view'] = { href: base_url+'resources/resources_links/view/'+this.id, icon: base_url+'resources/img/icarus/actions/view.png', text: ('View').localized('Resources') };
		if (this.isOwner) this.actions['rename'] = { icon: base_url+'resources/img/icarus/actions/rename.png', text: ('Rename').localized('Resources'), callback: 'rename' };
		if ((!this.icarus.settings.embed)&&(!this.icarus.settings.project_slug)) this.actions['copyToProject'] = { icon: base_url+'resources/img/icarus/actions/copy.png', text: ('Copy to active project').localized('Resources'), callback: 'copyToProject' };
		this.actions['permissions'] = { icon: base_url+'resources/img/icarus/actions/rowaccess.png', text: ('Security').localized('Resources'), callback: 'permissions' };
		if (this.canEdit) this.actions['edit'] = { href: base_url+'resources/resources_links/edit/'+this.id, icon: base_url+'img/actions/edit.png', text: ('Edit').localized('Resources') };
		if (this.isOwner) this.actions['remove'] = { icon: base_url+'resources/img/icarus/actions/remove.png', text: ('Delete').localized('Resources'), callback: 'remove' };
	},
	
	setClick: function () { $(this.graphics).click($.proxy(function (e) { this.view.icarus.infobox.show(this); }, this)); },
	setDblClick: function () { $(this.graphics).dblclick($.proxy(function (e) { window.open(this.link); }, this)); }
});

/* ************************************************************************************************ *
 * > File Class
 * ************************************************************************************************ */

var ResourcesFileClass = ItemClass.extend({
	
	// Constructor
	
	construct: function(view) {
		arguments.callee.$.construct.call(this, view);
		this.plugin = 'resources';
		this.controller = 'resources_files';
	},
    
    parse: function (data) {
    	arguments.callee.$.parse.call(this, data, 'ResourcesFile');
		this.extension = data.ResourcesFile.extension;
		this.size = data.ResourcesFile.size;
		this.thumb = base_url+'resources/img/icarus/mime-types/file.png';
	},
	
	// Behaviours
	
	setToolTipText: function () {
		var tiptext = '<b>'+this.title+'</b>';
		tiptext += '<br /><b>'+('Size').localized('Resources')+':</b> ' + Number(this.size).bytesToSize();
		tiptext += '<br /><b>'+('Extension').localized('Resources')+':</b> ' + this.extension;
		tiptext += '<br /><b>'+('User').localized('Resources')+':</b> ' + this.data.User.login;
		$(this.graphics).qtip({
			content: { text: tiptext },
			position: { my: 'top right',	at: 'bottom middle', viewport: $(window) },
			show: { solo: true, delay: 1000 }
		});
	},
	
	setContextMenu: function () {
		$.contextMenu({
	        selector: this.graphics, 
	        callback: $.proxy(function(key, options) {
	        	switch(key) {
	        		case 'download': window.location.href = base_url+'resources/download/'+this.id; break;
	        		case 'rename': this.rename(); break;
	        		case 'copy': this.copyToProject(); break;
	        		case 'permissions': this.permissions(); break;
	        		case 'remove': this.remove(); break;
	        	}
	        }, this),
	        items: {
	        	"download": {name: ('Download').localized('Resources'), icon: "download" },
	        	"sep1": "---------",
	        	"rename": {name: ('Rename').localized('Resources'), icon: "rename", disabled: $.proxy(function(key, opt) { return !this.isOwner; }, this)},
	        	"copy": {name: ('Copy to active project').localized('Resources'), icon: "copy", disabled: $.proxy(function(key, opt) { return this.icarus.settings.embed || this.icarus.settings.project_slug; }, this)},
	        	"sep2": "---------",
	        	"permissions": {name: ('Permissions').localized('Resources'), icon: "permissions"},
	        	"remove": {name: ('Remove').localized('Resources'), icon: "remove", disabled: $.proxy(function(key, opt) { return !this.isOwner; }, this)}
	        }
	    });
	},
	
	setInfoBox: function () {
		this.actions = [];
		this.actions['download'] = { href: base_url+'resources/download/'+this.id, icon: base_url+'resources/img/icarus/actions/download.png', text: ('Download').localized('Resources') };
		if (this.isOwner) this.actions['rename'] = { icon: base_url+'resources/img/icarus/actions/rename.png', text: ('Rename').localized('Resources'), callback: 'rename' };
		if ((!this.icarus.settings.embed)&&(!this.icarus.settings.project_slug)) this.actions['copyToProject'] = { icon: base_url+'resources/img/icarus/actions/copy.png', text: ('Copy to active project').localized('Resources'), callback: 'copyToProject' };
		this.actions['permissions'] = { icon: base_url+'resources/img/icarus/actions/rowaccess.png', text: ('Security').localized('Resources'), callback: 'permissions' };
		if (this.isOwner) this.actions['remove'] = { icon: base_url+'resources/img/icarus/actions/remove.png', text: ('Delete').localized('Resources'), callback: 'remove' };
	},
	
	setClick: function () { $(this.graphics).click($.proxy(function (e) { this.view.icarus.infobox.show(this); }, this)); },
	setDblClick: function () { $(this.graphics).dblclick($.proxy(function (e) { window.location.href = base_url+'resources/download/'+this.id; }, this)); }
});

/* ************************************************************************************************ *
 * > Folder Class
 * ************************************************************************************************ */

var FolderClass = ItemClass.extend({
	
	// Constructor
	
	construct: function(view) {
		arguments.callee.$.construct.call(this, view);
		this.plugin = 'resources';
		this.controller = 'resources_folders';
		
		this.isBack = false;
		this.thumb = base_url+'resources/img/icarus/mime-types/folder.png';
		
		this.projectId = 0;
		this.userId = 0;
		this.companyId = 0;
		
		this.graphics = null;
	},
	
	parse: function (data, draw) {
		if (draw == undefined) draw = true;
		this.data = data;
		this.id = data.ResourcesFolder.id;
		this.title = data.ResourcesFolder.title;
		this.slug = data.ResourcesFolder.slug;
		this.projectId = data.ResourcesFolder.project_id;
		this.rowaccess = data.ResourcesFolder.unserialized_rowaccess;
		
		this.social = false;
		this.actions = new Object();
		
		this.canEdit = data.ResourcesFolder.can_edit ? true : false;
		this.isOwner = data.ResourcesFolder.is_owner ? true : false;
		
		if (this.isProjectFolder()) this.thumb = base_url+'resources/img/icarus/mime-types/folder-project.png';
		// TODO rest of the types
		
		if (draw) this.draw();
	},
	
	parseBackFolder: function (parent_folder_id) {
		this.id = parent_folder_id;
		this.title = ' <b>&bull;&bull;</b>';
		this.isBack = true;
		this.thumb = base_url+'resources/img/icarus/mime-types/folder.png';
		this.draw();
	},
	
	getHTML: function () {
		var fodlerClass = 'droppable';
		fodlerClass += this.isBack ? ' folder-back' : ' folder draggable';
		var out = '<li class="'+fodlerClass+'" id="'+this.plugin+'-'+this.controller+'-'+this.id+'">';
		out += '<a href="javascript:void(0);" class="icon"><img alt="'+this.title+'" src="'+this.thumb+'" width="62" height="62" /></a>';
		out += '<div class="title"><a href="javascript:void(0);">'+this.title+'</a></div>';
		out += '</li>';
		return out;
	},
	
	// Functions
	
	isFolder: function () { return true; },
	isProjectFolder: function () { return this.projectId > 0; },
	
	open: function () {
		if (this.isBack) {
			var hash = this.view.hash.getPreviousFolderHash();
			if (!this.view.icarus.settings.embed) hash.setBrowserHistory(); else this.view.viewsCollection.requestHash(hash);
		} else {
			if (this.view.hash.type == 'search') {
				var hash = new HashClass(this.view.icarus, 'folder', this.data.ResourcesFolder.folder_slug + this.slug);
				if (!this.view.icarus.settings.embed) hash.setBrowserHistory(); else this.view.viewsCollection.requestHash(hash);
			} else {
				var hash = this.view.hash.getNextFolderHash(this);
				if (!this.view.icarus.settings.embed) hash.setBrowserHistory(); else this.view.viewsCollection.requestHash(hash);
			}
		}
	},
	
	rename: function() {
		$(this.graphics).find('.title').html('<input type="text" value="'+this.title+'" maxlength="255" style="width: 95%" />');
		$(this.graphics).find('input').focus();
		$(this.graphics).find('input').bind('focusout keyup', $.proxy(function (e) {
			if (e.type == 'keyup' && e.keyCode != 13) return;
			if (this.id == 'temp') {
				$(this.graphics).animate({opacity: .5});
				var title = $(this.graphics).find('input').val();
				var addUri = base_url+'resources/resources_folders/add';
				$.post(addUri, { title: title, parent_id: this.view.getFolderId() }, $.proxy(function(response) {
					if (response.status == 'error') {
						addGritter('Error', ('There was an error at create operation!').localized('Resources'), 'error');
						$(this.graphics).fadeOut('slow', function () { $(this).remove(); });
					} else if (response.status == 'ok') {
						this.parse(response.data, false);
						this.refresh();
						$(this.graphics).animate({opacity: 1});
						$(this.graphics).find('.title').html('<a href="javascript:void(0);">'+this.title+'</a>');
					}
				},this), 'JSON');
			} else {
				if (this.title != $(this.graphics).find('input').val()) {
					$(this.graphics).animate({opacity: .5});
					var title = $(this.graphics).find('input').val();
					var renUri = base_url+'resources/resources_folders/ren/';
					$.post(renUri, { id: this.id, title: title }, $.proxy(function(response) {
						if (response.status == 'ok') {
							this.title = title;
							$(this.graphics).animate({opacity: 1});
							$(this.graphics).find('.title').html('<a href="javascript:void(0);">'+this.title+'</a>');
						} else {
							addGritter('Error', ('There was an error at rename operation!').localized('Resources'), 'error');
							$(this.graphics).find('.title').html('<a href="javascript:void(0);">'+this.title+'</a>');
							$(this.graphics).animate({opacity: 1});
						}
					},this), 'JSON');
				} else {
					$(this.graphics).find('.title').html('<a href="javascript:void(0);">'+this.title+'</a>');
				}
			}
		}, this));
	},
	
	// Behaviours
	
	setToolTipText: function () {
		var tiptext = '<b>'+this.title+'</b>';
		tiptext += '<br /><b>'+('User').localized('Resources')+':</b> ' + this.data.User.login;
		$(this.graphics).qtip({
			content: { text: tiptext },
			position: { my: 'top right',	at: 'bottom middle', viewport: $(window) },
			show: { solo: true, delay: 1000 }
		});
	},
	
	setContextMenu: function () {
		if (!this.isBack && !this.isProjectFolder()) {
			$.contextMenu({
		        selector: this.graphics,
		        callback: $.proxy(function(key, options) {
		        	switch(key) {
		        		case 'open': this.open(); break;
		        		case 'generatescorm': window.location.href = base_url+'resources_elearnings/resources_elearnings_scorms/generator/'+this.id; break;
		        		case 'rename': this.rename(); break;
		        		case 'permissions': this.permissions(); break;
		        		case 'remove': this.remove(); break;
		        	}
		        }, this),
		        items: {
		        	"open": {name: ('Open').localized('Resources'), icon: "open" },
		        	"fold1a": {
		                "name": ('Generate').localized('Resources'), 
		                "items": {
		                    "generatescorm": {"name": ('SCORM').localized('Resources'), icon: "scorm"}
						}
					},
		        	"sep1": "---------",
		        	"rename": {name: ('Rename').localized('Resources'), icon: "rename", disabled: $.proxy(function(key, opt) { return !this.isOwner; }, this)},
		        	"sep2": "---------",
		        	"permissions": {name: ('Permissions').localized('Resources'), icon: "permissions"},
		        	"remove": {name: ('Remove').localized('Resources'), icon: "remove", disabled: $.proxy(function(key, opt) { return !this.isOwner; }, this)}
		        }
		    });
		}
	},
	
	setInfoBox: function () { this.actions = []; },
	
	setClick: function () {
		$(this.graphics).click($.proxy(function (e) { this.open(); }, this));
	},
	
	setDraggable: function () {
		if (this.view.hash.type == 'folder')
			if (!this.isBack && !this.isProjectFolder())
				$(this.graphics).draggable({ opacity: 0.7, revert: "invalid", cancel:'.clickable', containment: this.view.graphics, zIndex: 9000 }).data('obj', this);
	},
	
	setDroppable: function () {
		$(this.graphics).droppable({ accept: ".draggable", hoverClass: "drop" });
		$(this.graphics).bind("drop", $.proxy(function(event, ui) {
			var obj = $(ui.draggable).data("obj");
			$(obj.graphics).animate({opacity: .5});
			$.get(base_url+obj.plugin+'/'+obj.controller+'/move/'+obj.id+'/'+this.id, $.proxy(function(data) {
				if (data == 'OK') {
					$(obj.graphics).fadeOut('fast', function () { $(this).remove(); });
					for (var i=0;i<this.view.viewsCollection.views.length;i++)
						if (this.view.viewsCollection.views[i].getFolderId() == this.id) {
							this.view.viewsCollection.views[i].forceReload = true;
							break;
						}
				} else {
					$(obj.graphics).animate({opacity: 1});
					addGritter('Error', ('There was an error at move operation!').localized('Resources'), 'error');
				}
			}, this));
		}, this));
	}
	
});

/* ************************************************************************************************ *
 * > Hash Class
 * ************************************************************************************************ */

var HashClass = Class.extend({
	
	// Constructor
	
	construct: function(icarus, type, uri) {
		if (type == undefined) type = '';
		if (uri == undefined) uri = '';
		this.icarus = icarus;
		this.type = type;		// Type of hash (folder, search, view)
		this.uri = uri;		// The other part of the hash: (folder-slug/folder-slug/, search-string, object-type/object-id)
	},
	
	// Functions
	
	getBrowserHistory: function () { this.fromString(window.location.hash); },
	setBrowserHistory: function () { window.location.hash = this.toHashString(); },
	
	// Prepare slug for get process
	getFolderSlug: function () { return this.uri.replace(/\//gi, '|'); },
	
	getNextFolderHash: function (folder) {
		return new HashClass(this.icarus, 'folder', ((this.uri == '') ? folder.slug : this.uri + '/' + folder.slug));
	},
	
	getPreviousFolderHash: function () {
		var newHash = new HashClass(this.icarus, 'folder', '');
		if (this.uri != '') {
			var t = this.uri.split('/');
			if (t.length > 1) { var res = t[0]; for (var i=1;i<t.length-1;i++) res += '/' + t[i]; newHash.uri = res;
			} else { newHash.uri = ''; }
		}
		return newHash;
	},
	
	isDeeperThan: function (hash) {
		var currentLevel = this.getLevel(); var nextLevel = hash.getLevel();
		if (currentLevel > nextLevel) return true; return false;
	},
	
	// Utils
	
	equals: function (hash) {
		if (this.type != hash.type) return false;
		if (this.uri != hash.uri) return false;
		return true;
	},
	
	isEmpty: function () {
		if (this.uri == '') return true;
		return false;
	},
	
	getLevel: function () { if (this.uri == '') return 0; var divs = this.uri.split('/'); return divs.length; },
	
	toHashString: function () {
		if (this.type == 'folder') return '#folder://' + this.filterUri();
		else if (this.type == 'search') return '#search://' + this.uri;
		return null;
	},
	
	// read hash from string
	fromString: function (hashstring) {
		if (hashstring == '') {
			this.type = 'folder'; this.uri = '';
		} else {
			this.type = this.getType(hashstring);
			this.uri = this.getUri(hashstring);
		}
	},
	
	// Remove last slash from the uri
	filterUri: function () { if (this.uri.charAt(this.uri.length-1) == '/') this.uri = this.uri.substring(0, this.uri.length-1); return this.uri; },
	
	// Get uri from string
	getUri: function (hashstring) { if (hashstring.length > 10) return hashstring.substr(10); return ''; },
	
	// Get type from string
	getType: function (hashstring) {
		if (hashstring.substr(0, 10) == '#folder://') return 'folder';
		else if (hashstring.substr(0, 10) == '#search://') return 'search';
		else return '';
	}
});

/* ************************************************************************************************ *
 * > Infobox Class
 * ************************************************************************************************ */

var InfoboxClass = Class.extend({
	
	// Constructor
	
	construct: function(icarus) {
		this.icarus = icarus;
		this.viewsCollection = this.icarus.viewsCollection;
		this.graphics = this.icarus.graphics+' .infobox';
		var out = '<div class="infobox">';
		out += '<div class="close"><a href="javascript:void(0);"><img src="'+base_url+'resources/img/icarus/slidedown.png" /></a></div>';
		out += '<div class="info">';
		out += '<img class="thumbnail" src="" height="120" style="max-width: 200px;" />';
		out += '<div class="actions" style="text-align:right;float:right;margin-bottom:20px;width:400px;font-size:12px;"></div>';
		out += '<div class="title" style="font-size:16px;"></div>';
		//out += '<div class="link" style="font-size: 12px;"></div>';
		out += '<div class="user" style="font-size: 12px;"></div>';
		out += '<div class="content" style="font-size: 12px;"></div>';
		out += '</div></div>';
		$(this.icarus.graphics).append(out);
		$(this.graphics+' .close a').click($.proxy(function() { this.close(); }, this));
	},
	
	// Functions
	
	show: function (data) {
		$(this.graphics+' .title').html('<h3 style="margin-bottom: 0;">'+data.title.truncate(55)+'</h3>');
		//if (data.link != null) $(this.graphics+' .link').html('<a href="'+data.link+'" style="margin-bottom: 0;">'+data.link.truncate(80)+'</a>'); else $(this.graphics+' .link').html('');
		$(this.graphics+' .user').html(('User').localized("Resources")+": "+data.data.User.login);
		
		
		$(this.graphics+' .thumbnail').attr('src', data.thumb);
		
		$('.actions', this.graphics).html('');
		for (var key in data.actions) {
			if ($.isFunction(data.actions[key])) continue;
			var out = '';
			out += '<a';
			out += ' id="'+key+'"';
			if (data.actions[key].target && data.actions[key].target != '_self') out += ' target="'+data.actions[key].target+'"'; 
			if (data.actions[key].href) out += ' href="'+data.actions[key].href+'"'; else out += ' href="javascript:void(0);"';
			out += ' style="display:inline-block;" class="button" >';

			if (data.actions[key].icon) out += '<img src="'+data.actions[key].icon+'" />&nbsp;';
			if (data.actions[key].text) out += data.actions[key].text;
			
			out += '</a>';
			$('.actions', this.graphics).append(out);
			
			if (data.actions[key].callback) {
				var callback = data.actions[key].callback;
				$(this.graphics+' .actions #'+key).click($.proxy(eval('data.'+callback), data));
			}
		}
		
		var out = '';
		if (data.comment != undefined && data.comment != '') out += '<p class="comment">'+data.comment.cleanHTML().truncate(250)+'</p>';
		else if (data.content) out += '<p class="comment">'+data.content.cleanHTML().truncate(250)+'</p>';
		
		out += '<table>';
			if (data.tags != '' && data.tags != null) out += '<tr><td colspan="2">'+('Tags').localized('Resources')+': '+data.tags.truncate(60)+'</td></tr>';
			out += '<tr><td style="width:100px;"><div class="ratings"><input type="radio" name="newrate" value="1" /><input type="radio" name="newrate" value="2" /><input type="radio" name="newrate" value="3" /><input type="radio" name="newrate" value="4" /><input type="radio" name="newrate" value="5" /><input type="radio" name="newrate" value="6" /><input type="radio" name="newrate" value="7" /><input type="radio" name="newrate" value="8" /><input type="radio" name="newrate" value="9" /><input type="radio" name="newrate" value="10" /><span id="stars-cap"></span></div></td><td class="rate-average">('+Math.round(data.rateAverage)+')</td></tr>';
			if (data.social) out += '<tr><td><div class="social"></div></td></tr>';
		out += '</table>';
		out += '<div style="clear:both"></div>';
		$(this.graphics+' .content').html(out);
		
		//console.log(data.starsCallback);
		
		$(this.graphics+' .ratings').stars({ split: 2, callback: $.proxy(data.starsCallback, data) });
		$(this.graphics+' .ratings').stars("select", Math.round(data.rateCurrentUser));
		$(this.graphics+' .ratings .ui-stars-cancel a').html('');
		
		$(this.graphics).show(0, $.proxy(function () {
			this.resize();
			if (data.social) {
				if (!this.icarus.settings.embed) {
					out = '<table><tr>';
						out += '<td><iframe class="facebook" src="http://www.facebook.com/plugins/like.php?href='+data.link+'&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:125px; height:21px;" allowTransparency="true"></iframe></td>';
						out += '<td><a href="http://twitter.com/share" class="twitter-share-button" data-url="'+data.link+'" data-text="'+data.link+'" data-count="horizontal" data-lang="es">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></td>';
						out += '<td><script class="gpluss" type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: "es"}</script><g:plusone size="medium" href="'+data.link+'"></g:plusone></td>';
					out += '</tr></table>';
					$(this.graphics+' .social').html(out);
				}
			}
		}, this));
	},
	
	resize: function () {
		var o = $(this.viewsCollection.graphics).offset();
		var w = $(this.viewsCollection.graphics).width();
		var h = $(this.viewsCollection.graphics).height()+1;
		o.left += 1;
		o.top += h - 172;
		$(this.graphics).offset(o);
		$(this.graphics).width(w);
	},
	
	close: function() { $(this.graphics).hide(); }
	
});

/* ************************************************************************************************ *
 * > Loading screen
 * ************************************************************************************************ */

var ViewLoadingClass = Class.extend({
	
	// Constructor
	
	construct: function(viewsCollection) {
		this.viewsCollection = viewsCollection;
		this.icarus = this.viewsCollection.icarus;
		this.graphics = this.icarus.graphics+' .loading';
		this.code = '<div class="loading"></div>';
	},
	
	// Functions
	
	show: function () {
		if ($(this.graphics).length == 0) {
			$(this.icarus.graphics).append(this.code);
			this.update();
		}
	},
	
	showMessage: function (text) {
		$(this.graphics).html('<div>'+text+'<br /><a href="'+base_url+'resources/#folder://">'+('Go home!').localized('Resources')+'</a></div>');
	},
	
	update: function () {
		var o = $(this.viewsCollection.graphics).offset();
		var w = $(this.viewsCollection.graphics).innerWidth()+1;
		var h = $(this.viewsCollection.graphics).innerHeight()+1;
		$(this.graphics).offset(o);
		$(this.graphics).width(w).height(h);
	},
	
	hide: function () {
		$(this.graphics).fadeOut('slow', function () {
			$(this).remove();
		});
	}
	
});

/* ************************************************************************************************ *
 * > View class
 * ************************************************************************************************ */

var ViewClass = Class.extend({
	
	// Constructor
	
	construct: function(viewsCollection, hash) {
		this.viewsCollection = viewsCollection;
		this.icarus = this.viewsCollection.icarus;
		this.hash = hash;
		
		this.id = this.viewsCollection.views.length;
		
		this.breadcrumb = '';
		this.graphics = null;
		
		this.draw(this.id);
		this.refresh();
	},
	
	// Functions
	
	refresh: function () {
		this.viewsCollection.loading.show();
		this.icarus.loadingButton();
		//alert(this.hash.uri);
		switch (this.hash.type) {
			case 'folder': $.getJSON(base_url+'resources/resources/items_folder/'+this.hash.getFolderSlug(), { }, $.proxy(this.refreshCallback, this)); break;
			case 'search': $.getJSON(base_url+'resources/resources/items_search/'+this.hash.uri, { }, $.proxy(this.refreshCallback, this)); break;
		}
	},

	draw: function (id) {
		this.id = id;
		var out = '<div id="view-'+this.id+'" class="view grid"></div>';
		$(this.viewsCollection.graphics).append(out);
		this.graphics = this.viewsCollection.graphics + ' #view-' + this.id;
		/*$(this.graphics).dragToSelect({
    		selectables: 'li', 
    		onHide: function () {
        		//alert($('#jquery-drag-to-select-example li.selected').length + ' selected');
    		}
		});*/
	},
	
	hide: function () {
		$(this.graphics).hide();
	},
	
	getPath: function(path) {
		var out = ''; var slug = '';
		for(var i=0;i<path.length;i++) {
			if (path[i].ResourcesFolder == undefined) continue;
			out += path[i].ResourcesFolder.title + '/';
			if (i!=0) slug += path[i].ResourcesFolder.slug + '/';
		}
		out = '<a href="'+base_url+'resources/#folder://'+slug+'">'+out+'</a>';
		return out;
	},
	
	updateBreadCrumb: function () {
		$('.statusbar', this.icarus.graphics).html(this.breadcrumb).jBreadCrumb();
		$('.statusbar', this.icarus.graphics).width($(this.icarus.graphics).width() - 10);
		if (this.icarus.settings.embed) {
			$('.statusbar a', this.icarus.graphics).each($.proxy(function(index, el) {
				$(el).click($.proxy(function () {
					var hash = new HashClass(this.icarus);
					hash.fromString($(el).attr('href'));
					this.viewsCollection.requestHash(hash);
					return false;
				}, this));
			}, this));
		}
	},
	
	setContextMenu: function () { }
	
});

/* ************************************************************************************************ *
 * > Specific class for folder
 * ************************************************************************************************ */

var ViewFolderClass = ViewClass.extend({
	
	construct: function(viewsCollection, hash) {
		arguments.callee.$.construct.call(this, viewsCollection, hash);
		this.forceReload = false;
		this.type = 'folder';
		this.folder = null; // Store current folder data
		this.breadcrumb = '';
	},
	
	show: function (direction) {
		if (direction == null) direction = 'right';
		$(this.graphics).show("slide", { direction: direction }, 'fast');
		this.updateBreadCrumb();
		if (!this.icarus.settings.embed) $.cookie('actual-folder-slug', this.hash.toHashString(), { path: '/' });
		if (!this.icarus.settings.embed) $.cookie('actual-folder-id', this.getFolderId(), { path: '/' });
		
		if (this.canEdit()) {
			this.icarus.enableButtons();
			this.icarus.enableEdit();
			this.setContextMenu();
		} else {
			this.icarus.disableEdit();
			this.icarus.enableButtons('right');
		}
		
		$('#search', this.icarus.graphics).val('');
		$('#search', this.icarus.graphics).change();
		this.viewsCollection.loading.hide();
		$('li .icon img').lazyload({ effect : "fadeIn", container: $(this.graphics) });
	},
	
	refreshCallback: function (data) {
		
		if (data.status == 'error') {
			$(this.viewsCollection.loading.graphics).addClass('text');
			if (data.code = 404) this.viewsCollection.loading.showMessage(('This folder doesn\'t exists').localized('Resources'));
			this.viewsCollection.views.pop();
			return;
			//console.log(data.message);
		}
		
		this.folder = data.folder;
		this.setBreadCrumb(data.folder);
		
		$(this.graphics).html('<ul class="sortable">');
		
		$(this.graphics + ', #icarus').contextMenu('viewContextMenu', {
			bindings: {'new_fodler': $.proxy(function(t) { this.icarus.createFolder(); }, this)},
			menuStyle: {width: '150px'}
		});
		
		var folders = new Array();
		if (this.hash.uri != '') {
			if (this.icarus.settings.embed || data.folder[data.folder.length-1].ResourcesFolder.project_id == 0) {
				var folder = new FolderClass(this);
				folder.parseBackFolder(this.getParentFolderId());
			}
		}
		for (var i=0;i<data.folders.length;i++) {
			if (data.folders[i].ResourcesFolder.project_id != 0 && !this.icarus.settings.embed) continue;
			var folder = new FolderClass(this);
			folder.parse(data.folders[i]);
		}
		
		for (var i=0;i<data.items.length;i++)
			for (var j=0;j<resourcesTypes.length;j++)
				if ('object' === typeof eval('data.items[i].'+resourcesTypes[j])) {
					var item = null;
					var found = false;					
					
					if (resourcesTypes[j] == 'ResourcesFile') {
						for(var k=0;k<resourcesExtensionsFileType.length;k++)
							if (data.items[i].ResourcesFile.extension == resourcesExtensionsFileType[k][0]) {
								//Si existe un filtro, mostramos solo el filtro, independientemente de si está embebido o no
								console.log(icarusFilters);
								if (icarusActiveFilters.length != 0){
									for(var m=0;m<icarusFilters.length;m++){
										if ((icarusFilters[m][3][1]=="true") && (icarusFilters[m][2][1].exists(resourcesExtensionsFileType[k][1])))
											item = eval('new '+resourcesExtensionsFileType[k][1]+'(this)');
									}
									found = true;
									break;
								} else {
									item = eval('new '+resourcesExtensionsFileType[k][1]+'(this)');
									found = true;
									break;
								}
								/*if (this.icarus.settings.embed) {
									if (resourcesExtensionsFileType[k][1] == 'ResourcesFileImageClass') {
										item = eval('new '+resourcesExtensionsFileType[k][1]+'(this)');
									}
									found = true;
									break;
								} else {
									item = eval('new '+resourcesExtensionsFileType[k][1]+'(this)');
									found = true;
									break;
								}*/
							}
					} else if (resourcesTypes[j] == 'ResourcesLink') {
						for(var k=0;k<resourcesExtensionsLinkType.length;k++)
							if (data.items[i].ResourcesLink.link.match(resourcesExtensionsLinkType[k][0])) {
								if (this.icarus.settings.embed) {
									found = true;
									break;
								} else {
									item = eval('new '+resourcesExtensionsLinkType[k][1]+'(this)');
									found = true;
									break;
								}
							}
					}
					if (!found && !this.icarus.settings.embed) var item = eval('new '+resourcesTypes[j]+'Class(this)');
					
					if (item == null) break;
					item.parse(data.items[i]);
					item.draw();
					break;
				}
		
		this.viewsCollection.showView(this.id);
		this.updateBreadCrumb();
		this.icarus.refreshSearchFilters();
		if (this.canEdit()) {
			this.icarus.enableButtons();
			this.icarus.enableEdit();
			//this.setContextMenu();
		} else {
			this.icarus.disableEdit();
			this.icarus.enableButtons('right');
		}
		this.viewsCollection.loading.hide();
		this.icarus.resize();
	},
	
	setContextMenu: function () {
		$.contextMenu('destroy', this.icarus.graphics+' .views-wrapper');
		$.contextMenu({
	        selector: this.icarus.graphics+' .views-wrapper',
	        callback: $.proxy(function(key, options) {
	        	switch(key) {
	        		case 'newfolder': this.viewsCollection.createFolder(); break;
	        	}
	        }, this),
	        items: {
	        	"newfolder": {name: ('New folder').localized('Resources'), icon: "newfolder" }
	        }
	    });
	},
	
	getFolderId: function () { return this.folder[this.folder.length-1].ResourcesFolder.id; },
	getParentFolderId: function () { return this.folder[this.folder.length-1].ResourcesFolder.parent_id; },
	
	setBreadCrumb: function (data) {
		this.breadcrumb = '<ul>';
		if (this.icarus.settings.project_slug == '') this.breadcrumb += '<li><a href="#folder://">Home</a></li>';
		//else this.breadcrumb += '<li>Home</li>';
		var breadcum_slug = '';
		for (var i=1;i<data.length;i++) {
			if (breadcum_slug != '') breadcum_slug += '/';
			breadcum_slug += data[i].ResourcesFolder.slug;
			this.breadcrumb += '<li><a href="#folder://'+breadcum_slug+'">'+data[i].ResourcesFolder.title+'</a></li>';
		}
		this.breadcrumb += '</ul>';
		this.updateBreadCrumb();
	},
	
	canEdit: function () {
		if (this.folder[this.folder.length-1].ResourcesFolder.can_edit) return true; else return false;
	}
	
});

/* ************************************************************************************************ *
 * > Specific view for search
 * ************************************************************************************************ */

var ViewSearchClass = ViewClass.extend({
	
	construct: function(viewsCollection, hash) {
		arguments.callee.$.construct.call(this, viewsCollection, hash);
		this.type = 'search';
	},
	
	show: function (direction) {
		if (direction == null) direction = 'right';
		$(this.graphics).show("slide", { direction: direction }, 'fast');
		this.updateBreadCrumb();
		this.icarus.enableButtons('right');
	},
	
	refreshCallback: function (data) {
		this.setBreadCrumb();
		this.updateBreadCrumb();
		
		if (data.grouped_items.length == 0) {
			$(this.graphics).append('<h2 style="margin: 10px 10px;">'+('There are no result').localized('Resources')+'</h2>');
		}
		
		for (var i=0;i<data.grouped_items.length;i++) {
			$(this.graphics).append('<h4 style="margin: 10px 10px;">'+this.getPath(data.grouped_items[i].path)+'</h4>');
			$(this.graphics).append('<ul>');
			//$(this.graphics).append('<div style="clear:both"></div>');
			for (var j=0;j<data.grouped_items[i].folders.length;j++) {
				var folder = new FolderClass(this);
				folder.parse(data.grouped_items[i].folders[j]);
			}
			for (var j=0;j<data.grouped_items[i].items.length;j++) {
				for (var k=0;k<resourcesTypes.length;k++)
					if ('object' === typeof eval('data.grouped_items[i].items[j].'+resourcesTypes[k])) {
						var item = null;
						var found = false;
						if (resourcesTypes[k] == 'ResourcesFile') {
							for(var l=0;l<resourcesExtensionsFileType.length;l++)
								if (data.grouped_items[i].items[j].ResourcesFile.extension == resourcesExtensionsFileType[l][0]) {
									if (this.icarus.settings.embed) {
										if (resourcesExtensionsFileType[l][1] == 'ResourcesFileImageClass') {
											item = eval('new '+resourcesExtensionsFileType[l][1]+'(this)');
										}
										found = true;
										break;
									} else {
										item = eval('new '+resourcesExtensionsFileType[l][1]+'(this)');
										found = true;
										break;
									}
								}
						} else if (resourcesTypes[k] == 'ResourcesLink') {
							for(var l=0;l<resourcesExtensionsLinkType.length;l++)
								if (data.grouped_items[i].items[j].ResourcesLink.link.match(resourcesExtensionsLinkType[l][0])) {
									if (this.icarus.settings.embed) {
										found = true;
										break;
									} else {
										item = eval('new '+resourcesExtensionsLinkType[l][1]+'(this)');
										found = true;
										break;
									}
								}
						}
						if (!found && !this.icarus.settings.embed) var item = eval('new '+resourcesTypes[k]+'Class(this)');
						
						if (item == null) break;
						item.parse(data.grouped_items[i].items[j]);
						item.draw();
						break;
					}
			}
		}
		
		this.viewsCollection.showView(this.id);
		this.icarus.refreshSearchFilters();
		this.viewsCollection.loading.hide();
		this.icarus.enableButtons('left');
		this.icarus.resize();
	},
	
	setBreadCrumb: function () {
		this.breadcrumb = '<ul>';
		this.breadcrumb += '<li><a href="#folder://">Home</a></li>';
		this.breadcrumb += '<li>'+('Search').localized('Resources')+': '+this.hash.uri+'</li>';
		this.breadcrumb += '</ul>';
		this.updateBreadCrumb();
	}
	
});

/* ************************************************************************************************ *
 * > Collection for handle multiple views
 * ************************************************************************************************ */

var ViewsCollectionClass = Class.extend({
	
	construct: function(icarus) {
		this.icarus = icarus;
		this.views = new Array(); // Array of views
		this.current_view = 0; // Iterator for the array views
		this.graphics = this.icarus.graphics + ' .views-wrapper';
		this.loading = new ViewLoadingClass(this);
		
		if (!this.icarus.settings.embed) $(window).bind('hashchange', $.proxy(function() { this.requestCurrentHash(); }, this));
		
		// Initial view
		var isProject = (this.icarus.settings.project_slug != '');
		var isEmbed = this.icarus.settings.embed;
		var isHash = (window.location.hash != '');
		var isCookie = ($.cookie('actual-folder-slug') != null);
		
		var hash = new HashClass(this.icarus, '', '');
		if (isHash) {
			hash.getBrowserHistory();
			this.getView(hash);
		} else {
			if (isProject) {
				hash.type = 'folder'; hash.uri = this.icarus.settings.project_slug;
				hash.setBrowserHistory();
			} else {
				if (isCookie) {
					hash.fromString($.cookie('actual-folder-slug'));
					if (isEmbed) {
						this.getView(hash);
					} else {
						hash.setBrowserHistory(); 
					} 
				} else {
					hash.type = 'folder'; hash.uri = '';
					if (isEmbed) {
						this.getView(hash);
					} else {
						hash.setBrowserHistory(); 
					} 
				}
			}
		}
	},
	
	refresh: function () {
		this.views[this.current_view].refresh();
	},
	
	refreshAll: function () {
		for (var i=0;i<this.views.length;i++) {
			if (i == this.current_view) this.refresh();
			else this.views[i].forceReload = true;
		}
	},
	
	getView: function (hash) {
		//this.icarus.loadingButton();
		for (var i=0;i<this.views.length;i++) {
			if (this.views[i].hash.toHashString() == hash.toHashString()) {
				if (this.views[i].forceReload) {
					this.views[i].refresh();
					this.views[i].forceReload = false;
				}
				this.showView(i);
				return;
			}
		}
		this.addView(hash);
	},
	
	addView: function (hash) {
		var view = null;
		if (hash.type == 'folder') view = new ViewFolderClass(this, hash);
		else if (hash.type == 'search') view = new ViewSearchClass(this, hash);
		this.views.push(view);
	},
	
	showView: function (i) {
		if (this.views.length > 1) {
			this.views[this.current_view].hide();
			if (this.views[this.current_view].hash.isDeeperThan(this.views[i].hash)) this.views[i].show('left');
			else this.views[i].show('right');
		} else {
			i = 0;
			//alert(this.views);
			//console.log(this.views[i]);
			this.views[i].show();
			//alert('hola');
		}
		
		this.current_view = i;
		this.icarus.resize();
	},
	
	requestHash: function (hash) { this.getView(hash); },
	requestCurrentHash: function () { var hash = new HashClass(this.icarus, '', ''); hash.getBrowserHistory(); this.getView(hash); },
	
	// Get the current folder id
	getCurrentFolderId: function () {
		var view = this.views[this.current_view];
		if (view.type == 'folder') return view.getFolderId();
		else return 0;
	},
	
	createFolder: function(){
		var folder = new FolderClass(this.views[this.current_view]);
		folder.id = 'temp'; folder.title = ('New folder').localized('Resources');
		folder.draw(); this.icarus.resize(); folder.rename();
	}
});

/* ************************************************************************************************ *
 * > Icarus class
 * ************************************************************************************************ */

var IcarusClass = Class.extend({
	
	construct: function(id) {
		this.id = id;
		this.graphics = '#'+id;
		this.settings = $(this.graphics).data('settings');
		//this.filters = [];
		this.uploadFilters = [];
		
		this.viewsCollection = new ViewsCollectionClass(this);
		
		this.infobox = new InfoboxClass(this);
		this.initToolbar();
		$("#statusbar", this.graphics).jBreadCrumb();
		
		// Resize
		$(window).resize($.proxy(this.resize, this));
		this.resize();
	},
	
	initToolbar: function () {
		// Menu
		if (!this.settings.embed) {
			var out = '<ul>';
			for (var i=0;i<icarusMenu.length;i++) {
				out += '<li><a href="'+icarusMenu[i][1]+'">'+(icarusMenu[i][0]).localized("Resources")+'</a></li>';
			}
			out += '</ul>';
			$('#icarus-menu').html(out);
			$("#icarus-menu-button").fgmenu({ content: $("#icarus-menu-button").next().html(), backLink: false });
		}
		
		// Upload
		this.initUpload();
		
		// New folder
		$("#new-folder", this.graphics).click($.proxy(function () { this.viewsCollection.createFolder() }, this));
		
		// Reload
		$("#reload", this.graphics).click($.proxy(function () { this.viewsCollection.refresh() }, this));
		
		// Filter
		$("#filter", this.graphics).click(function(){
			$('<input type="checkbox" id="check1" /><label for="check1">B</label><input type="checkbox" id="check2" /><label for="check2">I</label><input type="checkbox" id="check3" /><label for="check3">U</label>').dialog({
				title: ('Filter').localized("Resources"),
				resizable: false, modal: true,
				buttons: [{
	    			text: ("OK").localized("Resources"),
	    			click: $.proxy(function () {
						
					}, this)
	    		}, {
	    			text: ("Cancel").localized("Resources"),
	    			click: function() { $(this).dialog("close"); }
	    		}]
			})
			}
		);
		
		// Slider
		$('#slider', this.graphics).slider({
			value: 50, min: 50, max: 125, step: 25,
			change: $.proxy(function (event, ui) {
				$('.grid li', this.graphics).animate({width: ui.value + 60}, 'fast');
				$('.grid li .icon img', this.graphics).animate({width: ui.value, height: ui.value}, 'fast');
				$('.grid li .icon', this.graphics).animate({width: ui.value, height: ui.value}, 'fast', $.proxy(function () {
					this.resize();
				}, this));
				this.resize();
			}, this),
			slide: $.proxy(function (event, ui) {
				$('.grid li', this.graphics).animate({width: ui.value + 60}, 'fast');
				$('.grid li .icon img', this.graphics).animate({width: ui.value, height: ui.value}, 'fast');
				$('.grid li .icon', this.graphics).animate({width: ui.value, height: ui.value}, 'fast', $.proxy(function () {
					this.resize();
				}, this));
				$.cookie('icarus_grid_size', ui.value);
				this.resize();
			}, this)
		});
		if ($.cookie('icarus_grid_size')) $('#slider', this.graphics).slider('value', $.cookie('icarus_grid_size'));
		
		// Search
		$('#search', this.graphics).qtip({
			content: { text: ('Write your search to filter the result.<br />Press enter if you are looking for a deeper search.').localized('Resources') },
			position: {	my: 'top right', at: 'bottom middle' }
		}).keyup($.proxy(function(event) {
			if (event.which == 13) {
				event.preventDefault();
				var hash = new HashClass(this, 'search', $('#search', this.graphics).val());
				if (!this.settings.embed) hash.setBrowserHistory(); else this.viewsCollection.requestHash(hash);
			}
		}, this));
	},
	
	initUpload: function () {
		var uploadButton = $('#upload-button', this.graphics);
		var uploadDialog = $('#'+this.id+'-icarus-upload-dialog', this.graphics);
		
		uploadDialog.dialog({
			modal: true, autoOpen: false, width: $(window).width() * 0.6, height: $(window).height() * 0.5,
			resizable: false, title: ('Upload').localized('Resources'),
			open: $.proxy(function(event, ui) {
				icarus_files_uploaded = false;
				var content = '<form id="icarus-upload-form" method="post" width="100%" height="100%">';
				content += '<div id="icarus-uploader" width="100%" height="100%">';
				content += '<p>You browser doesnt have HTML5, Flash, Silverlight, Gears or BrowserPlus support.</p>';
				content += '</div></form>';
				uploadDialog.html(content);
				
				var uploader = $('#icarus-uploader', uploadDialog);
				
				var pluploadFilters = new Array();
				for (var i=0;i<icarusUploadExtensions.length;i++) {
					pluploadFilters.push({ title: icarusUploadExtensions[i][0], extensions : icarusUploadExtensions[i][1] })
				}
				
				icarus_files_uploaded = false;
				uploader.plupload({
					runtimes : "html5,flash,silverlight,gears,browserplus",
					url : base_url + 'resources/resources_files/upload',
					max_file_size : icarus_upload_max_size + "b",
					chunk_size : icarus_upload_max_size + "b",
					dragdrop : true,
					filters : pluploadFilters,
					flash_swf_url : base_url + "resources/js/icarus/plupload/plupload.flash.swf",
					silverlight_xap_url : base_url + "resources/js/icarus/plupload/plupload.silverlight.xap",
					container: this.id+'-icarus-upload-dialog',
					multipart_params: { folder_id : this.viewsCollection.getCurrentFolderId() },
					
					init : {
						FileUploaded: function(up, file, info) {
							var allupload = true;
							for (var i=0;i<up.files.length;i++) if (up.files[i].status != 5) allupload = false;
							icarus_files_uploaded = true;
							if (allupload){
								/*$('<p><span class="ui-icon ui-icon-alert" style="z-index:9999;float:left; margin:0 7px 20px 0;"></span>'+("The files has been uploaded. Click OK to continue.").localized("Resources")+'</p>').dialog({
								title: ('File/s uploaded').localized("Resources"),
								resizable: false, modal: true,
								buttons: [{
					    			text: ("OK").localized("Resources"),
					    			click: function() {
					    				$(".ui-dialog-content").dialog("close");
					    			}
					    		}]
								})*/
								$(".ui-dialog-content").dialog("close");
							}
						},
						
						Refresh: function(up) {
							var height = uploadDialog.dialog("option", "height"); height -= 55;
						    $('#'+up.settings.container + ' form').css('height', height+10);
						    $('#'+up.settings.container + ' > div').children().css('font-size','20px');// default is 999px, which breaks everything in firefox
						    $('#'+up.settings.container + ' > #icarus_uploader').css('height', height);
						    $('#'+up.settings.container + ' .plupload_container').css('height', height+8);
						    $('#'+up.settings.container + ' .plupload_content').css('height', height-57);
						    $('#'+up.settings.container + ' .plupload_scroll').css('height', height-57-64);
						    $('#'+up.settings.container + ' .plupload_filelist').css('height','30px');
		            	}
					}
				});
				uploader.plupload("getUploader").refresh();
			}, this),
			close: $.proxy(function(event, ui) { if (icarus_files_uploaded) this.viewsCollection.refresh(); $(this).html(''); }, this)
		});
		uploadButton.click(function() {
			uploadDialog.dialog("open");
		});
	},
	
	resize: function () {
		$(this.graphics).width(this.settings.width);
		$(this.graphics).height(this.settings.height);
		
		if (this.viewsCollection != undefined) {
			$(this.viewsCollection.graphics).height($(this.graphics).height() - 100);
			this.viewsCollection.loading.update();
		}
		if (this.infobox != undefined) this.infobox.resize();
		
		$(this.graphics+' .grid li').css('width', $('#slider').slider('value') + 60);
		$(this.graphics+' .grid li .icon img')
			.css('width', $('#slider').slider('value'))
			.css('height', $('#slider').slider('value'));
		$(this.graphics+' .grid li .icon')
			.css('width', $('#slider').slider('value'))
			.css('height', $('#slider').slider('value'));
		
		$('.statusbar', this.graphics).width($(this.graphics).width() - 10);
		
		if (this.viewsCollection != undefined &&
			this.viewsCollection.views != undefined &&
			this.viewsCollection.views[this.viewsCollection.current_view] != undefined &&
			this.viewsCollection.views[this.viewsCollection.current_view].hash != undefined &&
			this.viewsCollection.views[this.viewsCollection.current_view].hash.type == 'folder') {
			
			var g = this.viewsCollection.views[this.viewsCollection.current_view].graphics;
			$('ul li', g).css('float', 'left');
			var eh = $('ul li', g).outerHeight() + 30;
			var ew = $('ul li', g).width() + 30;
			var num = $('ul li', g).length;
			var iw = $(this.graphics).width();
			var rows = Math.ceil(num * ew / iw);
			var th = rows * eh;
			var ah = $(this.viewsCollection.graphics).height();
			if (ah > th) th = ah;
			$('ul', g).height(th);
			
			//console.log('eh:' + eh + ', ew: '+ ew + ', num: ' + num + ', iw: ' + iw + ', rows: ' + rows + ', th: ' + th + ', ah: ' + ah);
		}
	},
	
	enableButtons: function () {
		$("#slider", this.graphics).button("option", "disabled", false);
		$("#reload", this.graphics).button("option", "disabled", false);
		$("#search", this.graphics).removeAttr('disabled').removeClass('disable');
	},
	
	enableEdit: function () {
		$("#block-message", this.graphics).remove();
		$("#loading-message", this.graphics).remove();
		$("#icarus-menu-button", this.graphics).show();
		$("#new-folder", this.graphics).show();
		$("#upload-button", this.graphics).show();
		//$("#icarus-menu-button", this.graphics).removeClass('ui-button-disabled').removeClass('ui-state-disabled');
		//$("#icarus-menu-button", this.graphics).fgmenu({ content: $("#icarus-menu-button").next().html(), backLink: false });
		//$("#new-folder", this.graphics).button("option", "disabled", false);
		//$("#upload-button", this.graphics).button("option", "disabled", false);
	},
	
	disableEdit: function () {
		$("#loading-message", this.graphics).remove();
		if ($("#block-message", this.graphics).length == 0)
			$(".toolbar", this.graphics).prepend('<div id="block-message" style="float:left; padding: 10px;">'+('Can\'t edit this folder').localized("Resources")+'</div>');
	},
	
	loadingButton: function () {
		$("#block-message", this.graphics).remove();
		if ($("#loading-message", this.graphics).length == 0)
			$(".toolbar", this.graphics).prepend('<div id="loading-message" style="float:left; padding: 10px;">'+('Loading...').localized("Resources")+'</div>');
		$("#icarus-menu-button", this.graphics).hide();
		$("#new-folder", this.graphics).hide();
		$("#upload-button", this.graphics).hide();
		$("#slider", this.graphics).button("option", "disabled", true);
		$("#reload", this.graphics).button("option", "disabled", true);
		$("#search", this.graphics).attr('disabled', 'disabled').addClass('disable');
	},
	
	// Reset Search filter for the current view
	refreshSearchFilters: function () {
		this.listFilter($("#search", this.graphics), $("ul", this.viewsCollection.graphics));
	},
	
	// Utility for the search filter
	listFilter: function (input, list) {
		$(input).change(function () {
			var filter = $(this).val();
			if(filter) {
				$(list).find("div.title:not(:Contains('" + filter + "'))").add("div.tags:not(:Contains('" + filter + "'))").parent().hide();
				$(list).find("div.title:Contains('" + filter + "')").add("div.tags:Contains('" + filter + "')").parent().show();
			} else $(list).find("li").show();
			return false;
		}).keyup(function () { $(this).change(); });
	}
	
});

/* ************************************************************************************************ *
 * > JQuery Icarus Plugin
 * ************************************************************************************************ */

(function( $ ){
	
	var icarus;
	var methods = {
		init : function(options) {
			var settings = $.extend({
				'embed'        : false,
				'project_slug' : '',
				'filters' 	   : [],
				'width'        : '100%',
				'height'       : '100%'
		    }, options);
		    
		    $(this).data('settings', settings); // Add settings to icarus
		    $(this).addClass('icarus'); // Add main class
		    
		    // Toolbar
		    
		    $(this).append('<div class="toolbar ui-widget-header ui-corner-all ui-helper-clearfix"></div>');
		    $('.toolbar', this).append('<input id="search" type="text" placeholder="'+('Search...').localized("Resources")+'" name="search" value="" />');
			$('.toolbar', this).append('<button id="reload">'+('Reload').localized("Resources")+'</button>');
			//$('.toolbar', this).append('<button id="filter">'+('Filter').localized("Resources")+'</button>');			
			$('.toolbar', this).append('<div id="slider"></div>');
		    if (!settings.embed) {
		    	$('.toolbar', this).append('<a id="icarus-menu-button" href="#" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all"><span class="ui-icon ui-icon-triangle-1-s"></span>'+('Add...').localized("Resources")+'</a>');
		    	$('.toolbar', this).append('<div id="icarus-menu" class="hidden">');
		    }
			$('.toolbar', this).append('<button id="upload-button">'+('Upload').localized("Resources")+'</button>');
			$('.toolbar', this).append('<button id="new-folder">'+('New folder').localized("Resources")+'</button>');
		    
		    // Separator
		    $(this).append('<table class="top-bar"><tr><td class="left"></td><td class="middle"></td><td class="right"></td></tr></table>');
		    
		    // Views Wrapper
		    $(this).append('<div class="views-wrapper">');
		    
		    // Status Bar
		    $(this).append('<div class="statusbar breadCrumb module">');
		    
		    // Separator
		    $(this).append('<table class="bottom-bar"><tr><td class="left"></td><td class="middle"></td><td class="right"></td></tr></table>');
		    
		    // Dialogs
		    $(this).append('<div id="'+this.attr('id')+'-icarus-upload-dialog"></div>');
		    
		    $("#reload", this).button({ icons: { primary: "ui-icon-arrowrefresh-1-e" }, text: false });
		    $("#filter", this).button({ icons: { primary: "ui-icon-gear" }, text: false });
			$("#new-folder", this).button({ icons: { primary: "ui-icon-folder-open" }, text: true });
			$("#upload-button", this).button({ icons: { primary: "ui-icon-circle-arrow-n" }, text: true });
		    
		    icarus = new IcarusClass(this.attr('id'));
		},
		
		resize : function(w, h) {
		    icarus.settings.width = w;
		    icarus.settings.height = h;
		    icarus.resize();
		}
	};
	
	$.fn.icarus = function(method) {
		if ( methods[method] ) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on jQuery.icarus');
		}
	};
})( jQuery );

/* ************************************************************************************************ *
 * > Third-party Libraries & Tools
 * ************************************************************************************************ */

/**
 * Patch for the search filter
 */
/*jQuery.expr[':'].Contains = function(a,i,m) {
	return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
}*/

/***
 * Pacth for dialog-fix ckeditor problem [ by ticket #4727 ]
 * 	http://dev.jqueryui.com/ticket/4727
 */

/*$.extend($.ui.dialog.overlay, { create: function(dialog){
	if (this.instances.length === 0) {
		// prevent use of anchors and inputs
		// we use a setTimeout in case the overlay is created from an
		// event that we're going to be cancelling (see #2804)
		setTimeout(function() {
			// handle $(el).dialog().dialog('close') (see #4065)
			if ($.ui.dialog.overlay.instances.length) {
				$(document).bind($.ui.dialog.overlay.events, function(event) {
					var parentDialog = $(event.target).parents('.ui-dialog');
					if (parentDialog.length > 0) {
						var parentDialogZIndex = parentDialog.css('zIndex') || 0;
						return parentDialogZIndex > $.ui.dialog.overlay.maxZ;
					}
					
					var aboveOverlay = false;
					$(event.target).parents().each(function() {
						var currentZ = $(this).css('zIndex') || 0;
						if (currentZ > $.ui.dialog.overlay.maxZ) {
							aboveOverlay = true;
							return;
						}
					});
					
					return aboveOverlay;
				});
			}
		}, 1);
		
		// allow closing by pressing the escape key
		$(document).bind('keydown.dialog-overlay', function(event) {
			(dialog.options.closeOnEscape && event.keyCode
					&& event.keyCode == $.ui.keyCode.ESCAPE && dialog.close(event));
		});
			
		// handle window resize
		$(window).bind('resize.dialog-overlay', $.ui.dialog.overlay.resize);
	}
	
	var $el = $('<div></div>').appendTo(document.body)
		.addClass('ui-widget-overlay').css({
		width: this.width(),
		height: this.height()
	});
	
	(dialog.options.stackfix && $.fn.stackfix && $el.stackfix());
	
	this.instances.push($el);
	return $el;
}});*/

/*
 * jQuery hashchange event - v1.3 - 7/21/2010
 * http://benalman.com/projects/jquery-hashchange-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($,e,b){var c="hashchange",h=document,f,g=$.event.special,i=h.documentMode,d="on"+c in e&&(i===b||i>7);function a(j){j=j||location.href;return"#"+j.replace(/^[^#]*#?(.*)$/,"$1")}$.fn[c]=function(j){return j?this.bind(c,j):this.trigger(c)};$.fn[c].delay=50;g[c]=$.extend(g[c],{setup:function(){if(d){return false}$(f.start)},teardown:function(){if(d){return false}$(f.stop)}});f=(function(){var j={},p,m=a(),k=function(q){return q},l=k,o=k;j.start=function(){p||n()};j.stop=function(){p&&clearTimeout(p);p=b};function n(){var r=a(),q=o(m);if(r!==m){l(m=r,q);$(e).trigger(c)}else{if(q!==m){location.href=location.href.replace(/#.*/,"")+q}}p=setTimeout(n,$.fn[c].delay)}$.browser.msie&&!d&&(function(){var q,r;j.start=function(){if(!q){r=$.fn[c].src;r=r&&r+a();q=$('<iframe tabindex="-1" title="empty"/>').hide().one("load",function(){r||l(a());n()}).attr("src",r||"javascript:0").insertAfter("body")[0].contentWindow;h.onpropertychange=function(){try{if(event.propertyName==="title"){q.document.title=h.title}}catch(s){}}}};j.stop=k;o=function(){return a(q.location.href)};l=function(v,s){var u=q.document,t=$.fn[c].domain;if(v!==s){u.title=h.title;u.open();t&&u.write('<script>document.domain="'+t+'"<\/script>');u.close();q.location.hash=v}}})();return j})()})(jQuery,this);
