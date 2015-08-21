<script language="javascript">
	if (AC_FL_RunContent == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		AC_FL_RunContent(
			'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
			'width', '500',
			'height', '700',
			'src', 'rechts',
			'quality', 'high',
			'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
			'align', 'middle',
			'play', 'true',
			'loop', 'true',
			'scale', 'showall',
			'wmode', 'transparent',
			'devicefont', 'false',
			'id', 'rechts',
			'bgcolor', '#999966',
			'name', 'rechts',
			'menu', 'true',
			'allowFullScreen', 'false',
			'allowScriptAccess','sameDomain',
			'movie', 'flash/rechts',
			'flashvars', 'language=<?=$_SESSION['language']?>',
			'salign', ''
			); //end AC code
	}
</script>
<noscript>
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="500" height="700" id="rechts" align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
    <param name=FlashVars value="language=<?=$_SESSION['language']?>">
	<param name="allowFullScreen" value="false" />
	<param name="movie" value="flash/rechts.swf" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><param name="bgcolor" value="#999966" />	<embed src="flash/rechts.swf" quality="high" FlashVars="language=<?=$_SESSION['language']?>" wmode="transparent" bgcolor="#999966" width="500" height="700" name="rechts" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>
</noscript>
