$(document).ready(function(){
	$('#register_form').submit(function(){
		$.ajax({
			type:"POST",
			url:"register_info.php",
			data:$("#register_form").serialize(),
			success:function(data){
				if(data=="success"){
					alert("Done");
					window.location.replace("login.php");
				}
				else{
					alert(data);
				}
			}
		});
		return false;
	});
});