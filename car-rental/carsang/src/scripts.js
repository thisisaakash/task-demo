var host = "http://localhost/goa-task/car/";//window.location.protocol+'//'+window.location.hostname+'/goa-task/car/';
function scroll_top() {
    $('html,body').animate({
        scrollTop: $('.form-error:first').offset().top - 60
    });
}
function isText(text) {
	var regex = /^[a-zA-Z' ]*$/;
	return regex.test(text);
}
function hideMessage() {
    $('#getRequestMessage').html('');
}
function validateForm(formID){
	var errorFound = false;
	$(formID).find('.form-error').remove();
	$(formID).find('.required').removeClass('border-red').each(function(){
		var fieldName = $(this).attr('fieldName');
		var val = $.trim($(this).val());
		var attrName = $(this).attr('name');
		if(val == "" || val == null){
			errorFound = true;
			var html = '<div class="form-error">Please enter '+fieldName+'</div>';
			if($(this).hasClass('select')){
				html = '<div class="form-error">Please select '+fieldName+'</div>';
			}
			$(this).addClass('border-red').after(html);
		}
	});
	if(errorFound === true){
		return false;
	} else {
		return true;
	}
}
$(document).ready(function(){
	$('form').submit(function(event){
		event.stopPropagation();
		var formID = '#'+$(this).attr('id');
		if(validateForm(formID)){
			var data = $(formID);
			var datas = new FormData($(data)[0]);
			$.ajax({
				type: "POST",
				url: host+"functions.php",
				data: datas,
				mimeType: "multipart/form-data",
				contentType: false,
				processData: false,
				beforeSend: function(){
					$(formID+" [name='submit']").attr('type','button').html('Loading...');
				},
				success: function(response) {
					if(response == ""|| response == null){
						$('#getRequestMessage').html('Something went wrong!!! Please try again').removeClass().addClass('highlight-error').show();
					} else {
						var data = JSON.parse(response);
						if(data['statusCode'] == '201') {
							document.getElementById(formID.substring(1,formID.length)).reset();
							$('#getRequestMessage').html(data['message']).removeClass().addClass('highlight-success').show();
						} else {
							$('#getRequestMessage').html(data['message']).removeClass().addClass('highlight-error').show();
						}
						setTimeout("hideMessage()", 5000);
					}
					$(formID+" [name='submit']").attr('type','submit').html('Submit');
				},
				error: function(){
					$('#getRequestMessage').html('Something went wrong!!! Please try again').removeClass().addClass('highlight-error').show();
				}
			});
		}	
		return false;
	});
	$("input").on("keypress", function(e) {
		if (e.which == 13) {
			return false;
		} else {
			$(".form-error:not('.uploadError')").remove();
			$(".border-red:not('.uploadError')").removeClass("border-red");
		}
	});
	if($('select[name="m_id"]').length){
		$.post(host+"functions.php",{'function':'getManufacturers'},function(response, status){
			if(response == ""|| response == null){
				$('#getRequestMessage').html('Something went wrong!!! Please try again').removeClass().addClass('highlight-error').show();
			} else {
				if(response['statusCode'] == '200') {
					for(var i=0;i<response['manufacturers'].length;i++){
						$('select[name="m_id"]').append('<option value="'+response['manufacturers'][i]['m_id']+'">'+response['manufacturers'][i]['m_name']+'</option>');
					}
				} else {
					$('#getRequestMessage').html(response['message']).removeClass().addClass('highlight-error').show();
				}
				setTimeout("hideMessage()", 5000);
			}
		});
	}
	if($('#viewInventoryTable').length){
		loadInventory();
	}
});
function loadInventory(){
	$.post(host+"functions.php",{'function':'getInventory'},function(response, status){
		if(response == ""|| response == null){
			$('#getRequestMessage').html('Something went wrong!!! Please try again').removeClass().addClass('highlight-error').show();
		} else {
			if(response['statusCode'] == '200') {
				$('#viewInventoryTable tbody').html('');
				for(var i=0;i<response['inventory'].length;i++){
					$('#viewInventoryTable tbody').append('<tr m_id="'+response['inventory'][i]['m_id']+'" md_id="'+response['inventory'][i]['md_id']+'"><td>'+response['inventory'][i]['m_id']+'</td><td>'+response['inventory'][i]['m_name']+'</td><td>'+response['inventory'][i]['md_name']+'</td><td>'+response['inventory'][i]['COUNT(*)']+'</td></tr>');
				}
				//$('#viewInventoryTable').DataTable().clear();
				$('#viewInventoryTable').DataTable().destroy();
				$('#viewInventoryTable').DataTable();
				$('#viewInventoryTable tbody tr').click(function(){
					$('#inventoryModal .modal-title').text($('td:nth-child(2)',this).text());
					$('#inventoryModal').modal('show');
					var m_id = $(this).attr('m_id');
					var md_id = $(this).attr('md_id');
					$.post(host+"functions.php",{'function':'getInventoryDetails','m_id':m_id,'md_id':md_id},function(response, status){
						if(response == ""|| response == null){
							$('#getRequestMessage').html('Something went wrong!!! Please try again').removeClass().addClass('highlight-error').show();
						} else {
							if(response['statusCode'] == '200') {
								$('#inventoryModalList tbody').html('');
								for(var i=0; i<response['inventory'].length; i++){
									$('#inventoryModalList tbody').append('<tr><td>'+response['inventory'][i]['md_name']+'</td><td>'+response['inventory'][i]['md_color']+'</td><td>'+response['inventory'][i]['md_mfg_year']+'</td><td>'+response['inventory'][i]['md_reg_number']+'</td><td>'+response['inventory'][i]['md_chachis_number']+'</td><td>'+response['inventory'][i]['md_notes']+'</td><td><a href="javascript:;" onclick="getModalImages(this,'+response['inventory'][i]['md_id']+');">view</a></td><td><a href="javascript:;" onclick="soldModal(this, '+response['inventory'][i]['md_id']+');">Sold</a></td></tr>');
								}
								//$('#inventoryModalList').DataTable().clear();
								$('#inventoryModalList').DataTable().destroy();
								$('#inventoryModalList').DataTable();
							}
						}
					});
				});
			} else {
				$('#getRequestMessage').html(response['message']).removeClass().addClass('highlight-error').show();
			}
			setTimeout("hideMessage()", 5000);
		}
	});
}
function soldModal(self, md_id){
	$.post(host+"functions.php",{"function":"soldModal","md_id":md_id},function(response, status){
		if(response == ""|| response == null){
			$('#getRequestMessage').html('Something went wrong!!! Please try again').removeClass().addClass('highlight-error').show();
		} else {
			if(response['statusCode'] == '200') {
				var model = $(self).parents('tr').children('td:first-child').text();
				$('#messageModalInner').text(model+' model is sold');
				$(self).parents('tr').remove();
				$('#messageModal').modal('show');
				$('#inventoryModalList').DataTable().destroy();
				$('#inventoryModalList').DataTable();
				loadInventory();
			} else {
				$('#getRequestMessage').html('No Images Found').removeClass().addClass('highlight-error').show();
			}
		}
	});
}
function getModalImages(self, md_id){
	$.post(host+"functions.php",{"function":"getModalImages","md_id":md_id},function(response, status){
		if(response == ""|| response == null){
			$('#getRequestMessage').html('Something went wrong!!! Please try again').removeClass().addClass('highlight-error').show();
		} else {
			$('#inventoryModalImagesList').html('');
			$('#inventoryModalImage .modal-title').text($(self).parents('tr').children('td:first-child').text()+' Images');
			if(response['statusCode'] == '200') {
				for(var i=0; i<response['images'].length; i++){
					$('#inventoryModalImagesList').append('<div class="col-sm-6"><img src="'+host+'images/'+response['images'][i]['mdi_name']+'" alt="" /></div>');
				}
				$('#inventoryModalImage').modal('show');
			} else {
				$('#inventoryModalImagesList').html('No Images Found');
				$('#inventoryModalImage').modal('show');
			}
		}
	});
}
if(window.File && window.FileList && window.FileReader) {
	$("#imageFile").on("change", function(e) {
		$(this).parent().find(".form-error").remove();
		var ele = $(this);
		$(this).parent().find(".customfileupload").html("");
		var error = 0;
		var totalSize = 0;
		var files = e.target.files, totalFile = filesLength = files.length;
		var filenames = '';
		if (totalFile != 2) {
			ele.val("");
			ele.parent().append("<div class='form-error uploadError'>Please upload 2 files</div>");
			return false;
		}
		for (i = 0; i < filesLength; i++) {
			var f = files[i];
			var fname = f.name;
			var type = f.type;
			totalSize += Math.round(f.size / 1024);
			var ext = fname.slice((Math.max(0, fname.lastIndexOf(".")) || Infinity) + 1);
			if (ext == 'png' || ext == 'jpg' || ext == 'jpeg') {
				if (i > 0) {
					filenames += ", ";
				}
				filenames += fname;
				var fileReader = new FileReader();
				fileReader.onload = (function(e) {
					var file = e.target;
					ele.parent().find(".img_name").html(f.name);
				});
				fileReader.readAsDataURL(f);
			} else {
				error++;
			}
		}
		if (Math.round(totalSize / 1000) > 5) {
			ele.val("");
			ele.parent().append("<div class='form-error uploadError'>Uploaded file size exceeds limit!!!</div>");
			return false;
		}
		if (error > 0) {
			ele.val("");
			ele.parent().append("<div class='form-error uploadError'>Please upload valid files</div>");
			return false;
		}
		$(this).parent().find(".customfileupload").html(filenames);
	});
} else {
	alert("Your browser doesn't support to File API");
}