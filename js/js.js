$(document).ready(function(){
	$(".checkthisform").submit(formChecker);
	$(document).ready(passChanger);
	$("#pchoose").change(passChanger);
});

//show new or old password fields
function passChanger(){
	var value = $("#pchoose").val();
	if(value == "new"){
		$("#oldPassword").hide();
		$("#newPassword").show();
		$("#fpass").addClass("checkthis");
		$("#fdesc").addClass("checkthis");
		$("#passid").removeClass("checkthis");
	}else{
		$("#oldPassword").show();
		$("#newPassword").hide();
		$("#fpass").removeClass("checkthis");
		$("#fdesc").removeClass("checkthis");
		$("#passid").addClass("checkthis");
	}
}

//this will check if form fielads are not empty
function formChecker(){
	var error = false;
	$("input[type='text']").each(function(){
		if($(this).hasClass("checkthis")){
			if($(this).val() == ""){
				$("#"+$(this).attr("id")+"_gr").addClass("error");
				$("#"+$(this).attr("id")+"_err").html("Please fill this field!");
				error = true;
			}else{
				$("#"+$(this).attr("id")+"_gr").removeClass("error");
				$("#"+$(this).attr("id")+"_err").html("");
			}
		}
	});
	$("input[type='password']").each(function(){
		if($(this).hasClass("checkthis")){
			if($(this).val() == ""){
				$("#"+$(this).attr("id")+"_gr").addClass("error");
				$("#"+$(this).attr("id")+"_err").html("Please fill this field!");
				error = true;
			}else{
				$("#"+$(this).attr("id")+"_gr").removeClass("error");
				$("#"+$(this).attr("id")+"_err").html("");
			}
		}
	});
	$("select").each(function(){
		if($(this).hasClass("checkthis")){
			if($(this).val() == ""){
				$("#"+$(this).attr("id")+"_gr").addClass("error");
				$("#"+$(this).attr("id")+"_err").html("Please fill this field!");
				error = true;
			}else{
				$("#"+$(this).attr("id")+"_gr").removeClass("error");
				$("#"+$(this).attr("id")+"_err").html("");
			}
		}
	});
	if(error){
		return false;
	}else{
		return true;
	}
}