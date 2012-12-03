
var spinnerVar;

$(function() {

  initAjaxUpload($('#newMaster'));
  $('.use-this-master').click(useThisMaster);
  $('#removeMasters').click(removeMasters);
  
});

function useThisMaster() {
  var postData = {id : $(this).closest('tr').attr('id')};
  var spinnerDiv = $(this).parent().find('.for-table-spinner');
  
  Message.confirm('This will wipe all entries in the current list (including new ones added from retail files run) and replace them with the contents of this file. Do you want to continue?', function() {
    var spinner = UIHelper.smallSpinner();
    spinnerDiv.append(spinner.el);
  
    $.post('useThisMaster', postData, function(result) {
		  result = $.parseJSON(result);
		  spinner.stop();
      delete spinner;
		  if (result.status == true) {
			  Message.flash('New Master List assigned.', result.status);
			  setTimeout("location.reload(true);",2500);
		  }
		  else {
			  Message.flash('There was an error assigning the new Master List.', result.status, result.messages);
		  }
		
	  });
  });
	
}

function initAjaxUpload(link) {
		
  new AjaxUpload($(link), {
	  action: getBaseURL() +'/files/newMaster',
	  name: 'masterFileUpload',
	  onSubmit: function(file, ext) {
	  

	    spinnerVar = UIHelper.smallSpinner({left: 30});
      $(link).parent().find('.for-upload-spinner').append(spinnerVar.el);
	    
	  },
	  onComplete: function(file, response) {
	    spinnerVar.stop();
      delete spinnerVar;
		  response = $.parseJSON(response);

		  if (response.status == true) {
		    Message.flash('Master List File uploaded successfully.', true);
		    setTimeout("location.reload(true);",1500);
		  }
		  else {
			  Message.flash('Something went wrong during the upload.', false, response.messages);
		  }	
	  }
  });
}

function removeMasters()
{
  Message.confirm('Are you sure you want to delete all Master Lists ever?', function() {
    window.location.href='removeMasters';
  });
}
