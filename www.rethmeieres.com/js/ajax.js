function createObject() 
{
	var request_type;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		request_type = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		request_type = new XMLHttpRequest();
	}
	return request_type;
}

var http = createObject();

function ajaxget(div, url) 
{

	var nocache = Math.random();
	http.open('get', url+'&nocache='+nocache);
	http.onreadystatechange = function()
	{
		if(http.readyState == 4)
		{	
	
			document.getElementById(div).innerHTML = http.responseText;
			
		}
	}
	http.send(null);
}

function ajaxupdatevalue(div, url) 
{

	var nocache = Math.random();
	http.open('get', url+'&nocache='+nocache);
	http.onreadystatechange = function()
	{
		if(http.readyState == 4)
		{	
			document.getElementById(div).value = http.responseText;
		
		}
	}
	http.send(null);
}

function htmlspecialchars(string, quote_style) {
    // http://kevin.vanzonneveld.net
    // +   original by: Mirek Slugen
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Nathan
    // +   bugfixed by: Arno
    // *     example 1: htmlspecialchars("<a href='test'>Test</a>", 'ENT_QUOTES');
    // *     returns 1: '&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;'
    
    string = string.toString();
    
    // Always encode
    string = string.replace(/&/g, '&amp;');
    string = string.replace(/</g, '&lt;');
    string = string.replace(/>/g, '&gt;');
    
    // Encode depending on quote_style
    if (quote_style == 'ENT_QUOTES') {
        string = string.replace(/"/g, '&quot;');
        string = string.replace(/'/g, '&#039;');
    } else if (quote_style != 'ENT_NOQUOTES') {
        // All other cases (ENT_COMPAT, default, but not ENT_NOQUOTES)
        string = string.replace(/"/g, '&quot;');
    }
    
    return string;
}

function addslashes( str)
{
	return (str+'').replace(/([\\"'])/g, "\\$1").replace(/\0/g, "\\0");
}

function urlencode( str ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Philip Peterson
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: AJ
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // %          note: info on what encoding functions to use from: http://xkr.us/articles/javascript/encode-compare/
    // *     example 1: urlencode('Kevin van Zonneveld!');
    // *     returns 1: 'Kevin+van+Zonneveld%21'
    // *     example 2: urlencode('http://kevin.vanzonneveld.net/');
    // *     returns 2: 'http%3A%2F%2Fkevin.vanzonneveld.net%2F'
    // *     example 3: urlencode('http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a');
    // *     returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a'
                                     
    var histogram = {}, histogram_r = {}, code = 0, tmp_arr = [];
    var ret = str.toString();
    
    var replacer = function(search, replace, str) {
        var tmp_arr = [];
        tmp_arr = str.split(search);
        return tmp_arr.join(replace);
    };
    
    // The histogram is identical to the one in urldecode.
    histogram['!']   = '%21';
    histogram['%20'] = '+';
    
    // Begin with encodeURIComponent, which most resembles PHP's encoding functions
    ret = encodeURIComponent(ret);
    
    for (search in histogram) {
        replace = histogram[search];
        ret = replacer(search, replace, ret) // Custom replace. No regexing
    }
    
    // Uppercase for full PHP compatibility
    return ret.replace(/(\%([a-z0-9]{2}))/g, function(full, m1, m2) {
        return "%"+m2.toUpperCase();
    });
    
    return ret;
}

function ajaxpost(div, form, url) 
{
	var http2 = createObject();
    http2.open("POST", url, true);
	
	var query = '';
	form = document.forms[form];
	
	for(var x = 0 ; x < form.length; x ++)
	{
		if(form[x].length != undefined)
		{
			for(var y = 0 ; y < form[x].length; y ++)
			{	
				if(form[x][y].selected)
				{
					query = query + form[x].name + '=' + form[x][y].value +'&';
				}
			}
		}
		else
		{
			query = query + form[x].name + '=' + urlencode(form[x].value) +'&';	
		}
	}
	
	http2.onreadystatechange =  function()
	{
		if(http2.readyState == 4)
		{
			document.getElementById(div).innerHTML = http2.responseText;
		}
	}
	document.getElementById(div).innerHTML = "<img src='gfx/ajax-loader.gif'>";
    http2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");    
    http2.send(query);


}