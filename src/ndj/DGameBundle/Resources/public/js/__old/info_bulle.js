// Requiert outils.js
var mouseX  = 0;
var mouseY  = 0;

// Recuperer la position de la souris dans la page
/*
   /!\ Ne fonctionne pas sur les pages en HTML Strict
   /!\ mais seulement sur les pages en HTML Transitional sur IE...
   /!\ Merci Billou...
*/
function getMousePosition(e)
{
  // Recherche de la position
  if (nava)
  {
    mouseX = e.pageX ;
    mouseY = e.pageY ;
  }
  else if (iex)
  {
    mouseX = window.event.x + document.body.scrollLeft;
    mouseY = window.event.y + document.body.scrollTop;
  }
  else if (dom)
  {
    mouseX = e.pageX ;
    mouseY = e.pageY ;
  }
  // FIN Recherche de la position

  // Leger decalage pour ne pas rester sous le pointeur de la souris
  mouseX += 20;
  

  // Car on ne sait jamais...
  if (mouseX < 0) mouseX = 0;
  if (mouseY < 0) mouseY = 0;

  // Deplacement du DIV d'information
  //var style = $('info_div').getStyle();
  //style.left = mouseX+"px";
  //style.top  = mouseY+"px";
  $('info_div').setStyle({left:mouseX+"px",top:mouseY+"px"});

  return true;
}

// Mise en marche de la capture de la souris
     if (nava) document.captureEvents(Event.MOUSEMOVE)
else if (iex ) document.onmousemove = getMousePosition;
else if (dom ) document.onmousemove = getMousePosition;

// Montrer le DIV d'information en y inserant txt
function show_info(txt)
{
	//alert(txt);
  $('info_div').show();// = 'shown';
  //var info_style = 
  $('info_div').setStyle({left:mouseX+"px",top:mouseY+"px"});
  //info_style.left = mouseX+"px";
  //info_style.top  = mouseY+"px";
  $('info_div').update(txt);
}

// Cacher le DIV d'information
function hide_info()
{
  $('info_div').hide();// = 'hidden';
}

/*
html : 
     <div id="info_div" class="hidden"></div>
css : 
#info_div {
  padding          : 0.2em;
  padding-top      : 0.1em;
  padding-bottom   : 0.1em;
  position         : absolute;
  border           : solid 1px black;
  background-color : rgb(255,255,225);
  font-size        : 80%;
}

.shown {
}

.hidden {
  display : none;
}
*/