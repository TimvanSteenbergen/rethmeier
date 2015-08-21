<!--

// het id in deze functie is node met children in de volgorde
// van opbouwen. pos is de positie. deze kan "mid" of "end" zijn,
// het resultaat hiervan lijkt me duidelijk.

function showitem(id,pos) {
  if (item[id] == "false") {
  	// item staat uit, aanzetten
  	document.getElementById(id).style.display='inline';
	if (pos == "mid") {
	  // item is een middennode
 	  document.getElementById('plus'+id).src='images/ftv2mnode.gif';
	} else {
	  // item is een lastnode
	  document.getElementById('plus'+id).src='images/ftv2mlastnode.gif';
	}
	item[id] = "true";
  } else {
    // item staat aan, uitzetten
   	document.getElementById(id).style.display='none';
	if (pos == "mid") {
	  // item is een middennode
 	  document.getElementById('plus'+id).src='images/ftv2pnode.gif';
	} else {
	  // item is een lastnode
	  document.getElementById('plus'+id).src='images/ftv2plastnode.gif';
	}
	item[id] = "false";
  }	
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
-->