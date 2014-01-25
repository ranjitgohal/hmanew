
<html> 
<head> 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script> 
<script type="text/javascript"> 
jQuery(document).ready(function($) {
i= 0;
$(function() {
  setInterval(function() {

    $("#container").text(i++);
	
	$.get('http://115.146.85.132/hmamain/pages/rest/record?id=36&tmp=7&bp=7&hb=7',function(data){ console.log(data) });
 
  
  },3000);
});
});
</script> 
<style> 
    body {
        margin: 0;
        padding: 0;
        background: white;
    }
    .container {
        width: 100%;
        margin: 0;
        padding: 0; 
    }
    #container {
        margin: 100px;
        padding: 0;
        color: black;
        font-size: 14px;;
        font-family: impact, arial black;
        text-align: center;     
    }   
</style> 
</head> 
<body> 
<div class=container><p id=container>sending data</p></div> 
</body> 
</html>
