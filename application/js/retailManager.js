var searchTimeout = 0;

$(function() {

  initAjaxUpload($('#uploadAndRun'));
  $('.retail-continue').on('click', continueRetail);
});

function continueRetail() {
  var id = $(this).closest('tr').attr('id');
  openContinueModal(id)
}

function openContinueModal(id) {
  $.post('continueRetail', {listId: id}, function(result) {
	  Message.popup(result, {onClose: function() {
	      $.modal.close();
        $('#popupModal').html("");
        $('#popupModal').removeClass();
        document.location.reload(true);
	  }});
	  initContinue();
  });
}

function initContinue() {
  Message.resize();
  $('.master-row').click(function() {
    var masterId = $(this).attr('id');
    saveCodeToRetail(masterId);
  });
  $('#addNewCode').click(function() {
    var masterId = $('#newCode').val();
    saveCodeToRetail(masterId);
  });
  $("#searchMasters").keyup(function(event) {
    var searchVal = $(this).val();
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
      filterMasters(searchVal);
    }, 500);
  });
  $('#generateAll').click(autoGenAll);
}

function autoGenAll() {  
	var listId = $('#currentRetailListId').val();
	$('#popupModal').html('<h3>Loading...</h3>');
  $.post('autoGenAll', {listId: listId}, function(result) {
	  result = $.parseJSON(result);

	  if (result.status == true) {
		  Message.flash('All codes successfully assigned.', result.status, result.status);
      $.post('continueRetail', {listId: listId}, function(result) {
        $('.simplemodal-close').trigger('click');
      });
	  }
	  else {
		  Message.flash('There was an error assinging the Codes.', result.status, result.messages);
	  }
  });
}

function filterMasters(searchVal) {
  $('.master-row').each(function() {
    var haystack = new Array();

    if (valIsPartOfRow(searchVal, $(this).find('.master-search').text())) {
      $(this).show();
    }
    else {
      $(this).hide();
    }
  });
}

function valIsPartOfRow(needle, haystack) {
  needle = needle.toLowerCase();
  haystack = haystack.toLowerCase();
  return (haystack.indexOf(needle) !== -1);
}

function saveCodeToRetail(masterId) {
  var retailId = $('#retailEntryId').val();
	var listId = $('#currentRetailListId').val();
  $('#popupModal').html('<h3>Loading...</h3>');
  $.post('saveCodeToRetail', {retailId: retailId, masterId: masterId}, function(result) {
	  result = $.parseJSON(result);

	  if (result.status == true) {
		  Message.flash('Code successfully assigned.', result.status, {}, 3000);
      $.post('continueRetail', {listId: listId}, function(result) {
        $('#popupModal').html(result);
        initContinue();
      });
	  }
	  else {
		  Message.flash('There was an error assinging the Code.', result.status, result.messages);
	  }
  });
  
}

function initAjaxUpload(link) {
		
  new AjaxUpload($(link), {
	  action: getBaseURL() +'/parser/uploadAndRun',
	  name: 'retailFileUpload',
	  onSubmit: function(file, ext) {
	  
	    spinnerVar = UIHelper.smallSpinner({left: 30});
      $(link).parent().find('.for-upload-spinner').append(spinnerVar.el);
	    
	  },
	  onComplete: function(file, response) {
	    spinnerVar.stop();
      delete spinnerVar;
		  response = $.parseJSON(response);

		  if (response.status == true) {
		    Message.flash('Retail List File uploaded successfully.', true, response.messages, 15000);
		    var listId = response.data.listId;
		    openContinueModal(listId);
		  }
		  else {
			  Message.flash('Something went wrong during the upload.', false, response.messages);
		  }	
	  }
  });
}
