mode = 'donjon';
map={};
mapA={};

function gLoadTab(_call)
{
		tab = null;
		params = null;
		
		if (_call == null || _call == '') {
			tab = 'bureau';
		} else if (_call[0] == '#') {
			req = _call.substring(1);
			req = req.split('?');
			// alert("clic# | call="+_call+" | "+req+" ("+req.length+")");
			if (req.length > 1)	 {
				tab = req[0];
				params = req[1];
				var query = Routing.generate('gamedonjon_getinterface',{'interface':tab})+'?'+params;
				start_loading();
				//console.log(query)
				$.get(query).done( function(data) {
					$("div#layer_"+tab).html(data);
                                        })
                                        .fail(function(err){ set_error("Erreur lors du chargement de "+query); })
                                        .always(function(){ stop_loading(); });
			} else {
				tab = req[0];
			}
		}
		
		$('#eNavMenu ul li.current').removeClass('current');
		$("#eNavMenu ul li a[href*="+tab+"]").parent().addClass('current');	
		$("div#eContent > div[id*=layer_]").hide();
		$("div#eContent > div#layer_"+tab).show();
	}
	
$(function () {
	$("div#eNavMenu ul li a").click(function(){
		gLoadTab($(this).attr('href'));
		//return false;
	});

	gLoadTab(self.document.location.hash);
});

function gKeyLoadTabs(h) {
    self.document.location.hash=h;
    gLoadTab(h);
}

//
// ECONOMAT
//
function economatLoad(l)
{
	//loadInLayer('economat/index'+l,'economat-main-panel');
	self.document.location.hash = l;
	gLoadTab(l);
	$("#economat-main-panel").css('backgroundImage','none');
	//window.
}

function deposerPo(idPIECE,x,y,s) {
	//$.get(dir+"_ajax_query.php/economat/deposerOr?idPIECE="+idPIECE+"&x="+x+"&y="+y+"&s="+s, function(data){
	console.log(Routing.generate('economat_deposeror',{'idpiece':idPIECE,'x':x,'y':y,'or':s}))
	$.get(Routing.generate('economat_deposeror',{'idpiece':idPIECE,'x':x,'y':y,'or':s})).done(function(data){
		if(data!='') {
			set_error(data);
		} else {
			updateAffichageDonneeNoVal('PACT'); 
			updateAffichageDonneeNoVal('ARGENT'); 
			updateAffichageDonneeNoVal('ARGENT_AU_SOL'); 
			loadInLayer(Routing.generate('economat_or'), '#economat-main-planel');
		}
	});
}

function ramasserOr(idp,x,y,or)
{
	//dir+'_ajax_query.php/economat/ramasserOr?idPIECE='+idp+'&x='+x+'&y='+y+'&or='+or
	$.get(Routing.generate('economat_ramasseror',{'idpiece':idp,'x':x,'y':y,'or':or})).done(function(data){
		if(data!='') {
			set_error(data);
		} else {
			updateAffichageDonneeNoVal('PACT'); 
			updateAffichageDonneeNoVal('ARGENT');
			updateAffichageDonneeNoVal('ARGENT_AU_SOL');
			loadInLayer(Routing.generate('economat_or'), '#economat-main-planel');
		}
	});
}

function EconomatClickDeposer()
{
	idPIECE = $( "#economat-or-salle-selector" ).val();
   	x = $( "#spinner-ECO-DEPOSER-X" ).val();
   	y = $( "#spinner-ECO-DEPOSER-Y" ).val();
   	s = parseFloat($( "#spinner-ECO-DEPOSER-PO" ).val());
	if (s>0) {
		deposerPo(idPIECE,x,y,s);			
    	$("#economat-or-deposer-dialog").dialog("close");
    } else {
    	$( "#spinner-ECO-DEPOSER-PO" ).effect( "shake" ).focus();
    }
}

function economat_inv_vendre(id)
{
	if (confirm('Souhaites-tu vraiment mettre cet objet en vente ?'))
	{
		loadInLayer(Routing.generate('economat_inventairevendre',{'id':id}), '#economat-main-panel');
	}
}

function economat_inv_deposer(id)
{	
	set_dialog({title:'Déposer un objet'}, Routing.generate('economat_inventairedeposerform',{'id':id}) );
}

function economat_inv_deposer_submit(f)
{
	var idINVENTAIRE = f.find('input[name="idINVENTAIRE"]').val();
	var x = f.find('input[name="x"]').val();
	var y = f.find('input[name="y"]').val();
	var idPIECE = f.find('select[name="idPIECE"]').val();

	var cmd = Routing.generate('economat_inventairedeposer',{'id':idINVENTAIRE,'idpiece':idPIECE,'x':x,'y':y});
	loadInLayer(cmd, '#economat-main-panel');
	f.parent().dialog('close');
}

function economat_inv_ranger(id)
{
	if (confirm('Souhaites-tu vraiment ranger cet objet dans tes coffre ?'))
	{
		loadInLayer(Routing.generate('economat_inventaireranger',{'id':id}), '#economat-main-panel');
	}
}

function economat_inv_equiper(id)
{
	set_dialog({title:'Équiper une créature'}, 'economat/inventaireEquiperForm?idINVENTAIRE='+id);
	//loadInLayer(Routing.generate('economat_inventairevendre',{'id':id}), '#economat-main-panel');
}


function economat_bes_vendre(id)
{
	if (confirm('Souhaitez-vous vraiment mettre cette créature en vente ?'))
	{
		loadInLayer(Routing.generate('economat_bestiairevendre',{'id':id}), '#economat-main-panel');
	}
}

function economat_bes_salle(id)
{
	set_dialog({title:'Placer une créature'}, Routing.generate('economat_bestiairesalleform',{'id':id}));
}

function economat_bes_salle_submit(f)
{
	var idBESTIAIRE = f.find('input[name="idBESTIAIRE"]').val();
	var x = f.find('input[name="x"]').val();
	var y = f.find('input[name="y"]').val();
	var idPIECE = f.find('select[name="idPIECE"]').val();

	var cmd = Routing.generate('economat_bestiairesalle',{'id':idBESTIAIRE,'idpiece':idPIECE,'x':x,'y':y});
	loadInLayer(cmd, '#economat-main-panel');
	f.parent().dialog('close');
}

function economat_bes_repos(id)
{
	if (confirm('Souhaitez-vous vraiment mettre cette créature au repos ?'))
	{
		loadInLayer(Routing.generate('economat_bestiairerepos',{'id':id}), '#economat-main-panel');
	}
}

function allerAuMarcher()
{
   /* $( "#layer_mache_dialog" ).dialog( "open");
    if ($( "#layer_mache_dialog" ).html()=='')
    {
    	loadInLayer("commerce/index", "layer_mache_dialog");
    }
    */
    loadInLayer(Routing.generate("commerce_index"), "#economat-main-panel");
    set_background('marche_forge','#economat-main-panel');
}

// ???????????????????


//
//	EDITEUR ETAGE
//
function addEtage(endroit)
{
	var taille = prompt("Quelle taille souhaites-tu donner à l'étage ?\n (Pour connaître le prix de la construction, multiplie la taille par 10.\n Exemple, un étage de taille 60 coûte 600 PO).","60");
	if (taille!=null && !isNaN(taille)) {	
		start_loading();
		$.post(Routing.generate('etage_add',{'taille':taille,'o':endroit})).done( function(data){
			if(data[0]=='1') {
				etage = data.substring(2).split(':');
				line = '<tr><td>Niveau '+etage[1]+'</td><td><div id="editInPlace_'+etage[1]+'">'+etage[2]+'</div></td><td><a href="#editeur?e='+etage[0]+'" onclick="gLoadTab($(this).attr(\'href\'));">Editer</a></td></tr>';
			
				if (endroit == 'p') {
					$('table#tableListeEtage tr:nth-child(2)').after(line);
				} else {
					$('table#tableListeEtage tr:last-child').before(line);
				}
				setClickable();
				updateAffichageDonneeNoVal('ARGENT');
				updateAffichageDonneeNoVal('PACT');
			} else {
				set_error(data);
			}
		})
                .fail(function(err){ set_erro("Erreur lors de la création de l'étage !") })
                .always(function(){ stop_loading();});
	}
}

function saveChanges(idEtage, obj, cancel)
{
	if (!cancel) {
   		var t = $(obj).parent().siblings(0).val();
   				
   		if (t == '' || t == null) {
   			set_error("Le nom ne peut pas être vide !");
   			return;
   		}
   				
   		
   		$.post(Routing.generate('etage_setnom',{'nom': t, 'id': idEtage})).done(function(txt){
   					
   			tmp = txt.split(':');
   			if (txt[0]=='1') {
   				var t = txt.substring(2);
   			} else {
   				set_error(txt);
   				return;
   			}
 		});
	} else {
		var t = cancel;
	}
 			
	$(obj).parent().parent().after('<div id="editInPlace_'+idEtage+'">'+t+'</div>').remove();

	setClickable();
}
	
 	function setClickable()
 	{

   		$('div[id^="editInPlace_"]').click(function(){
   		var tmp = $(this).attr('id').split("_");
   		var idEtage =  tmp[1];
   		var textarea = '<div><input type="text" name="NOM" value="' + $(this).text() + '"/>';
 		var button = ' <span><input type="button" value="Ok" class="saveButton smaller" /> <input type="button" value="Annuler" class="cancelButton smaller"/></span></div>';
 		var revert = $(this).html(); 
 		
 		$(this).after(textarea+button).remove();
 		 
 		$('.saveButton').click(function(){saveChanges(idEtage, this, false);});
 		$('.cancelButton').click(function(){saveChanges(idEtage, this, revert);});
 		
    }).mouseover(function() {
			$(this).addClass("editable");
		})		
		.mouseout(function() {
			$(this).removeClass("editable");
		});
 	}

    	var nvllPieceEnCours = false;
    	
    	var nvllPieceInitPos = [];
    	
    	
    	function addPieceToGrid(initX, initY)
    	{
    		//setTimeout(function(){ clearEdition(); }, 2000);
    		
    		nvllPieceEnCours = true;    		
			nvllPieceInitPos = [initX, initY];
			setSquarePiece(initX, initY, parseInt(initX)+2, parseInt(initY)+2);
    	}
    	
		function setSquarePiece(xi,yi,x,y)
		{
			xi = parseInt(xi);
			yi = parseInt(yi);
			x = parseInt(x);
			y = parseInt(y);
			if (nvllPieceEnCours && x>xi+2 && y>yi+2)
			{
				$("td.gEEtempTile").removeClass('gEEtempTile');
				for(i = xi; i<=x; i++) {
					for(j = yi; j<=y; j++) {
						$("td#c"+i+"_"+j).addClass('gEEtempTile');
					}
				}
			}
		}
		
		function stopNouvellePiece()
		{
			$("td.gEEtempTile").removeClass('gEEtempTile');
			nvllPieceEnCours = false;
			nvllPieceInitPos = [];
		}
		
		function initGridClick(idETAGE)
		{
			stopNouvellePiece();
			
			$(".etageTile").click(function() {
				if (!nvllPieceEnCours && !$(this).hasClass('piece'))
				{
					var idTile = $(this).attr('id');
					var posTile = idTile.substring(1).split("_");
					addPieceToGrid(posTile[0], posTile[1]);
				}
				else if(!$(this).hasClass('piece'))
				{
					console.log("nll piece");
					var idTile = $(this).attr('id');
					var posTile = idTile.substring(1).split("_");
					
					$.post(Routing.generate('piece_add',{
							'id':idETAGE,
							'posx': nvllPieceInitPos[0],
							'posy': nvllPieceInitPos[1],
							'taillex':(posTile[0]-nvllPieceInitPos[0]+1),
							'tailley': (posTile[1]-nvllPieceInitPos[1]+1) }))
                                           .done( function(data) {
                                                    // console.log("data:"+data);
                                                    Donjon_eLoadPiece(idETAGE);
                                                    set_info(data);
                                                    updateAffichageDonneeNoVal('ARGENT');
                                                    updateAffichageDonneeNoVal('PACT');
   						} );
					
					stopNouvellePiece();
				}
			});
			
			
			$(".etageTile").mouseover(function() {
				if (nvllPieceEnCours && !$(this).hasClass('piece'))
				{
					var idTile = $(this).attr('id');
					var posTile = idTile.substring(1).split("_");
					setSquarePiece(nvllPieceInitPos[0], nvllPieceInitPos[1], posTile[0], posTile[1]);
				}
				else
				{
					$("div#ESalleInfo").html("Cliquez pour créer une nouvelle salle à partir d'ici.");
				}
			});
			
			
			$(document).keypress(function(event) {
  				if ( event.keyCode == 27 ) { // ECHAP
     				stopNouvellePiece();
   				}
   			});

		}

// chargement de la liste des pices dans l'etage
function Donjon_eLoadPiece(idEtage)
{
	start_loading();
	$("div#ESalleInfo").html('Chargement des salles en cours...');
	
		$.getJSON(Routing.generate('donjon_loadpieces',{'id':idEtage})).done( function(r){
			
    			// Handle the response content...
    			var tileColor1 = 100;
    			var tileColor2 = 100;
    			var tileColor3 = 20;
    			for(i=0;i < r.length;i++)
    			{
    				p = r[i];

    				larg = p.taillex;
    				haut = p.tailley;
    				dep_x = p.posx;
    				dep_y = p.posy;
					
					max_y = parseInt(dep_y) + parseInt(haut);
					max_x = parseInt(dep_x) + parseInt(larg);
					
					for(a = dep_y ; a < max_y ; a++)
					{
						for(b = dep_x ; b < max_x ; b++)
						{							
							$('#c'+b+'_'+a).addClass('piece');
							$('#c'+b+'_'+a).css("background-color","rgb("+tileColor1+","+tileColor2+","+tileColor3+")");
							$('#c'+b+'_'+a).attr('name', p.id);
							$('#c'+b+'_'+a).append('<span class="hide"><h3>'+p.nom+'</h3>Position : x='+p.posx+' - y='+p.posy+'<br />Taille : '+p.taillex+'x'+p.tailley+'</span>');
							//$('#c'+b+'_'+a).attr('title', p.Nom);
							$('#c'+b+'_'+a).dblclick(function(){
								$("div#ESalleInfo").html('Patientez... Chargement de la salle en cours pour l\'édition !');
								query = "#editeur?p=" + $(this).attr('name');
								window.location.href = query;
								gLoadTab(query);
							});
							$('#c'+b+'_'+a).mouseover(function(){
								$("div#ESalleInfo").html($(this).find('span').html());
							});
							
						}
					}
					
					tileColor1 = (tileColor1 + 70) % 255;
					tileColor2 = (tileColor2 + 30) % 255;
					tileColor3 = (tileColor3 + 20) % 255;
    				
    			}
    			$("div#ESalleInfo").html('Chargement des salles terminé !');
    			
		}).fail(function(err){set_error("Erreur lors du chargement (essayez de recharger la page !).");})
                .always(function(){stop_loading();});
}

//
//
// EDITEUR DE SALLE
//
//
function gGotoEMap(id) {
	if (map!=null && map!=undefined) {
		map.destroy();
	}
	gLoadTab('#editeur?p='+id);
}

__g_save_Counter_init = 60;
__g_save_Counter_to = null;
function __g_save_Counter(i)
{	
	clearTimeout(__g_save_Counter_to);
	if (i==0) {
		__g_save();
		return;
	}
	$('#gardien-editeur-save2').button( "option", "label", i);
	__g_save_Counter_to = setTimeout('__g_save_Counter('+(i-1)+')',1000);
}

function __g_save_Counter_stop()
{
	clearTimeout(__g_save_Counter_to);
}

function __g_save()
{
	clearTimeout(__g_save_Counter_to);
	$('#gardien-editeur-save').button( "disable" );
	$('#gardien-editeur-save2').button( "disable" );
	$('#gardien-editeur-save2').button( "option", "label", '<img src="'+img_dir+'/interface/wait3.gif"/>' );
	map.save('__g_save_restart()');
}
function __g_save_restart()
{
	clearTimeout(__g_save_Counter_to);
	$('#gardien-editeur-save').button( "enable" );
	$('#gardien-editeur-save2').button( "enable" );
	$('#gardien-editeur-save2').button( "option", "label", __g_save_Counter_init );
	
	if ($('#gardien-editeur-save2').is(':checked'))
		__g_save_Counter(__g_save_Counter_init);
}

function gSelectTileLib(e)
{
	e.parent().children().removeClass('selected');
	e.parent().parent().children().children().removeClass('selected');
	e.addClass('selected');

	if (map) map.libActionSelectedItem = e.attr('action');
}

function gLoadCouche(c) {
	// panneau de la libraire
	if (c==1) {
		$('#gardien-editeur-pensel').buttonset('enable');
		$('.ge_mobilier_wrapper').hide();
		$('.ge_lib_wrapper').hide();
		if ($('.ge_sol_wrapper').html()=='') {
			gLoadMapLib('sol');
		}
		$('.ge_sol_wrapper').show();
	} else if (c==2 || c==3) {
		$('#gardien-editeur-pensel').buttonset('enable');
		$('.ge_lib_wrapper').hide();
		$('.ge_sol_wrapper').hide();
		if ($('.ge_mobilier_wrapper').html()=='') {
			gLoadMapLib('statue');
		}
		$('.ge_mobilier_wrapper').show();
		//gLoadMapLib('mobilier');
	} else {
		$('#gardien-editeur-pensel').buttonset('disable');
		$('.ge_sol_wrapper').hide();
		$('.ge_mobilier_wrapper').hide();
		$('.ge_lib_wrapper').hide();
		$('.ge_lib_wrapper-'+c).show();
	}
	
	// carte
	$('#gardien-editeur-mapwrapper .map_layer').hide();
    if (c==1) {
    	$('div#map'+map.MapId+'_layer_'+c).show().css('opacity',1);
    } else if (c==2 || c==3 || c==4 || c==5 || c==6) {	
    	for(var i = 1;i < c;i++) {
    		$('div#map'+map.MapId+'_layer_'+i).show().css('opacity',(0.2+0.1*i));
    	}
    	$('div#map'+map.MapId+'_layer_'+c).show().css('opacity',1);
    } else if (c==8) {
    	$('#gardien-editeur-mapwrapper .map_layer').show().css('opacity',1);
    }
}

function gLoadMapLib(libname)
{
	var layerlId = 'gardien-editeur-libs-'+libname;
	var deja = ($("#"+layerlId).length==1);
	 
	// on ferme tout
	//$('.ge_layer_libs').hide();
	//$('.ge_layer_tree').hide();
	
	if (deja) {
		$('#'+layerlId).show().parent().parent().scrollTo($('#'+layerlId))
		$('gardien-editeur-tree-'+$('#'+layerlId).attr('data-type')).show();
				
	} else { // si pas encore créer, on construit (libs)

		// on récupére la librairie Js 
		if (_gaLib['sol'][libname])			{	lib = _gaLib['sol'][libname];		type='sol';}
		else if (_gaLib['mobilier'][libname])	{	lib = _gaLib['mobilier'][libname];	type='mobilier';}
		
		$('#ge_'+type+'_libs-wrapper').append('<div id="'+layerlId+'" class="ge_layer_libs" data-type="'+type+'"></div>');
		layerlId = '#'+layerlId;
	
		// chargement des images
		$(layerlId).html('<div style="color:#fff;" class="txtcenter"><b>'+lib['title']+'</b></div>');
		sel = true;
		for(var i in lib['image']) {
			var img = lib['image'][i];
			$(layerlId).append('<span'+((sel)?' class="selected"':'')+' style=\'background-image:url("'+img_dir+'/tiles/'+img+'");\' onclick="gSelectTileLib($(this))"></span>');
			if (sel) sel=false;
		}
		
		$(layerlId).parent().parent().scrollTo($(layerlId));
		
		// si pas encore créer, on construit (tree)
		var layertId = 'gardien-editeur-tree-'+type;
		var dejaa = ($("#"+layertId).length==1);
		
		if (dejaa) {
			$("#"+layertId).show();
		} else {
			$('#ge_'+type+'_tree-wrapper').append('<div id="'+layertId+'" class="ge_layer_tree"></div>');
			layertId = '#'+layertId;
			
			// chargement du l'arbre
			$(layertId).html('');
			for(var i in _gaLib[type]) {
				$(layertId).append('<span><a href="javascript:void(0);" onclick="gLoadMapLib(\''+i+'\')">'+_gaLib[type][i]['title']+'</a> ('+_gaLib[type][i]['image'].length+')</span>');
			}
		}


	
	}
	
}


function getClassNameCell(cn)
{
	cn = cn.split(" ");
	
	if(cn.length == 1)
	{
		if (cn[0].search(/sol/) != -1)
			return cn[0];
		else
			return null;
	}
		
	for(var c in cn)
	{
		if (cn[c].search(/sol/) != -1) {
			return cn[c];
		}
	}
}
function editorSelectLayer(l)
{
	// RAZ des calques
	for(var i=5; i>0; i--)
	{
		$('map_layer_'+i).hide();
		$('ES_Cell'+i).setOpacity(0.6);

		if ($('lib_layer_'+i) != null)
			$('lib_layer_'+i).hide();
	}	

	$('map_layer_'+l).show();
	$('ES_Cell'+l).setOpacity(1);

	if ($('lib_layer_'+l)!=null)
		$('lib_layer_'+l).show();

}


function getCurrentTool()
{
	return $('#gardien-editeur-toolbar input:radio[name="ge-pensel"]:checked').val();
	//return $('input:radio[name="tools"]:checked').val();
}


// SALLE DES YEUX
mapA = null;
$(document).ready(function(){
			$("#salle-yeux-salle-valid").button().click(function(){

					var layer_map = "salle-yeux-map-wrapper";
					var id_sel = "salle-yeux-salle-selector";
					var mapId = $("#"+id_sel).val();

					// RAZ					
					$("#"+layer_map).html('<br /><div class="txtcenter w60 center box pad1 ui-corner-all ui-widget-content"><img src="'+img_dir+'interface/wait3.gif" align="absmiddle"/> Chargement en cours...</div>');
					
					$.getJSON(Routing.generate('piece_getpieceinfo',{'id':mapId,'mode':'json'}) ).done( function(data){
							p = data;
							if (mapA != null) {
								mapA.destroy();
								delete mapA;
							}
							mapA = new Map(p.id, p.taillex, p.tailley);	
							mapA.displayMode = 'view';
							mapA.setInitialPosition('0', '0');
							mapA.createMapStructure('#'+layer_map);
							mapA.createMapElements(p.coucheSol, p.coucheSol2, p.coucheMobilier);
						});
					
				});
});


function dj_updateXp() {
	$.get(dir+'_ajax_query.php/donjon/getExperience').done( function(data){
		data = data.split(',');
		var level = parseInt(data[0]);
		var xpb = parseInt(data[1]);
		var xp = parseInt(data[2]);
		var xpmax = parseInt(data[3]);
		var xpreel = parseInt(data[4]);
		$( ".progressbar-XP" ).progressbar("option",{max:xpmax});
		$( ".progressbar-XP" ).progressbar("option",{value:xp});
		updateAffichageDonnee('data-donjon-EXPERIENCE',xpreel);
		updateAffichageDonnee('data-donjon-NIVEAU',level);
		updateAffichageDonneeNoVal('EXPERIENCE_NEXT_LEVEL');
	});
}


function donjon_setetat(form)
{
	var etat = $(form).find('select').val();
    $.post(Routing.generate('donjon_setetat'),{etat:etat}).done( function(data){
    	if (data==0)
    	{
    		set_error('Impossible de changer l\'état du donjon !');
    	}
    	else
    	{
    		$(form).parent().dialog("close");
            updateAffichageDonneeNoVal('ETAT');
    	}
    });  
    return false;
}

                
    $(document).bind('keydown', 'Shift+b', function(evt) {  gKeyLoadTabs('#bureau');   return false;  });
    $(document).bind('keydown', 'Shift+y', function(evt) {  gKeyLoadTabs('#yeux');   return false;  });
    $(document).bind('keydown', 'Shift+r', function(evt) {  gKeyLoadTabs('#economat');   return false;  });
    $(document).bind('keydown', 'Shift+e', function(evt) {  gKeyLoadTabs('#editeur');   return false;  });