function myFunction() {
    var checkBox = document.getElementById("hurry");
	var text1 = document.getElementById("text1");
	var text2 = document.getElementById("text2");
	var time = document.getElementById("time");
	var point = document.getElementById("point");
    if (checkBox.checked == true){
        text1.style.display = "block";
		text2.style.display = "block";
		time.style.display = "block";
		point.style.display = "block";
    } else {
       text1.style.display = "none";
		text2.style.display = "none";
		time.style.display = "none";
		point.style.display = "none";
    }
}