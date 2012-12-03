function getBaseURL() {
  return $('#baseURL').val();
}

Message = {
  
  //info on the simplemodal dialog here:
  //http://www.ericmmartin.com/projects/simplemodal/
  'popup' : function(content, extraOptions) {
    extraOptions = extraOptions || {};
    
    if (content !== null) {
      $('#popupModal').html(content);
    }
    $('#popupModal').modal($.extend(true, {
      overlayClose:true,
      //fancy slide down opening. Takes a second longer to load
      onOpen: function (dialog) {
        dialog.overlay.fadeIn(100, function () {
          dialog.container.slideDown(200, function () {
            dialog.data.fadeIn(200);
          });
        });
      },
      overlayCss: {
        backgroundColor:"#666",
        borderColor:"#666"
      },
      //added the 3 lines below instead of the commented out one since the modal was hiding behind the top banner.
      //also because the modal was hidden when the browser was too small
      autoPosition: true,
      autoResize: true,
      zIndex: 2000,
      //position: [110,],
      onClose: function(dialog) {
        $.modal.close();
        $('#popupModal').html("");
        $('#popupModal').removeClass();
      }
    }, extraOptions));
  },
  
  'resize' : function() {
    $(window).trigger('resize.simplemodal');
  },

	'popupClose' : function() {
	  $.modal.close();
	},
		
  'confirm' : function(message, callback) {
  
    var messageDiv = UIHelper.putInDiv(message);
  
    var confirmButtons = "<div class='buttons'>" +
    "<button class = 'no'>No</button><button class = 'yes'>Yes</button>" +
    "</div>" +
    "<style>.yes, .no {width:75px; margin: 5px; float: right;} .buttons {height:35px; width:100%;}</style>";

    $.template( "confirmButtons", confirmButtons );
    $.tmpl( "confirmButtons", {}).appendTo($(messageDiv));

    $.sticky($(messageDiv).html(), {autoclose : false, position: "top-center"});

    $('.yes').click(function () {
      // call the callback
      if ($.isFunction(callback)) {
        callback.apply();
        $('.close').trigger('click');
      }
    });
    $('.no').click(function () {
      $('.close').trigger('click');
    });
  },
  
  'alert' : function (message, extraOptions) {
    var alertButton="<div class='buttons'><button class='yes'>Ok</button></div>" +
    "<style>.yes {width:75px; margin: 5px; float: right;} .buttons {height:35px; width:100%;}</style>";
    $.template( "alertButton", alertButton );
  
    $.tmpl( "alertButton", {}).appendTo(message);
    
    
    var alertOptions = $.extend({overlayClose:false,
      onShow: function (dialog) {
          var modal = this;

          // if the user clicks "yes"
          $('.yes').click(function () {
            // close the dialog
            modal.close(); // or $.modal.close();
          });
      }
    }, extraOptions);
    
    Message.popup(message, alertOptions);
  },
  'flashMessageTimeout' : 0,
  
  'flash' : function(message, status, listItems, seconds, makeSticky) {
    
    if (status == true) {
      seconds = seconds || 5000;
    }
    else if (status == false) {
      seconds = 30000;
    }
    if (makeSticky == true) {
      seconds = false;
    }
    
    listItems = listItems || {};
    
    var flashDiv = UIHelper.createMessagesList(listItems, message);
    var statusClass;

    if(status === true) { statusClass = 'st-success'; }
    else if(status === false) { statusClass = 'st-error'; }
    else { statusClass = 'st-info'; }

    $.sticky($(flashDiv).html(), {autoclose: seconds, position: 'top-right', type: statusClass});
  },
  
  'alertResult' : function(message, status, listItems) {
    listItems = listItems || {};
    
    var flashDiv = UIHelper.createMessagesList(listItems, message);
    $(flashDiv).attr('style', 'min-width: 400px;');
    
    var extraOptions = {containerCss : Message.cssPopupOptionsByStatus(status)};
    if (status === true) {
      $.extend(extraOptions, {overlayClose:true});
    }
    Message.alert(flashDiv, extraOptions);
  },
  
  'inline' : function(message, status, listItems) {
    listItems = listItems || {};
    
    var inlineDiv = UIHelper.createMessagesList(listItems, message);
    
    $('.inline-message').html(inlineDiv);
    
    Message.setClassByStatus(status, $('.inline-message'));
    
    $('.inline-message').fadeIn('fast');
  },
  
  'clearInline' : function() {
    var inlines = $('.inline-message');
    inlines.html('');
    inlines.removeClass();
    inlines.addClass('inline-message');
  },
  
  'cssPopupOptionsByStatus' : function(status) {

    var cssOptions = null;
    if (status === true) {
      cssOptions = {border: "3px solid #84BB44",
        background: "#F3FFEB",
        color: "#5B5943"
      };
    }
    else if (status === false) {
      cssOptions = {border: "3px solid #cd0a0a",
        background: "#fef1ec url(images/ui-bg_inset-soft_95_fef1ec_1x100.png) 50% bottom repeat-x",
        color: "#cd0a0a"
      };
    }
    else {
      cssOptions = {border: "3px solid #5B5943",
        background: "#FFFDDD",
        color: "#5B5943"
      };
    }
    return cssOptions;
  },
  
  'setClassByStatus' : function(status, selector) {
    if (status === true) {
      $(selector).addClass('ui-state-success');
    }
    else if (status === false){
      $(selector).addClass('ui-state-error');
    }
    else {
      $(selector).addClass('ui-state-warning');
    }
  },
  
  'setInputError' : function(selector) {
    $(selector).addClass('input-error');
  },
  
  'clearInputError' : function(selector) {
    $(selector).removeClass('input-error');
  },
  
  'clearAllInputErrors' : function(selector) {
    $('.input-error').removeClass('input-error');
  },
  'clearStickys' : function(status) {
		  if (status == true) {
		    $('.sticky').filter('.st-success').find('.st-close').trigger('click');
		  }
		  else if (status == false) {
		    $('.sticky').filter('.st-error').find('.st-close').trigger('click');
		  }
		  else {
		    $('.st-close', '.sticky').trigger('click');
		  }
		}
};

UIHelper = {
  
  'largeSpinner' : function(options) {
    var opts = {
      lines: 13, // The number of lines to draw
      length: 9, // The length of each line
      width: 4, // The line thickness
      radius: 10, // The radius of the inner circle
      corners: 1, // Corner roundness (0..1)
      rotate: 0, // The rotation offset
      color: '#000', // #rgb or #rrggbb
      speed: 1.5, // Rounds per second
      trail: 60, // Afterglow percentage
      shadow: false, // Whether to render a shadow
      hwaccel: false, // Whether to use hardware acceleration
      className: 'spinner', // The CSS class to assign to the spinner
      zIndex: 2e9, // The z-index (defaults to 2000000000)
      top: 'auto', // Top position relative to parent in px
      left: 'auto' // Left position relative to parent in px
    };
    $.extend(opts, options);
    return new Spinner(opts).spin();
  },
  'smallSpinner' : function(options) {
    var opts = {
      lines: 9, // The number of lines to draw
      length: 4, // The length of each line
      width: 2, // The line thickness
      radius: 4, // The radius of the inner circle
      corners: 1, // Corner roundness (0..1)
      rotate: 0, // The rotation offset
      color: '#000', // #rgb or #rrggbb
      speed: 1.5, // Rounds per second
      trail: 60, // Afterglow percentage
      shadow: false, // Whether to render a shadow
      hwaccel: false, // Whether to use hardware acceleration
      className: 'spinner', // The CSS class to assign to the spinner
      zIndex: 2e9, // The z-index (defaults to 2000000000)
      top: 'auto', // Top position relative to parent in px
      left: 'auto' // Left position relative to parent in px
    };
    $.extend(opts, options);
    return new Spinner(opts).spin();
  },
  
  'dropdown' : function(id, options, selected, htmlAttributes, optionHtmlAttributes) {
    htmlAttributes = (typeof htmlAttributes == "undefined") ? {} : htmlAttributes;
    optionHtmlAttributes = (typeof optionHtmlAttributes == "undefined") ? {} : optionHtmlAttributes;
    
    var dropdownHtml = $('<select></select>');
    
    $.each(htmlAttributes, function(attribute, value) {
      $(dropdownHtml).attr(attribute, value);
    });
    
    if (id !== '') {
      $(dropdownHtml).attr('id', id);
    }
    
    for (var opt in options) {
      var optionHtml = $('<option></option>');
      var optionLabel = options[opt];
      var optionValue = opt;
      
      $(optionHtml).attr('value', optionValue);
      $(optionHtml).text(optionLabel);
      
      $.each(optionHtmlAttributes, function(attribute, value) {
        $(optionHtml).attr(attribute, value);
      });
      
      if (optionValue == selected)
        $(optionHtml).attr('selected', 'selected');
        
      dropdownHtml.append(optionHtml);
    }
    
    return dropdownHtml;
  },
  
  'addOption' : function(selectbox, value, text, selected) {
    var optn = document.createElement("option");
    optn.text = text;
    optn.value = value;
    if (selected) {
      optn.selected = true;
    }
    $(optn).appendTo(selectbox);
  },
  
  'showErrorMessage' : function(content) {
    alert(content);
  },
  
  'initLeftNavigation' : function() {
    $('#leftNav a').click(function(e) {
      e.preventDefault();
      var href = $(this).attr('href');
      $.post(href, function(response) {
        $('#contentDisplay').html(response);
      }, 'html');
    });
  },

  'highlightActiveSubmenu' : function() {
    var currTab = $('ul#menu li a[href="'+window.location.pathname+'"]');
    $("#menu li").removeClass("active");

    if(currTab.length) {
      currTab.addClass("active");
      currTab.parent().parent().css("display", "block");
      currTab.parent().find('ul').css("display", "block");
    }
  },
  
  'putInDiv' : function(content) {
    var div = document.createElement('div');
    $(div).html(content);
    return div;
  },
  
  'createUL' : function(listItems) {
    var newUL = document.createElement("ul");
    $.each(listItems, function(label, value) {
      var newLI = document.createElement("li");

      $(newLI).html(value);
      $(newLI).attr('style', 'margin-left: 30px;');
      $(newLI).appendTo(newUL);
      
    });
    
    return newUL;
  },
  
  'createMessagesList' : function(listItems, message) {
    var flashDiv = UIHelper.putInDiv(message);
    var listUL = UIHelper.createUL(listItems);
    
    $(listUL).appendTo(flashDiv);
    
    return flashDiv;
  },
  
  'accordionIsClosing' : function(event, ui) {
    if (ui.newContent.length) {
      return false;
    }
    return true;
  },
  
  'exchangeDivs' : function(hideDiv, showDiv, duration, dir, effect) {
    effect = effect || 'slide';
    dir = dir || 'left';
    duration = duration || 500;
  
    $(showDiv).hide();
  
    var parent = $(hideDiv).parent();
    $(parent).append(showDiv);
  
    $(hideDiv).hide(effect, {direction:dir}, duration, function() {
      if (dir == 'left') {
        dir = 'right';
      }
      else if (dir == 'right') {
        dir = 'left';
      }
      $(showDiv).show(effect, {direction:dir}, duration);
    });
  },
  
  getSlider: function() {
    var slider = {
          
      container: null,
      slide: null,
      panels: [],
      forward: null,
      back: null,
      
      currentPanelIndex: 0,
      
      getPanelWidth: function() {
        return $(slider.panels[0]).width();
      },
      addPanel: function(div) {
        $(div).appendTo(slider.slide);
        slider.panels.push($(div));
      },
      addAndMoveToPanel: function(div) {
        slider.addPanel(div);
        slider.currentPanelIndex = slider.panels.length - 1;
        slider.updateSlidePosition();
      },
      // replacePanel: function(div, index) {
      //   $(div).insertAfter(slider.panels[(index)]);
      //   slider.panels[(index)];
      //   slider.panels[(index)] = div;
      //   setupCCInfo;
      // },
      removePanel: function(index) {
        $(slider.panels[(index)]).remove();
        slider.panels.splice(index, 1);
      },
      slideForward: function(e) {

        
        if((slider.currentPanelIndex + 1) < slider.panels.length) {
          slider.currentPanelIndex++;
          slider.updateSlidePosition();
        }
        else {
        }
      },
      slideBack: function(e) {
        console.log('moving back');
        
        if ((slider.currentPanelIndex - 1) < 0) {

        }
        else {
          slider.currentPanelIndex--;
          slider.updateSlidePosition();
        }
      },
      updateSlidePosition: function() {
        var newPosition = -(slider.currentPanelIndex * slider.getPanelWidth());
        
        slider.slide.animate({
          left: newPosition + 'px'
        });
      }
    };
    return slider;
  }
};



