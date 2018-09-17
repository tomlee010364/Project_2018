$(document).ready(function(){
	$('#submit_form').submit(function(){
		$.ajax({
			type:"POST",
			url:"check.php",
			data:$("#submit_form").serialize(),
			success:function(data){
				if(data=="password is wrong" || data=="account is wrong"){
					alert(data);
				}
				else{
					alert("success");
					window.location.replace("success.php?id="+data);
				}
			}
		});
		return false;
	});
});