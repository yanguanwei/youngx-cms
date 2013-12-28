/**
 * jQuery tabletree v1.0
 *
 * Copyright 2013, E-Mail: yanguanwei@qq.com, QQ: 176013294
 * Date: 2013-4-5
 */
;(function($) {
	var TableTreeRow = function( parent, options, data, child, next ) {
		var self = this,
			loading = false;
		
		this.parent = parent;
		this.options = options;
		this.layer = -1;
		this.loaded = false;
		this.elements = $([]);
		this.children = {};
		
		var init = function( data, child, next ) {
			this.hidden = false;
			this.collapsed = true;
			this.layer = parent.layer + 1;
			this.id = this.options.idPrefix + (TableTreeRow.id++);
			
			this.tr = $('<tr />').hide();
			
			var $indenter = $('<span class="tabletree-indenter" />');
			
			this.tr
				.addClass( next ? this.options.nextCssClass : this.options.endCssClass )
				.attr('id', this.id);
			
			this.options.format.call(this, data, child, next);
			
			$indenter.css('margin-left', (this.layer * this.options.indenter) + 'px');
			
			if ( child ) {
				this.tr
					.addClass( this.options.childCssClass )
					.addClass( this.options.collapsedCssClass );
					
				$indenter.append( 
					$('<a href="javascript:;" title="Expand">&nbsp;</a>')
						.click(function() {
							if ( self.collapsed ) {
									self.expand();
							} else {
								self.collapse();
							}
						})
					);
			}
			
			this.tr.find('td').eq(0).prepend($indenter);
			
			this.expand = function() {
				var expand = function() {
					if ( !this.hidden )
						for ( var id in this.children )
							this.children[id].show();
					
					this.tr
						.removeClass(this.options.collapsedCssClass)
						.addClass(this.options.expandedCssClass);
					
					this.collapsed = false;
				};
				
				if ( this.loaded ) {
					expand.call( this );
				} else {
					this.load( expand );
				}
			};
			
			this.collapse = function() {
				for ( var id in this.children )
					this.children[id].hide();
				
				this.tr
					.removeClass(this.options.expandedCssClass)
					.addClass(this.options.collapsedCssClass);
				
				this.collapsed = true;
			};
			
			this.show = function() {
				if ( !this.collapsed ) {
					for ( var id in this.children ) {
						this.children[id].show();
					}	
				}
				this.tr.show();
				this.hidden = false;
			};
			
			this.hide = function() {
				if ( !this.collapsed ) {
					for ( var id in this.children ) {
						this.children[id].hide();
					}
				}
				this.tr.hide();
				this.hidden = true;
			};
		};
		
		data && init.call( this, data, child, next );

        this.insert = function(json) {

            for ( var i=0, n=json.items.length; i<n; i++ ) {
                var ttr = new TableTreeRow( this, this.options, json.items[i].data, json.items[i].child, i < (n-1) );
                this.elements = this.elements.add( ttr.tr );
                this.children[ttr.id] = ttr;
            }

            if ( this.layer==-1 ) {
                this.parent.append( this.elements.show() );
            } else {
                this.elements.insertAfter( this.tr );
            }
        };

		this.load = function( success ) {
			if ( this.loaded || loading )
				return false;
			
			loading = true;
			
			if ( self.layer>-1 )
				this.tr.addClass( this.options.loadingCssClass );
			
			$.ajax($.extend({}, this.options.ajaxOptions, {
				url: this.options.url.call(this, data, child, next),
				dataType: 'json',
                type: 'post',
                data: this.options.data.call(this, data, child, next) || {},
				success: function(json) {
                    self.insert(json);
					success && success.call( self );
					self.loaded = true;
				},
				complete: function() {
					if ( self.layer>-1 )
						self.tr.removeClass( self.options.loadingCssClass );
					loading = false;
				}
			}));
		}
	};
	TableTreeRow.id = 0;
	
	$.fn.tabletree = function( options, json ) {
		options = $.extend({}, {
			tableCssClass: 'tabletree',
			childCssClass: 'tabletree-child',
			nextCssClass: 'tabletree-next',
			endCssClass: 'tabletree-end',
			expandedCssClass: 'tabletree-expanded',
			collapsedCssClass: 'tabletree-collapsed',
			loadingCssClass: 'tabletree-loading',
			idPrefix: 'tabletree-id-',
			format: function( data, child, next ) {},
			url: function( data, child, next ) {},
            data: function( data, child, next ) {},
			ajaxOptions: {},
			indenter: 19
		}, options || {});
		
		$(this).addClass( options.tableCssClass );
		
		$(this).each(function() {
            var ttr = new TableTreeRow( $(this), options );
            if (json) {
                ttr.insert(json);
            } else {
                ttr.load();
            }
		});
	};
})(jQuery);