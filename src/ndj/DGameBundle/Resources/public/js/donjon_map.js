//
// MANIPULATION DES CARTES DONJON
//
var Map = function(id, w, h) {
	
		// Variables
		this.posX = 0 ;
		this.posY = 0 ;
		
		this.MapId = makeid(3);

		this.idPIECE = id;
		
		// nombre de case lerg/heut
		this.NWidth  = (w==null) ? 10 : w ;
		this.NHeigth = (h==null) ? 10 : h ;
		
		this.sqrl = 32 ;
		
		this.lumiere = 10;
	
		this.innerIdMap = null;
		
		/**
		 * tableau contenant les actions de la carte
		 *
		 * format :
		 *	actions[y][x]['event'] = callback ;
		 */
		this.actions = new Array();
		
		// largeur/hauteur maximum du cadre contenant la carte
		this.MaxWidth = 640;
		this.MaxHeight = 480;	
		
		// position de la carte dans la zone de carte
		this.mapTop = 0;
		this.mapLeft = 0;
		
		// largeur/hauteur = hauteur d'un tile * nombre de tile 
		this.width  = parseInt(this.sqrl) * parseInt(this.NWidth);
		this.height = parseInt(this.sqrl) * (parseInt(this.NHeigth) +1); // +1 pour la ligne de tile du mur du haut
		
		// 'game' ou 'editor' ou 'view'
		this.displayMode = 'game';
		
		this.editorCurrentDraw = false;
		this.editorDrawInitPot = [];
		
		// liste des elemnts sur la carte (perso, pnj, objets)
		this.onMap = new Array();
		
		this.actionFirstLoad = true;
		this.libActionSelectedItem = 'I';
};

$.extend(Map.prototype, {
	
	destroy: function() {
		$(this.innerIdMap).empty();
		$('.carte-liste-element').empty();
		//delete this;
	},
	
	setInitialPosition: function(x, y) {
		this.posX = x ;
		this.posY = y ;
	},
	
	createMapStructure: function(innerId) {
		
			this.innerIdMap = innerId;
			
			this.MaxWidth = $(this.innerIdMap).innerWidth(); //640;
			this.MaxHeight = $(this.innerIdMap).innerHeight();//480;
			//console.log(this.MaxWidth+":"+this.MaxHeight)
			
			// creation des éléments pour la carte			
			$(this.innerIdMap).html(
			'<div id="map'+this.MapId+'">' +
				'<div id="map'+this.MapId+'_layer_l" class="map_layer map_layerl hide"></div>' +
				'<div id="map'+this.MapId+'_layer_6" class="map_layer map_layer6 hide"></div>' +
				'<div id="map'+this.MapId+'_layer_5" class="map_layer map_layer5 hide"></div>' +
				'<div id="map'+this.MapId+'_layer_4" class="map_layer map_layer4 hide"></div>' +
				'<div id="map'+this.MapId+'_layer_3" class="map_layer map_layer3 hide"></div>' +
				'<div id="map'+this.MapId+'_layer_2" class="map_layer map_layer2 hide"></div>' +
				'<div id="map'+this.MapId+'_layer_1" class="map_layer map_layer1 hide"></div>' +
			'</div>');
			
			// positionnement
			mapOverflow = 1;
			
			$(this.innerIdMap).css('overflow', (this.displayMode == 'game')?'hidden':'auto');
			
			iw = $(this.innerIdMap).width();
			ih = $(this.innerIdMap).height();
			it = $(this.innerIdMap).position().top;
			il = $(this.innerIdMap).position().left;

			if (this.displayMode=='editor') {
				w = this.MaxWidth;
				h = this.MaxHeight;
				if (ih > this.height)	this.mapTop = Math.abs(ih - this.height) / 2;
				if (iw > this.width)	this.mapLeft = Math.abs(iw - this.width) / 2;
			} else {
				w = (this.width > this.MaxWidth)? this.MaxWidth : this.width;
				h = (this.height > this.MaxHeight)? this.MaxHeight : this.height;
			}
			
			l = Math.abs(iw - w) / 2;
			t = Math.abs(ih - h) / 2;

			// correctif scrollbar
			//if (this.height > this.MaxHeight) { w +=20 ; if(w>this.MaxHeight)w=this.MaxHeight; }
			
			// calque de base
			$("div#map"+this.MapId).css({
				backgroundColor: '#000',
				backgroundImage:((this.displayMode == 'editor')?'url("'+img_dir+'/interface/stripe_01.png")':'none'), 
				position : 'absolute',
				overflow: ((this.displayMode == 'game')?'hidden':'auto'),
				top : t+"px",
				left : l+"px",
				//border: (this.displayMode == 'editor')?'1px solid #C0C0C0':'',
				width: w+"px",
				height: h+"px",
				maxWidth: this.MaxWidth+"px",
				maxHeight: this.MaxHeight+"px"
			});
			
			// couches de la carte 				
			for(var i=1; i<=6; i++)
			{
				$("div#map"+this.MapId+"_layer_"+i.toString()).css({
					position : 'absolute',
					//border: '2px solid red',
					//overflow : 'hidden',
					top : this.mapTop+"px",
					left : this.mapLeft+"px",
					width: this.width+"px",
					height: this.height+"px",
					zIndex: 200+10*(i+1),
					display: 'none'
				});
				
			}
			
			if (this.displayMode == 'game') {
				$("div#map"+this.MapId+"_layer_l").css({					
					position : 'absolute',
					top : this.mapTop+"px",
					left : this.mapLeft+"px",
					width: this.width+"px",
					height: this.height+"px",
					zIndex: 269,
					backgroundColor: '#000',
					opacity: 0
				});
			}
			
	},
	
	setContextMenu: function($trigger, e) {
		var map = (!mapA) ? map : mapA ;
		var pos = $trigger.attr('id').split('-l6d').pop().split('_');
		var x = pos[0];
		var y = pos[1];
		var action = map.actions[y][x];
		
		// menu en mode jeu
		if (map.displayMode=='game') {
			
			var o = {
    			callback: function(key, options) {
    				set_error('Choix "'+key+'" non traité !')
        		},
        		items: {
            		//"fouiller": {name: "Fouiller"}
            		//"sep1": "---------",
            		//"quit": {name: "Quit", icon: "quit"}
		        }
		    }
		    //for (var k in action['aventurier']) {
		    //	o.items[k] = action['menu'][k];
		    //}
		    
		    
		    // ajout des aventuriers
		    for(var k in action['aventurier']) {
		    	var id = k.split(':').shift();
		    	if ($('#aventurier-imgtile-'+id).hasClass('aventurier-imgtile-joueur'))
		    		continue;
		    	var nom = action['aventurier'][k];
		    	var key = k;
		    	o.items[k] = {
		    			name:nom,
		    			"items": {
		    				"fiche": {"name":"Fiche",callback:function(k,o){ displayFiche('aventurier',id); },icon:"fiche"},
		    				"attac": {"name":"Attaquer", callback:function(k,o){ av_attaquer(key);},icon:"attack"},
		    				"parler": {"name":"Parler",callback:function(k,o){ chat_msg_prive(nom); }},
		    				"askfor": {"name":"Demander...",
		    						"items":{	"proche":{name:"en proche"},
		    									"ami":{name:"en ami"},
		    									"amour":{name:"en amour"},
		    									"ennemi":{name:"en ennemi"},
		    									"maitre":{name:"un apprentissage"},
		    									"creancier":{name:"de l'argent"}
		    						}}
                    	}
		    		};
		    }
		    
		    // ajout des bestaire
		    for(var k in action['bestiaire']) {
		    	var id = k.split(':').shift(); 
		    	var nom = action['bestiaire'][k];
		    	var key = k;
		    	o.items[k] = {
		    		name:nom,
		    		"items": {
		    				"fiche": {"name":"Fiche",callback:function(k,o){ displayFiche('bestiaire',id); },icon:"fiche"},
		    				"attac": {"name":"Attaquer", callback:function(k,o){ av_attaquer(key);},icon:"attack"}
                    	}
		    	};
		    }
		    // ajout des inventaires
		    for(var k in action['inventaire']) {
		    	var id = k.split(':').shift(); 
		    	var nom = action['inventaire'][k];
		    	var key = k;
		    	o.items[k] = {
		    		name:nom,
		    		"items": {
		    				"fiche": {"name":"Fiche",callback:function(k,o){ displayFiche('inventaire',id); },icon:"fiche"}
		    				, "take": {"name":"Ramasser",callback:function(k,o){ av_ramasser_inv(id); },icon:"take"}
		    				//, "hide": {"name":"Cacher"}
                    	}
		    	};
		    }
		    
		    if (action['PO']!=null) {
		    	o.items['PO'] =  {"name":"Prendre ("+action['PO']+" PO)",callback:function(k,o){  Av_Action_PO(x,y,action['PO']); },icon:"take"};
		    }
		    
            o.items["infos"] = {name: "Infos",icon:"aide"};
		}
		
		// menu en mode aperçu
		else if (map.displayMode=='view') {
			var action = mapA.actions[y][x];
			
			var o = {
    			callback: function(key, options) {
    				switch(key) {
    					case 'or' :
    						break;
    						
    					default:
    						var k = key.split(',');
    						var x=k[0]; var y=k[1]; var a=k[2];
    						switch(a) {
    							case 'PO': Av_Action_PO(x,y,k[3]); break;
    						}
    						break;
    				}
            		//alert(key); 
        		},
        		items: {
            		//"fouiller": {name: "Fouiller", callback:function(k,o){ set_error('Cette action n\'est pas encore faisable') } },
            		//"cut": {name: "Cut", icon: "cut"},
            		//"copy": {name: "Copy", icon: "copy"},
            		//"paste": {name: "Paste", icon: "paste"},
            		//"delete": {name: "Delete", icon: "delete"},
            		//"sep1": "---------",
            		//"quit": {name: "Quit", icon: "quit"}
		        }
		    }
		    for (var k in action['menu']) {
		    	o.items[k] = action['menu'][k];
		    }
		}
		
		// menu en mode editeur
		else if (map.displayMode=='editor') {
			var o = {
    			callback: function(key, options) {
            		//alert(key); 
        		},
        		items: {
            		"quit": {name: "Quit", icon: "quit"}
		        }
		    }
		}
		

		return o;
	},
                
	createMapElements : function(tileSet1, tileSet2, tileSet3) {
		// creation de la couche décors de fond
		this.setLayer(1, tileSet1);
		// création de la couche décors supérieurs
		this.setLayer(2, tileSet2);
		// création de la couche decors "mobilier"
		this.setLayer(3, tileSet3);
		// création de la couche Objets
		this.setLayer(4);
		// création de la couche personnages
		this.setLayer(5);
		// création de la couche Actions
		this.setLayer(6);
		
		// load objets & personnage
		this.build();
		
		var map = (mapA)?mapA:this; 
		
			// menu contextuel			
			$(function(){
	    		$.contextMenu({
        			selector: '#map'+mapA.MapId+'_layer_6 table.epiece td',
        			build: mapA.setContextMenu
    			});
			});
		//var m = this;
		//$(document).ready(function() {
			//if (m.displayMode=='editor') m.fadeIn();
		//});
	},
	
	fadeIn: function() {
		for(var i=1;i<=6;i++) {
			$("div#map"+this.MapId+"_layer_"+i.toString()).fadeIn(1000);
		}
	},
	
	build: function() {
		this.buildActionsArray();
		this.loadObjetEtMonstre();
	},
	
	setLayer: function(layerNum, tileSetString) {
		
		isTileSet = (tileSetString!=null && tileSetString!=undefined);
		
		if(isTileSet && layerNum==1)
			tileSet = this.tileSetStringToArray(tileSetString);
		if(isTileSet && (layerNum==2 || layerNum==3))
			tileSet = this.tileMobilierStringToArray(tileSetString);
			
		var layer = $("div#map"+this.MapId+"_layer_"+layerNum.toString());
		
		// creation de la grille
		var gird = '<table class="epiece">';
		
		for(var y = 0; y < this.NHeigth; y++) {
			gird += '<tr>';
			// coordonnées
			/*
			if (y==0) {				
				gird += '<th></th>';
				for(var i=0;i < this.NWidth;i++)
					gird += '<th>'+i+'</th>';
				gird += '</tr><tr>';
			}
			gird += '<th>'+y+'</th>';
			*/
			for(var x = 0; x < this.NWidth; x++) {
				bonus = '';
				if (isTileSet && tileSet[y][x]!=null) {
					bonus += ' style="background:no-repeat url(\''+img_dir+'tiles/t'+tileSet[y][x]+'.png\');"';
				}
				if (this.displayMode=='editor') {
					bonus += ' class="border1gray" ';
				}
				gird += '<td id="'+this.MapId+'-l'+layerNum+'d'+x+'_'+y+'" '+bonus+' title="('+x+','+y+')" map-action=""></td>';
			}
			gird += '</tr>';
		}
		gird += '</table>';
		
		layer.html(gird);
		
		if (this.displayMode=='editor') {
			var oMap = this;
			
			if (layerNum==2 || layerNum==3) {
				$('td[id^="'+this.MapId+'-l'+layerNum+'d"]').dblclick(function(){
					$(this).css({backgroundImage: '' });					
					//oMap.save();
				});
			}
			if (layerNum==1 || layerNum==2 || layerNum==3 || layerNum==6) {
	
				$('td[id^="'+this.MapId+'-l'+layerNum+'d"]').click(function(){
					this.oMap = oMap;
					e = $('#gardien-editeur-sidepanel-libs .ge_layer_libs span.selected:visible');
					
					tool = (layerNum==6)?e.attr('tool'):getCurrentTool();
					
					// pinceau
					if (tool == 'p') {
						if (layerNum!=6) {
							$(this).css({backgroundImage: e.css('background-image') });
						} else {
							oMap.setAction($(this), e);
						}
					
					// rectangle
					} else if (tool =='r') {
						if (oMap.editorCurrentDraw) {
							if (layerNum!=6) {
								$("td.gEPtempTile").css({backgroundImage: e.css('background-image') });
							} else {
								oMap.setAction($("td.gEPtempTile"), e);
							}
							oMap.stopNewTile();
						} else {
							var idTile = $(this).attr('id');
							//format ex: VRV-l1d11_9
							var posTile = idTile.substring(7).split("_");
							var l = idTile.substring(5,1);
							oMap.addTileToGrid(l, posTile[0], posTile[1]);
						}
					}
				});
				$('td[id^="'+this.MapId+'-l'+layerNum+'d"]').mouseover(function() {
					if (oMap.editorCurrentDraw) {
						this.oMap = oMap;
						var idTile = $(this).attr('id');
						var posTile = idTile.substring(7).split("_");
						var l = idTile.substring(5,6);
						oMap.setSquareNewTiles(l, oMap.editorDrawInitPot[0], oMap.editorDrawInitPot[1], posTile[0], posTile[1] );
					}	
				});
			}
		}
		
		
	},
	
	// editeur d'action : traiter lorsque l'editeur ajoute une action à partir de la carte directement
	setAction: function (cells, action) {
		
		oMap = this;
		a = action.attr('action');
		switch(a) {
			case 'W' : case 'V' : case 'I': case 'EM': case 'ED' : case 'Z': case 'S': case 'F':
				var data = new Array();
				cells.each(function (){
					data.push($(this).attr('id').split('-l6d').pop());
				});
				data = a+'-'+data.join('-');
				loadInLayer(Routing.generate('piece_saveactions',{'id':oMap.idPIECE,'actions':data}),'#gardien-editeur-sidepanel-libs .ge_lib_wrapper-6')
				oMap.build();
				break;
			case 'PO' :
				var coord = cells.attr('id').split('-l6d').pop().split('_');
				var somme = prompt('Quel montant veux-tu déposer en '+coord[0]+','+coord[1]+' ?');
				if (somme!=null && somme!=undefined) {
					deposerPo(oMap.idPIECE,coord[0],coord[1],somme);
					loadInLayer(Routing.generate('piece_interfaceediteurpieceactionlibs',{'id':oMap.idPIECE}),'#gardien-editeur-sidepanel-libs .ge_lib_wrapper-6');
					// loadInLayer('donjon/editeurLibrairieActionLib?idPIECE='+oMap.idPIECE+'&a='+data,'#gardien-editeur-sidepanel-libs .ge_lib_wrapper-6')
					oMap.build();
				}
				break;
				
			case 'P' : case 'M' :case 'MS' : case 'D':
				var coord = cells.attr('id').split('-l6d').pop().split('_');
				loadInLayer(Routing.generate('piece_actionform',{'id':oMap.idPIECE,'type':a,'x':coord[0],'y':coord[1]}), "#dialogLibNewAction");
				
				$("#dialogLibNewAction").dialog({
					modal:true,
					closeOnEscape:true,
					title:"Nouvelle action",
					width:500,
					resizable:false
				});
				break;
		}
	},
	
	// retourne le $ cellule x,y de la couche l
	getCell: function (l,x,y) {
		return $('#'+this.MapId+'-l'+l+'d'+x+'_'+y);
	},
	
	buildActionsArray: function() {
		//
		// /!\ TODO lors d'un build appellé pour un rafraichissement,
		// ne pas refaire le tableau pour aventurier/inventaire/bestiaire.
		// seulement pour les PO
		//
		
		// construction du tableau des actions
		for(var y = 0; y < this.NHeigth; y++) {
			if (this.actionFirstLoad) this.actions[y] = new Array();
			for(var x = 0; x < this.NWidth; x++) {
				if (this.actionFirstLoad) this.actions[y][x] = new Array();
				this.actions[y][x]['onClick'] 		= "" ;	// lorsque l'on click sur la case
				this.actions[y][x]['onDblClick'] 	= "" ;	// lorsque l'on double click sur la case
				this.actions[y][x]['beforeMove'] 	= null ;	// avant d'arriver la case
				this.actions[y][x]['onMove'] 		= null ;	// lorsque l'on arrive sur la case
				this.actions[y][x]['onOut'] 		= null ;	// lorsque l'on quitte la case
				this.actions[y][x]['PO'] 			= null ;	// lorsque l'on quitte la case
				
				if (this.actionFirstLoad) {
					this.actions[y][x]['menu']			= new Array() ; // menu contextuelle (special)
					this.actions[y][x]['aventurier']	= new Array() ; // aventurier présent
					this.actions[y][x]['inventaire']	= new Array() ; // inventaire présent
					this.actions[y][x]['bestiaire']	= new Array() ; // bestiaire présent
					//this.actions[y][x]['strings'] 		= "" ;	// backup des actions
				}
			}
		}
		this.actionFirstLoad = false;
	},	
	
	
	getTypeFromObject: function(o) {
		if (o.prenom!=undefined && o.prenom != null) return 'bestiaire';
		if (o.pact!=undefined && o.pact != null) return 'aventurier';
		if (o.qualite!=undefined && o.qualite != null) return 'inventaire';
		else return null;
	},
	
	// chargement des objets et monstre de la carte
	loadObjetEtMonstre: function() {
		//var map = this;		
		// todo : nouveau, départ, mouvement, statique
		if(!mapA || mapA==null || mapA==undefined) mapA=this;
		start_loading();
		var temp = new Array();
		var liste = new Array();
		$.getJSON(Routing.generate('gamecommon_maploadobjetmonstre',{'id':mapA.idPIECE})).done( function(data){
			for(var i=0; i < data.length; i++)
			{
				var d=data[i];
				
				p = d.position.substring(1,d.position.length-1).split(',');
				mode = mapA.getTypeFromObject(d);
				id=d.id;
				key=d.id+':';
				x = p[0];	y=p[1];
				if (mode=='aventurier') 	 {
					key=key+'a'; layer=5;
					src= (d.image=='null') ?img_dir+'/creatures/aventurier.gif': dir+d.image;
				} else if (mode=='bestiaire') {
					key=key+'b';   layer=5;
					src= (d.image=='null') ?img_dir+'/creatures/monstre.gif': dir+d.image;
				} else if (mode=='inventaire'){
					key=key+'i'; layer=4;
					src= (d.image=='null') ?img_dir+'/objets/livre_0.png': dir+d.image;
				}
				liste.push(d);
				
				obj = $('img#'+mode+'-imgtile-'+id);
				
				complement = '';	plus = '';
				if (d.joueur!=undefined && d.joueur=='1') { complement=' aventurier-imgtile-joueur'; }
				else { plus += ' ondblclick="displayFiche(\''+mode+'\','+id+')"'; }
				img = '<img src="'+src+'" id="'+mode+'-imgtile-'+id+'" class="'+mode+'-imgtile'+complement+'"'+plus+'/>';
				cell = mapA.getCell(layer,x,y);
				if (obj.length==1) { // est déjà présent
					if (obj.parent().attr('id')!=cell.attr('id')) {
						// a bougé
						//console.log(key+" a bougé");
						var apos = obj.parent().attr('id').split('-l'+layer+'d').pop().split('_');
						delete mapA.actions[apos[1]][apos[0]][mode][key]; // = array_minus_key(mapA.actions[apos[1]][apos[0]][mode], key );
						mapA.actions[y][x][mode][key] = d.nom; 
						//
						obj.remove();
						cell.html(img);
					}
				} else { //  nouveau sur la carte
					//console.log(key+" arrive");
					cell.html(img);
					mapA.onMap.push(key);
					mapA.actions[y][x][mode][key] = d.nom;
				}
				temp.push(key);
			}
			
			// on supprimer les éléments qui ont quitté la carte
			var temp2 = array_minus(mapA.onMap, temp);
			mapA.onMap = temp;
			for(var i in temp2) {
				console.log(temp2[i]+' nous quitte')
				var o = temp2[i].split(':');
				var layer = 5;
				if (o[1]=='a') mode = 'aventurier';
				else if (o[1]=='i') { mode = 'inventaire'; layer=4; }
				else if (o[1]=='b') mode = 'bestiaire';
				
				if ($('img#'+mode+'-imgtile-'+o[0]).length>0) {
					var pos = $('img#'+mode+'-imgtile-'+o[0]).parent().attr('id').split('-l'+layer+'d').pop().split('_');
				
					delete mapA.actions[pos[1]][pos[0]][mode][temp2[i]];
					//	mapA.actions[y][x][mode] = array_minus_key(mapA.actions[y][x][mode], key );
				}
				
				$('img#'+mode+'-imgtile-'+o[0]).remove();
			}
			
			// on met à jour le tableau
			var t = $('.carte-liste-element');
			if (mapA.displayMode != 'editor' && t.length > 0) {
				var c = '<ul>';
				for(var i in liste)
				{
					var o = liste[i];
					var complement_css = '';
					var complement = '';
					
					if (o.joueur!=undefined && o.joueur==1)
						continue;
					
					p = o.position.substring(1,o.position.length-1).split(',');

					mode = mapA.getTypeFromObject(o);
					var id = o.id;
					x = p[0];	y=p[1];
					if (mode=='aventurier') {
						src= (o.image=='null') ?img_dir+'/creatures/aventurier.gif': dir+o.image;
						complement += ' <button onclick=\'chat_msg_prive("'+o.nom+'")\'><span class="ui-icon ui-icon-comment"></span></button>';
						if (mapA.displayMode=='game') complement += ' <button onclick="av_attaquer(\''+o.id+':a\')"><img width="16" src="'+img_dir+'/gicon/attack.png"  /></button>';
					} else if (mode=='inventaire') {
						src= (o.image=='null') ?img_dir+'/objets/livre_0.png': dir+o.image;
						complement_css = 'inventaire';
						if (mapA.displayMode=='game') complement += ' <button onclick="av_ramasser_inv('+o.id+')"><img width="16" src="'+img_dir+'/gicon/take.png"  /></button>';
						if (mapA.displayMode=='view') complement += ' <button onclick="dj_ramasser_inv('+o.id+')"><img width="16" src="'+img_dir+'/gicon/take.png"  /></button>';
					} else if (mode=='bestiaire') {
						src= (o.image=='null') ?img_dir+'/creatures/monstre.gif': dir+o.image;
						if (mapA.displayMode=='game') complement += ' <button onclick="av_attaquer(\''+o.id+':b\')"><img width="16" src="'+img_dir+'/gicon/attack.png"  /></button>';
					}
					c=c+'<li class="description '+mode+'">';
					c=c+'<img align="absmiddle" title="('+x+','+y+')" onmouseover="clignote(\'#'+mode+'-imgtile-'+id+'\')"  src="'+src+'" height="20"> '+o.nom;
					c=c+' <button onclick="displayFiche(\''+mode+'\','+id+')"><img width="16" src="'+img_dir+'/gicon/fiche.png"  /></button>';
					c=c+complement
					c=c+'</li>';
				}
				c = c+'</lu>';
				t.html(c);
			}
					
			if (mapA.displayMode=='game')
				updateAffichageDonneeNoVal('POSITION');
			
			mapA.loadActions();
		}).fail(function(err){ set_error("Erreur lors du chargement des objets et personnages (actualiser la page ?)") })
                .always(function(){  stop_loading(); });
	},
	
	loadActions: function() {
		//if(!mapA || mapA==null || mapA==undefined) mapA=this;
		var mapA=this;
		start_loading();
		$.getJSON(Routing.generate('gamecommon_maploadactions',{'id':mapA.idPIECE}) ).done( function(data){
			
			//if (mapA.displayMode=='editor')
			 $("div#map"+mapA.MapId+"_layer_6 td").empty();
			
			for(var i=0; i < data.length;i++)
			{
				d = data[i].split(',');
				x = d[0];
				y = d[1];
				action = d[2];
				switch (action)
				{
					// PO {X,Y,PO,5} : 5 PO
					case 'PO' :
						mapA.actions[y][x]['PO'] = d[3];
						//mapA.actions[y][x]['menu'][data[i]] =  {name: "Ramasser l'or ("+d[3]+"PO)" };
						if (mapA.displayMode!='editor')
							mapA.getCell(4,x,y).html('<img src="'+img_dir+'/interface/actions/gold_01.png" width="50%" height="50%"/>');
						break;

					// Case interdit {X,Y,I} : Case interdit
					case 'I' : case 'W' : case 'V' :
						mapA.actions[y][x]['beforeMove'] = "false";
						break;

					// Case de sortie {X,Y,S} : Case de sortie du donjon
					case 'S' :
						mapA.actions[y][x]['beforeMove'] = "quitterDonjon();";
						if (mapA.displayMode!='editor') {
							mapA.getCell(6,x,y).css({backgroundColor:'green', opacity:0.5}).attr('title','Sortie de secours !');
							mapA.animateLoopOpacity(6,x,y);
						}
						break;

					// Case de fin {X,Y,F} : Case de fin du donjon
					case 'F' :
						mapA.actions[y][x]['beforeMove'] = "Av_Action_F()";
						if (mapA.displayMode!='editor') {
							mapA.getCell(6,x,y).css({backgroundColor:'yellow', opacity:0.5}).attr('title','Fin du donjon :-)');
							mapA.animateLoopOpacity(6,x,y);
						}
						break;

					// Escalier qui monte/descend {X,Y,EM/ED} : Escalier qui monte/descend
					case 'EM' : case 'ED' :
						mapA.actions[y][x]['beforeMove'] = "Av_Action_"+action+"()";
						break;

					// Passage secret {X,Y,P,idP,X,Y} : passage secret vers piece#idP en X,Y
					case 'P' :
						mapA.actions[y][x]['beforeMove'] = "Av_Action_P("+d[3]+","+d[4]+","+d[5]+")";
						//mapA.actions[y][x]['onMove'] = "Av_Action_P("+d[3]+","+d[4]+","+d[5]+")";
						break;

					// Message {X,Y,M,"Message"} : affiche le message "Message"
					case 'M' :
						mapA.actions[y][x]['onMove'] = "alert(\""+d[3]+"\")";
						break;
						
					// Message {X,Y,MS,"Message"} : affiche le message "Message" au survol de la sourie
					case 'MS' :
						mapA.getCell(6,x,y).mouseover(function(){ alert(d[3]); });
						break;
					
					// Message {X,Y,M,"Message"} : affiche le message "Message"
					case 'D' :
						mapA.actions[y][x]['onMove'] = "Av_Action_D()";
						break;
				
				}
				if (mapA.displayMode=='editor') {
					// #lib-action-item-I-11-13
					idLine = '\'#lib-action-item-'+action+'-'+x+'-'+y+'\'';
					txt = '<img src="'+img_dir+'/interface/actions/'+action+'.png" width="16" height="16" border="0"/>';
					item = '<span id="onmap-action-item-'+action+'-'+x+'-'+y+'"'
						+ ' class="pointer"'
						+ ' onmouseover="clignote($('+idLine+'));"'
						+' onclick="$(\'#gardien-editeur-sidepanel-libs\').scrollTo($('+idLine+'));clignote($('+idLine+'));"'
						+' ondblclick="confirm(\'Supprimer?\')'
							+' && loadInLayer(Routing.generate(\'piece_unsetaction\',{\'id\':'+mapA.idPIECE+',\'x\':'+x+',\'y\':'+y+',\'a\':\''+action+'\'}),'
								+' \'#gardien-editeur-sidepanel-libs .ge_lib_wrapper-6\')"'
						+'>'
						+txt
						+'</span> ';
					mapA.getCell(6,x,y).append(item);
				}
						
			}

			if (mapA.displayMode=='game') {
				var pos = $('.aventurier-imgtile-joueur').parent().attr('id').split('-l5d').pop().split("_");

				var scrollX = pos[0] * 32 - mapA.MaxWidth/2;
				var scrollY = pos[1] * 32 - mapA.MaxHeight/2;
				//console.log(scrollX+","+scrollX)
				//console.log(mapA.MaxWidth+","+mapA.MaxHeight)
				$(document).ready(function() {
					$('#map'+mapA.MapId).delay(100).scrollTo({top:scrollY+"px",left:scrollX+"px"},1000,{easing:'easeInOutCubic'});
				});
			}
			
			if (mapA.displayMode!='editor') {
				$(document).ready(function(){
					$("div#map"+mapA.MapId+"_layer_l").show().animate({opacity : (1 - mapA.lumiere/10) });
					mapA.fadeIn();
				});
			}
				
			
		}).fail(function(err){ set_error("Etteur lors du chargement des actions (actualiser la page ?).") })
                .always(function(){ stop_loading(); });
	},
	
	animateLoopOpacity: function(l,x,y) {
		map = this;
		map.getCell(l,x,y)
			.animate({opacity:0.1},3000)
			.delay(3000)
			.animate({opacity:0.5},3000,"linear", function() { map.animateLoopOpacity(l,x,y) });
	},
	
	beforeMove: function (x, y) {
		var a = this.actions[y][x]['beforeMove']; 
		if (a==null) {
			return true;
		} else {
			return eval(a);
		}
	},
	
	onMove: function (x, y) {
		var a = this.actions[y][x]['onMove'];
		if (a==null) {
			return true;
		} else {
			return eval(a);
		}
	},
	
	onOut: function (x, y) {
		var a = this.actions[y][x]['onOut'];
		if (a==null) {
			return true;
		} else {
			return eval(a);
		}
	},
	
	onClick: function (x, y) {
		var a = this.actions[y][x]['onClick']; 
		if (a==null) {
			return true;
		} else {
			return eval(a);
		}
	},
	
	tileSetStringToArray: function(ts) {
		// on retire [ et ] du début et de la fin
		ts = ts.substr(1,ts.length-2);
		// on créer les lignes
		ts = ts.split('][');
		// ... puis les colonnes
		for(var i=ts.length-1; i>=0; i--) {
			ts[i] = ts[i].split(',');
		}
		return ts;		
	},
	
	tileMobilierStringToArray: function(ts) {
		
		tab = [];		
		for(var y = 0; y < this.NHeigth; y++)	{
			tab[y] = []
			for(var x = 0; x < this.NWidth; x++)	{
				tab[y][x] = null;
			}
		}
		if (ts!=null && ts!=undefined && ts.replace(/ /,"")!='')
		{
			// on retire [ et ] du début et de la fin
			ts = ts.substr(1,ts.length-2);
			// on coupe pour le parcours des objets du décors 
			ts = ts.split('][');
			// on parcours les objets
			for(var i=ts.length-1; i>=0; i--) {
				var tmp = ts[i].split(',');
				tab[tmp[1]][tmp[0]] = tmp[2];
			}
		}
		
		return tab;	
	},
	
	save: function(_call)
	{
		var sol = this.getSolString();
		var sol2 = this.getMobilierString(2);
		var mobilier = this.getMobilierString(3);
		//console.log("sol : "+sol)
		//console.log("sol2 : "+sol2)
		//console.log("mobilier : "+mobilier)
		var route = Routing.generate('piece_save',{'id':this.idPIECE,'sol':sol, 'sol2':sol2, 'mobilier':mobilier});
		//console.log(route);
		//
		start_loading();
		$.post(route).done( function(txt){
			if(txt!='ok') { set_error(txt); }
			if (_call!=undefined && _call!=null) { eval(_call); }
		})
                  .fail(function (err){ set_error("Erreur lors de la sauvegarde de la carte !") })
                 .always(function(){ stop_loading(); });
	},
	
	getMobilierString: function(l){
		str = '';
		for(var y=0; y < this.NHeigth; y++)
		{
			for(var x=0; x < this.NWidth; x++)
			{
				elem = this.getTileNumFromUrl($('#'+this.MapId+'-l'+l+'d'+x+'_'+y).css('background-image'));
				if (elem != null)
				{
					str += "["+x+","+y+","+elem+"]";
				}
			}
		}
		return str;
	},	

	getSolString: function() {
		str = '';
		for(var y=0; y < this.NHeigth; y++)
		{
			str += "["; 
			limit_fix_x = this.NWidth-1;
			for(var x=0; x < this.NWidth; x++)
			{
				elem = this.getTileNumFromUrl($('#'+this.MapId+'-l1d'+x+'_'+y).css('background-image'));
				if (elem == null) {
					elem = 'null';
				}
				str += elem;
				if (x < limit_fix_x) str += ',';
			}
			str += "]";
		}
		return str;
	},
	
	getTileNumFromUrl: function(url) {
		console.log('map.getTileNumFromUrl("'+url+'")');
		if (url==null || url==undefined || url=='') return null;
		var reg = /(\/.*)+\/t([0-9]+)\.png/;
		var trouve = url.split(reg);
		var tileNum = (trouve != null) ? trouve[2] : null;
		return tileNum;
	},
	
	// EDITOR DRAG
	addTileToGrid: function(l, initX, initY)
    {    		
    		this.editorCurrentDraw = true;    		
			this.editorDrawInitPot = [initX, initY];
			this.setSquareNewTiles(l, initX, initY, initX, initY);
    },
    
	setSquareNewTiles: function(l, xi,yi,x,y)
	{
		if (this.editorCurrentDraw)
		{
			xi = parseInt(xi);
			yi = parseInt(yi);
			x = parseInt(x);
			y = parseInt(y);
			$("td.gEPtempTile").removeClass('gEPtempTile');
			for(i = xi; i<=x; i++) {
				for(j = yi; j<=y; j++) {
					$("td#"+this.MapId+"-l"+l+"d"+i+"_"+j).addClass('gEPtempTile');
				}
			}
		}
	},
	
	stopNewTile: function()
	{
		$("td.gEPtempTile").removeClass('gEPtempTile');
		this.editorCurrentDraw = false;
		this.editorDrawInitPot = [];
	}

});


