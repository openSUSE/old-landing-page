jQuery.fn.selectBox = function(o) {
	return this.each(function() {
		var oThis = this;
		var oSelected = null;
		var state = 'closed';
		var iSelectedIndex = 0;

		var oSelectOffset = jQuery(this).offset('HTML');

		// wrap div around the select element

		jQuery(this).wrap('<div></div>').css({top:'-1000px',left:'-1000px',position: 'absolute'})//hide();
		var oContainer = jQuery(this).parent().addClass(o.css);

		// append html inside the container
		oContainer.append('<div><p></p></div><ul></ul>');
		// assign toggle action

		jQuery("div",oContainer).css({width: oSelectOffset.width+ 'px'}).toggle(function() {

			jQuery("ul",oContainer).slideDown('fast');
			jQuery("li",oContainer).removeClass('active');
			if(oSelected == null) {
				jQuery("li:eq(0)",oContainer).addClass('active');
				oSelected = jQuery("li:eq(0)",oContainer);
			} else {
				oSelected.addClass('active');
			}
			state = 'opened';
			jQuery(oThis)[0].focus();
		},function() {
			jQuery("ul",oContainer).slideUp('fast');
			state = 'closed';
			jQuery(oThis)[0].blur();
		}).mouseover(function() {
			jQuery(oThis)[0].focus();
		}).mouseout(function() {
			jQuery(oThis)[0].blur();
		});

		// assign click outside dropdown.
		jQuery().click(function() {
			if(state == 'opened') {
				jQuery("div",oContainer).trigger("click");
			}
		});

		// get values from the option elements and set them in the ul list.
		jQuery(this).keyup(function() {
			setValue();
		});

		jQuery('option',this).each(function(i) {
			var o = this;
			jQuery(this).click(function() {
				this.selected = true;
			});
			/*
			if(i == 0) {
				jQuery("div p",oContainer).html(jQuery(o).text())
			}
				*/
			jQuery("ul",oContainer).append('<li class="geeko">' +  jQuery(this).text() + '</li>');
			/*
			if(jQuery(this).is(':selected')) {
				jQuery("li:eq(" + i + ")",oContainer).addClass('active');
				oSelected = jQuery("li:eq(" + i + ")",oContainer);
			}
			*/
			jQuery("li:eq(" + i + ")",oContainer).click(function() {
				jQuery(o).click();
				jQuery("div",oContainer).trigger("click");

				//jQuery("div p",oContainer).html(jQuery(o).text())
				$("#search form input.textfield").attr('value', jQuery(o).text());

				var newClasses = $(this).attr('class');
				$("#search form div.select p").attr('class', newClasses);

				oSelected = $(this)
			}).mouseover(function() {
				jQuery(this).addClass('active');
				jQuery(oThis)[0].focus();
			}).mouseout(function() {
				jQuery(this).removeClass('active');
				jQuery(oThis)[0].blur();
			});
		});




		// set ul list position
		jQuery("ul",oContainer).hide();

		var positionListElement = function() {
			var oOffset = jQuery("div",oContainer).offset('HTML');
			// set ul list position
			jQuery("ul",oContainer).css({
				left: oOffset.left  + 'px',
				top: oOffset.top + parseInt(oOffset.height) + 'px',
				position: 'absolute',
				width: oOffset.width + 'px'
			});
		};
		var setValue = function() {
			var val = jQuery(":selected",oThis).text();
			jQuery("div p",oContainer).html(val);
			oSelected = jQuery("li:eq(" + jQuery(oThis)[0].selectedIndex + ")",oContainer);
			jQuery("li",oContainer).removeClass('active');
			oSelected.addClass('active');
		};

		setValue();
		positionListElement();

		jQuery(window).resize(positionListElement);
	});
};