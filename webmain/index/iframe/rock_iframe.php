<?php defined('HOST') or die('not access'); ?>
<script >
$(document).ready(function(){
	{params}
	var url = jm.base64decode(params.url);
    console.log(url);
	get('iframe{rand}').src = url;
	$('#iframediv{rand}').css('height',''+(viewheight-20)+'px');
    sessionStorage.removeItem('wcl_reload');
    sessionStorage.setItem('wcl_reload','3');
});
</script>
<div style="height:200px" id="iframediv{rand}">
<iframe src="" id="iframe{rand}" width="100%" height="100%" frameborder="0"></iframe>
</div>
