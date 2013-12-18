/*
 * dGame
 */

//prédeclaration
var gKeyLoadTabs = function(s){};
var aventurier_showpan = function(s){};

/**
 * @argument {String} sep séparateur
 * @return {Array}
 */
String.prototype.explodex = function(sep) {
    return this.substr(1, this.length - 2).split(sep);
};

/**
 * Vérifie l'identité de deux objets
 * @author http://stackoverflow.com/a/2736070
 * @param {Object} o
 * @param {Object} x
 * @returns {Boolean}
 */
objectEquals = function(o, x){
    for (var p in o) {
        if (typeof(o[p]) !== typeof(x[p]))
            return false;
        if ((o[p] === null) !== (x[p] === null))
            return false;
        switch (typeof(o[p])) {
            case 'undefined':
                if (typeof(x[p]) != 'undefined')
                    return false;
                break;
            case 'object':
                if (o[p] !== null && x[p] !== null && (o[p].constructor.toString() !== x[p].constructor.toString() || !objectEquals(o[p],x[p])))
                    return false;
                break;
            case 'function':
                if (p != 'equals' && o[p].toString() != x[p].toString())
                    return false;
                break;
            default:
                if (o[p] !== x[p])
                    return false;
        }
    }
    return true;
};

/**
 * Fait le différence entre deux tableaux (retire du tablreau courant les éléments présents dans le tableau {a})
 * @param {Array} a
 * @param {Boolean} strict
 * @returns {Array}
 */
Array.prototype.diffo = function(a, strict) {
    if (typeof(strict) != "boolean")
        strict = false;
    var res = [];
    for (var i = 0; i < this.length; i++) {
        var fill = true;
        for (var j = 0; j < a.length; j++) {
            if ((strict && objectEquals(a[j], this[i])) || (!strict && a[j].id == this[i].id)) {
                fill = false;
                break;
            }
        }
        if (fill) res.push(this[i]);
    }
    return res;
};


(function($) {
    // conversion d'une chaine en array pour une couche
    var tileSetStringToArray = function(ts) {
        // on retire [ et ] du début et de la fin et on créer les lignes
        ts = ts.explodex('][');
        // ... puis les colonnes
        for (var i = ts.length - 1; i >= 0; i--) {
            ts[i] = ts[i].split(',');
        }
        return ts;
    };
    
    // conversion d'une chaine en array pour une couche mobilier
    var tileMobilierStringToArray = function(ts, w, h) {
        var tab = [];
        for (var y = 0; y < h; y++) {
            tab[y] = []
            for (var x = 0; x < w; x++) {
                tab[y][x] = null;
            }
        }
        if (ts != null && ts != undefined && ts.replace(/ /, "") != '')
        {
            // on retire [ et ] du début et de la fin et on coupe pour le parcours des objets du décors
            ts = ts.substr(1, ts.length - 2).split('][');
            // on parcours les objets
            for (var i = ts.length - 1; i >= 0; i--) {
                var tmp = ts[i].split(',');
                tab[tmp[1]][tmp[0]] = tmp[2];
            }
        }
        return tab;
    };
    
    $.extend({dGame: {
            data: {
                mode:null, // mode de jeu (aventurier / donjon)
                joueur: null, // joueur courant
                
                maps: [], // liste des cartes du jeu

                rang: [0, 10, 50, 100, 250, 500] // niveau des rangs
            },
            ingame:function(){
              return window.location.href.match(/game\/game_/);  
            },
            init:function(){
                if (typeof(modeDeJeu)!=='undefined') {
                    if (modeDeJeu === 'a') {
                        this.data.mode = 'aventurier';
                    } else if (modeDeJeu === 'd') {
                        this.data.mode = 'donjon';
                    }  
                }
                this.jsobserver.init();
            },
                    
            /**
             * OBSERVER
             * 
             * 1) Utilisation :
             *  tag html avec l'attribut data-source ayant pour valeur l'uri pour la mise à jour
             *  Exemple : <div id="myid" class="..." data-source="even.namespace">Some text...</div>
             *  
             * 2) Strucutures des observations :
             *      objet_#-attribut
             *      
             * 3) NOTES:
             * Objets à observer :
             *      aventurier, inventaire, piece (actions notamment), bestiaire, commerce, chat
             * 
             */
            jsobserver: {
                data: {
                  timer:null, // objet timer
                  delay:2000, // delay de maj de jos
                  tempon:[], // queue d'envoie
                  lock:false // vérou d'envoie
                },
                _lock: function() {
                    if (this.data.lock) {
                        return false;
                    } else {
                        this.data.lock = true;
                        return true;
                    }
                },
                _unlock: function() {
                    if (this.data.lock) {
                        this.data.lock = false;
                    } else {
                        throw "Unable to unlock an unlocked lock ! ;)";
                    }
                },
                // initialisation
                init: function() {
                    console.log("jsobserver.init");
                    // on vérifie qu'il est en jeu
                    if ($.dGame.ingame()) {
                        var i = 0;
                        $('[data-source]').each(function() {
                            i++;
                            $.dGame.jsobserver.addItem( $(this) );
                        });
                        console.log(i + " data-loaders.");

                        // set timer
                        this.data.timer = setInterval(function(){ $.dGame.jsobserver.transaction() }, this.data.delay);
                    }
                    return this;
                },
                // ajout une action à envoyer 
                queue: function(o, force) {
                    if (!this._lock()) {
                        setTimeout(function(){ $.dGame.jsobserver.queue(o, force); }, 300);
                        return this;
                    }
                    // { code:"char_reload.tagada", data:{data} }
                    this.data.tempon.push(o);
                    this._unlock();

                    if (force == true) {
                        return this.transaction();
                    }
                    
                    return this;
                },
                // Fonction timer de jsobserver (maj régulière toute les 2 sec.)
                _timer: function() {
                    //clearTimeout(this.data.timer);
                    this.transaction()
                    //this.data.timer = setTimeout('$.dGame.jsobserver._timer()', this.data.delay);

                    return this;
                },
                // Effectue une transaction : envoie de la queue et reception des evenements        
                transaction: function() {
                    if (!this._lock()) {
                        clearInterval(this.data.timer);
                        setTimeout(function() {  $.dGame.jsobserver.transaction(); }, 450);
                        return this;
                    }
                    // var queue = this.data.tempon.serialize();
                    // var queue = $.param(this.data.tempon, false);
                    //var queue = $.parseJSON(this.data.tempon);
                    var queue = JSON.stringify(this.data.tempon);
                    var jso = this;
                    $.getJSON(Routing.generate("jso_transaction", {q: queue}))
                    .always(function() {
                        // re-set interval si clear
                        if (jso.data.timer == null || jso.data.timer == undefined) {
                            jso.data.timer = setInterval(function() { jso.transaction(); }, jso.data.delay);
                        }
                        jso._unlock();
                    }).fail(set_error)
                    //function(err) {
                    //    set_error($(err.responseText).find('h1').html());
                    //})
                    .done(function(res) {
                        jso.data.tempon = [];
                        if (typeof(res) !== 'undefined' && typeof(res) !== 'null' && res != '') {
                            for (var i = 0; i < res.length; i++) {
                                // console.log("jso.trigger : "+res[i].code+"."+ res[i].data)
                                jso.gtrigger(res[i].code, res[i].data);
                            }
                        }
                    });

                    return this;
                },
                __jsoHandler: function(event, data_in) {
            
                    var data = null;
                    if (data_in != undefined && data_in != null && data_in != "null" && typeof(data_in) != "undefined")
                        data = data_in;
                    else if (data_in != undefined &&  typeof(event.data) != "undefined" && typeof(event.data) != "null")
                        data = event.data;

                    //var src = event.split(".");
                    //var ev = src[0].split("_");
                    //var ns = (src.length == 2) ? src[1] : null;
                    var target = $(event.target);
                    var ev = event.type.split("_");
                    var ns = event.namespace;
                    if (ev[0] == "aventurier" || ev[0] == "bestiaire" || ev[0] == "inventaire" || ev[0] == "donjon") {
                        $(this).reload(event, data);
                    } else if (ev[0] == "piece") {
                        if (ns == "reloadElements") {
                            target.loadElements();
                        }
                    } else if (ev[0] == "chat") {
                        if (ev[1] == "send") { // empiler la commande & send

                        } else if (ev[2] == "reload") {// charger le chat

                        }

                    } else if (ev[0] == "commerce") {

                    } else if (ev[0] == "info") {
                        set_info(data);
                    }
                },
                // Ajoute un nouvelle "écouteur"
                addItem: function(o) {
                    o.each(function(){
                        $(this).on($(this).attr('data-source'), $.dGame.jsobserver.__jsoHandler);
                    });
                    return this;
                },
                // Suprrimer un "écouteur"
                delItem: function(o) {
                    o.off(o.attr('data-source'), $.dGame.jsobserver.__jsoHandler);
                },
                // Signale un evenement aux écouteurs
                gtrigger: function(event, data) {
                    $('[data-source]').trigger(event, data);
                    return this;
                },
                // indique la route @deprecated ?
                data_reload_getrealsource: function(ds) {
                    return ds;
                },
                // recharge tous les écouteurs
                data_reload_all: function() {
                    $('[data-source]').reload();
                }
            },
            //
            // FIN OBSERVER
            // 
            
            //
            // CHAT
            // @todo
            //
            chat: {
              msg_container: null,  // element contenant les messages
              msg_input:null,       // champ de saisie des commandes chat
            },
            
            //
            // MAP
            //
            
            /**
             * Ajout un map
             * @param {jQuery} m
             * @returns {void}
             */
            addMap: function(m) {
                $.dGame.data.maps.push(m);
            },
                    
            /**
             * Retire un map
             * @param {jQuery} m
             * @returns {void}
             */
            delMap: function(m) {
                $.dGame.data.maps.unset(m);
            },
                    
            /**
             * Cherche un carte en fonction du nom
             * @param {String} name
             * @returns {null}|{jQuery}
             */
            getMapByName: function(name) {
                for(var i=0;i<$.dGame.data.maps.length;i++) {
                    if ($.dGame.data.maps[i].data("name") === name)
                        return $.dGame.data.maps[i];
                }
                return null;
            },
                    
            /**
             * Renvoie la liste des cartes correspondant aux critères de recherche
             * @param {String} index
             * @param {String} value
             * @returns {Array}
             */
            getMapBy: function(index, value) {
                var res = [];
                for(var i=0;i<$.dGame.data.maps.length;i++) {
                    if ($.dGame.data.maps[i].data(index) === value)
                        res.push( $.dGame.data.maps[i] );
                }
                return res;
            },
                    
            map_default_config: {
                mid: null, // identifiant unique
                name:null,   // nom de la carte, permettant dfacilement de la récupérer
                //
                // PIECE attributs
                //
                id:null,
                idetage:null,
                nom:null,
                posx:null,
                posy:null,
                taillex:null,
                tailley:null,
                coucheSol:null,
                coucheSol2:null,
                coucheMobilier:null,
                actions:null,
                etat:null,
                lumiere:null,
                //
                // DGAME attributs
                //                       
                mode: 'game', // mode : editor, game, view
                width: 640, // largeur de la fenetre de vue de la carte
                height: 480, // largeur de la fenetre de vue de la carte
                mapWidth: 640, // largeur de la fenetre de vue de la carte
                mapHeight: 480, // largeur de la fenetre de vue de la carte
                tileSize: 32, // taille d'un carré
                wrapper: null,  // id de l'element DOM contenant la carte
                layers: [], // couches de la carte @deprecated
                
                objets: [], // personnages, monstes et objets sur la carte
                _aventuriers:[], // aventuriers sur la carte
                _bestiaires:[], // bestiaires sur la carte
                _inventaires:[], // inventaire sur la carte
                
                actionFirstLoad:true,// tableau des actions déjà initialisé
                _actions:[] // tableau des actions initialisées
                
            }
        }


    }); // fin de $

    // definition pour le selector jQuery $('...')
    $.fn.extend({
        //
        // OBSERBER : RELOAD
        //
        reload: function(event, data) {
            return this.each(function() {
                var src = $(this).attr('data-source');
                if (src == undefined) {
                    console.error("Attribut 'data-source' not found.");
                    return this;
                }
                if (typeof(data) != "null" && typeof(data) != null && typeof(data) != "undefined") {
                    $(this).html(data);
                    return this;
                } else {
                    var uri = $.dGame.jsobserver.data_reload_getrealsource(this.attr('data-source'));
                    $(this).load(uri, function(response, status, xhr) {
                        if (status == "error") {
                            var errmsg = "(" + xhr.status + " " + xhr.statusText + ") : " + response;
                            console.error(errmsg);
                            set_error(errmsg);
                        }
                    });
                }
            });
        },
        
        _jso_majDonneeAffichage: function(data) {
           
        },
        _jso_loadData:function() {
            
        },
        
        //
        // MAP : MANIPULATION GENERALE
        //
        createMap: function(options) {
            if (this.length == 1) {
                if (this[0] == document) {
                    // Old usage detected, this is not supported anymore
                    throw "Impossible de créer une carte ici !";
                }
                options = options || {};
                options = $.extend($.dGame.map_default_config, options);
                
                options.wrapper = this;
                
                options.mid = 'dgame_map_' + Math.floor(Math.random() * 999999);
                
                this.data(options);
                
                this.addClass("map_wrapper");
                
                $.dGame.addMap(this);
            }
            return this;
        },
                
        deleteMap: function() {
            $.dGame.jsobserver.delItem(this);
            $.dGame.delMap(this); // fonctionne ?
            this.empty();
            return this;
        },
                
        importMap: function(m) {
            this.data(m);
            this.buildMap();
            return this;
        },    
                
        buildMap: function() {
            // ajustement du wrapper
            this.css('overflow', ((this.data("mode") === 'game')?'hidden':'auto')); // dépassement si carte trop grande pour le conteneur ?
                        
            if (this.height() === 0) {
                this.height( this.parent().height() - 10 );
            }
            this.hide();
            
            // récupération des dimensions du conteneur
            this.data("width", this.width() );
            this.data("height", this.height() );
            // calcul des dimensions de la carte
            this.data("mapWidth", this.data("tileSize") * this.data("taillex") );
            this.data("mapHeight", this.data("tileSize") * this.data("tailley") );
            // récupération de l'identifiant unique de la carte
            var mid = this.data("mid");
            
            var posX = (this.data("mapWidth") > this.data("width")) ? 0 : (this.data("width")-this.data("mapWidth")) / 2 ;
            var posY = (this.data("mapHeight") > this.data("height")) ? 0 : (this.data("height")-this.data("mapHeight")) / 2 ;
            
            // création des calques
            this.empty().html($('<div />', {
                id: mid,
                'class': "map_layer_wrapper",
                css: {
                    backgroundColor: '#000',
                    backgroundImage: ((this.data("mode") === 'editor') ? 'url("' + img_dir + '/interface/stripe_01.png")' : 'none'),
                    position: 'absolute',
                    overflow: ((this.data("mode") === 'game') ? 'hidden' : 'auto'),
                    top: posY,
                    left: posX,
                    //border: (this.displayMode == 'editor')?'1px solid #C0C0C0':'',
                    width: this.data("mapHeight") + "px",
                    height: this.data("mapHeight") + "px"
                }
            }));
            
            var mapw = this.children('#'+mid);
            

            // couches de la carte 
            for(var i=1; i<=6; i++)
            {
                mapw.append( $('<div />', {
                    id: mid + '_layer_' + i,
                    'class': "map_layer map_layer" + i,
                    css: {
                        position: 'absolute',
                        //border: '2px solid red',
                        //overflow : 'hidden',
                        top: 0,
                        left: 0,
                        width: this.data("mapWidth") + "px",
                        height: this.data("mapHeight") + "px",
                        zIndex: 200 + 10 * (i + 1)
                    }
                }) );

            }
            
            // couche lumière
            mapw.append($('<div />', {
                id: mid + '_layer_l',
                'class': "map_layer map_layerl"

            }));
            // css couche lumière
            if (this.data("mode") === 'game') {
                $('#'+mid + "_layer_l").css({
                    backgroundColor: '#000',
                    opacity: 0,
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    width: this.data("mapWidth") + "px",
                    height: this.data("mapHeight") + "px",
                    zIndex: 269
                }).animate({opacity: (1 - this.data("lumiere") / 10)});
            }
            
            // Remplissage des couches
            mapw.children("div.map_layer")._initLayer();
           
            // initialisation du tableau des actions
            //
            // /!\ TODO lors d'un build appellé pour un rafraichissement,
            // ne pas refaire le tableau pour aventurier/inventaire/bestiaire.
            // seulement pour les PO
            //
            var actions = [];
            for (var y = 0; y < this.data("tailley"); y++) {
                if (this.data("actionFirstLoad")) {
                    actions[y] = [];
                }

                for (var x = 0; x < this.data("taillex"); x++) {
                    if (this.data("actionFirstLoad") === true)
                        actions[y][x] = [];

                    actions[y][x]['onClick'] = "";	// lorsque l'on click sur la case
                    actions[y][x]['onDblClick'] = "";	// lorsque l'on double click sur la case
                    actions[y][x]['beforeMove'] = null;	// avant d'arriver la case
                    actions[y][x]['onMove'] = null;	// lorsque l'on arrive sur la case
                    actions[y][x]['onOut'] = null;	// lorsque l'on quitte la case
                    actions[y][x]['PO'] = null;	// lorsque l'on quitte la case

                    
                    if (this.data("actionFirstLoad") === true) {
                        actions[y][x]['menu'] = []; // menu contextuelle (special)
                        actions[y][x]['aventurier'] = []; // aventurier présent
                        actions[y][x]['inventaire'] = []; // inventaire présent
                        actions[y][x]['bestiaire'] = []; // bestiaire présent
                        //this.actions[y][x]['strings'] = "" ;	// backup des actions
                    }
                }
            }
            this.data("actionFirstLoad",false);
            this.data("_actions", actions);
            this._parseActions();
            
            this.loadElements();
            
            
            // scroll pour voir le joueur
            if (this.data("mode") === 'game') {

                /*var pos = $('.aventurier-imgtile-joueur').parent().attr('id').split('-l5d').pop().split("_");
                 
                 var scrollX = pos[0] * 32 - this.data('MaxWidth') / 2;
                 var scrollY = pos[1] * 32 - this.data('MaxHeight') / 2;
                 //console.log(scrollX+","+scrollX)
                 //console.log(mapA.MaxWidth+","+mapA.MaxHeight)
                 $(document).ready(function() {
                 this.find('#'+this.data("mid")).delay(100).scrollTo({top: scrollY + "px", left: scrollX + "px"}, 1000, {easing: 'easeInOutCubic'});
                 });*/
            }
            
            // initialisation du menu contextuel
            $(function() {
                $.contextMenu({
                    selector: "#" + mapw.parent().data("mid") + '_layer_6 table.epiece td',
                    //'#map' + mapA.MapId + '_layer_6 table.epiece td',
                    build: mapw.parent().setContextMenu
                });
            });
            
            this.attr("data-source","piece_"+this.data("id")+".reloadElements");
            $.dGame.jsobserver.addItem(this);
            this.fadeIn(1000);
            
            this.attr('data-source','piece_'+this.data("id"));
            $.dGame.jsobserver.addItem(this);
            
            return this;
        },
                
        _getLayer: function(num) {
            return this.find("#"+this.data("mid") + "_layer_" + num);
        },
        // retourne le $ cellule x,y de la couche l
        _getCell: function(l, x, y) {
            return this.find("td#" + this.data("mid") + '-l' + l + 'd' + x + '_' + y);
        },
        
        _getMapRoot: function() {
            if (this == document) {
                throw "Element MapRoot introuvable !";
            }
            // console.log("_getMapRoot on '"+this.get(0).tagName+((this.attr("id")?"#"+this.attr("id"):"?"))+"' : "+(this.data("mid") != undefined));
            return (this.data("mid") != undefined) ? this : this.parent()._getMapRoot();
        },
   
	_animateLoopOpacity: function(l,x,y) {
                var _delay = 2000;
		this._getCell(l,x,y)
                        .delay(_delay)
			.animate({opacity:0.1},_delay)
			.delay(_delay * 4)
			.animate({opacity:0.5},_delay,"linear", function() { $(this)._getMapRoot()._animateLoopOpacity(l,x,y) });
	},
                
        _initLayer: function() {
            return this.each(function() {
                var map = $(this).parent().parent();
                var mid = map.data("mid");
                // ?
                var layerNum = $(this).attr("id").split("_").pop();
                // on récupère le tileset
                var tileSetString = null;
                switch (layerNum) {
                    case '1' :
                        tileSetString = map.data("coucheSol")
                        break;
                    case '2' :
                        tileSetString = map.data("coucheSol2")
                        break;
                    case '3' :
                        tileSetString = map.data("coucheMobilier")
                        break;
                    case 'l':
                        return;
                        break;
                }

                var isTileSet = (tileSetString != null && tileSetString != undefined);
                var tileSet = [];

                // mise en forme des données pour la création de la grille
                if (isTileSet && layerNum == 1) {
                    tileSet = tileSetStringToArray(tileSetString, map.data("taillex"), map.data("tailley"));
                } else if (isTileSet && (layerNum == 2 || layerNum == 3)) {
                    tileSet = tileMobilierStringToArray(tileSetString, map.data("taillex"), map.data("tailley"));
                }

                // creation de la grille
                var gird = '<table class="epiece">';
                for (var y = 0; y < map.data("tailley"); y++) {
                    gird += '<tr>';
                    for (var x = 0; x < map.data("taillex"); x++) {
                        var bonus = '';
                        if (isTileSet && tileSet[y][x] != null) {
                            bonus += ' style="background:no-repeat url(\'' + img_dir + 'tiles/t' + tileSet[y][x] + '.png\');"';
                        }
                        if (map.data("mode") === 'editor') {
                            bonus += ' class="border1gray" ';
                        }
                        gird += '<td id="' + mid + '-l' + layerNum + 'd' + x + '_' + y + '" ' + bonus + ' title="(' + x + ',' + y + ')" map-action=""></td>';
                    }
                    gird += '</tr>';
                }
                gird += '</table>';

                // on ajoute la grille créée au calque
                $(this).html(gird);

                // si on est en mode édition, ajout de fonctionnalités
                if (map.data("mode") === 'editor') {

                    // on récupère les tiles
                    var tiles = $(this).find('td');

                    // on supprime un element du décors au double clique (sur les couches décors et mobilier uniquement)
                    if (layerNum == 2 || layerNum == 3) {
                        // .$('td[id^="' + this.MapId + '-l' + layerNum + 'd"]')
                        tiles.dblclick(function() {
                            $(this).css({backgroundImage: ''});
                        });
                    }

                    // fonction de dessin
                    // @todo
                    if (layerNum == 1 || layerNum == 2 || layerNum == 3 || layerNum == 6) {
                        
                        map = $(this);

                        //$('td[id^="' + this.MapId + '-l' + layerNum + 'd"]').click(function() {
                        tiles.click(function() {
                            // tile selectionné dans la  librairie
                            var e = $('#gardien-editeur-sidepanel-libs .ge_layer_libs span.selected:visible');
                            // quel outil sélectionné ?
                            var tool = (layerNum == 6) ? e.attr('tool') : getCurrentTool();

                            // pinceau
                            if (tool === 'p') {
                                if (layerNum != 6) {
                                    $(this).css({backgroundImage: e.css('background-image')});
                                } else {
                                    map.setAction($(this), e);
                                }

                            // rectangle
                            } else if (tool === 'r') {
                                // si rectangle déja en cours : stop réctangle
                                if (map.data("editorCurrentDraw") == true) {
                                    if (layerNum != 6) {
                                        tiles.filter(".gEPtempTile").css({backgroundImage: e.css('background-image')});
                                    } else {
                                        map.setAction(tiles.filter(".gEPtempTile"), e);
                                    }
                                    map.stopNewTile();
                                // sinon : nouveau rectangle
                                } else {
                                    var idTile = $(this).attr('id');
                                    //format ex: VRV-l1d11_9
                                    var posTile = idTile.substring(7).split("_");
                                    var l = idTile.substring(5, 1);
                                    map.addTileToGrid(l, posTile[0], posTile[1]);
                                }
                            }
                        });

                        // $('td[id^="' + this.MapId + '-l' + layerNum + 'd"]').mouseover(function() {
                        tiles.mouseover(function() {
                            if (map.data("editorCurrentDraw")) {
                                this.map = map;
                                var idTile = $(this).attr('id');
                                var posTile = idTile.substring(7).split("_");
                                var l = idTile.substring(5, 6);
                                map.setSquareNewTiles(l, map.data("editorDrawInitPot")[0], map.data("editorDrawInitPot")[1], posTile[0], posTile[1]);
                            }
                        });
                    }
                }
                return this;
            });
        },
                
        // actions
        _parseActions: function() {
            this._getLayer(6).find('td').empty();

            var data = this.data("actions").explodex("}{");
            

            for (var i = 0; i < data.length; i++)
            {
                var d = data[i].split(',');
                var x = d[0];
                var y = d[1];
                var action = d[2];
                switch (action)
                {
                    // PO {X,Y,PO,5} : 5 PO
                    case 'PO' :
                        this.data("_actions")[y][x]['PO'] = d[3];
                        //mapA.actions[y][x]['menu'][data[i]] =  {name: "Ramasser l'or ("+d[3]+"PO)" };
                        if (this.data("mode") !== 'editor')
                            this._getCell(4, x, y).html('<img src="' + img_dir + '/interface/actions/gold_01.png" width="50%" height="50%"/>');
                        break;

                        // Case interdit {X,Y,I} : Case interdit
                    case 'I' :
                    case 'W' :
                    case 'V' :
                        this.data("_actions")[y][x]['beforeMove'] = "false";
                        break;

                        // Case de sortie {X,Y,S} : Case de sortie du donjon
                    case 'S' :
                        this.data("_actions")[y][x]['beforeMove'] = "quitterDonjon();";
                        if (this.data("mode") !== 'editor') {
                            this._getCell(6, x, y).css({backgroundColor: 'green', opacity: 0.5}).attr('title', 'Sortie de secours !');
                            this._animateLoopOpacity(6, x, y);
                        }
                        break;

                        // Case de fin {X,Y,F} : Case de fin du donjon
                    case 'F' :
                        this.data("_actions")[y][x]['beforeMove'] = "Av_Action_F()";
                        if (this.data("mode") != 'editor') {
                            this._getCell(6, x, y).css({backgroundColor: 'yellow', opacity: 0.5}).attr('title', 'Fin du donjon :-)');
                            this._animateLoopOpacity(6, x, y);
                        }
                        break;

                        // Escalier qui monte/descend {X,Y,EM/ED} : Escalier qui monte/descend
                    case 'EM' :
                    case 'ED' :
                        this.data("_actions")[y][x]['beforeMove'] = "Av_Action_" + action + "()";
                        break;

                        // Passage secret {X,Y,P,idP,X,Y} : passage secret vers piece#idP en X,Y
                    case 'P' :
                        this.data("_actions")[y][x]['beforeMove'] = "Av_Action_P(" + d[3] + "," + d[4] + "," + d[5] + ")";
                        //mapA.actions[y][x]['onMove'] = "Av_Action_P("+d[3]+","+d[4]+","+d[5]+")";
                        break;

                        // Message {X,Y,M,"Message"} : affiche le message "Message"
                    case 'M' :
                        this.data("_actions")[y][x]['onMove'] = "alert(\"" + d[3] + "\")";
                        break;

                        // Message {X,Y,MS,"Message"} : affiche le message "Message" au survol de la sourie
                    case 'MS' :
                        this._getCell(6, x, y).mouseover(function() {
                            alert(d[3]);
                        });
                        break;

                        // Message {X,Y,M,"Message"} : affiche le message "Message"
                    case 'D' :
                        this.data("_actions")[y][x]['onMove'] = "Av_Action_D()";
                        break;

                }
                if (this.data("mode") === 'editor') {
                    // #lib-action-item-I-11-13
                    idLine = '\'#lib-action-item-' + action + '-' + x + '-' + y + '\'';
                    txt = '<img src="' + img_dir + '/interface/actions/' + action + '.png" width="16" height="16" border="0"/>';
                    item = '<span id="onmap-action-item-' + action + '-' + x + '-' + y + '"'
                            + ' class="pointer"'
                            + ' onmouseover="clignote($(' + idLine + '));"'
                            + ' onclick="$(\'#gardien-editeur-sidepanel-libs\').scrollTo($(' + idLine + '));clignote($(' + idLine + '));"'
                            + ' ondblclick="confirm(\'Supprimer?\')'
                            + ' && loadInLayer(Routing.generate(\'piece_unsetaction\',{\'id\':' + this.data('id') + ',\'x\':' + x + ',\'y\':' + y + ',\'a\':\'' + action + '\'}),'
                            + ' \'#gardien-editeur-sidepanel-libs .ge_lib_wrapper-6\')"'
                            + '>'
                            + txt
                            + '</span> ';
                    this._getCell(6, x, y).append(item);
                }

            }
        },
                
        // chargement des objets et monstre de la carte
	loadElements: function() {
            var mapw = this;
            start_loading();
            $.getJSON(Routing.generate('gamecommon_maploadobjetmonstre', {'id': mapw.data("id")})).done(function(data) {
                
                // AVENTURIERS
                // parcour : arrivée(new) - départ(delete) - présent(update)
                if (data.aventuriers.length > 0)
                {
                    var av_del = mapw.data("_aventuriers").diffo(data.aventuriers, false); // on récupère les aventuriers partis de la carte
                    var av_upd = data.aventuriers.diffo(mapw.data("_aventuriers"), true); // on récupère les aventuriers ayant bougé sur la carte
                    var av_new = data.aventuriers.diffo(mapw.data("_aventuriers"), false); // on récupère les nouveaux aventuriers de la carte
                 
                    // mise à jour du tableau des aventuriers actuellement sur la carte
                    mapw.data("_aventuriers", data.aventuriers);
                    
                    // on ajoute les nouveaux aventuriers
                    for (var i = 0; i < av_new.length; i++) {
                        var av = av_new[i];
                        var pos = av.position.explodex(",");
                        var cell = mapw._getCell(5,pos[0],pos[1]);
                        
                        // on ajoute l'image sur la carte
                        var img = '<img src="' + img_dir + '/creatures/aventurier.gif"' + ' alt="'+av.nom+'" id="aventurier-imgtile-' + av.id + '" class="aventurier-imgtile" style="display:none;" ondblclick="displayFiche("aventurier",' + av.id + ')"/>';
                        cell.append(img);
                        cell.children('#aventurier-imgtile-' + av.id).fadeIn(500);
                        
                        // @deprecated on met à jour le tableau
                        // mapw.data("actions")[y][x]['aventuriers'][av.id+':a'] = av.nom;
                    }
                    
                    // on marque le joueur courant
                    if (mapw.data("mode") === "game")
                        mapw.find("#aventurier-imgtile-" + $.dGame.data.joueur.id).addClass("aventurier-imgtile-joueur");
                    
                    // on retire les aventuriers ayant quitté la carte
                    for (var i = 0; i < av_del.length; i++) {
                        var av = av_del[i];
                        mapw._getLayer(5).find('img#aventurier-imgtile-' + av.id).fadeOut(500, function() { $(this).remove(); });
                        
                        // @deprecated on met à jour le tableau
                        // delete mapw.data("actions")[y][x]['aventuriers'][av.id+':a'];
                    }
                    
                    // on déplce les aventuriers ayant bougé
                    for (var i = 0; i < av_upd.length; i++) {
                        var av = av_upd[i];
                        var pos = av.position.explodex(",");
                        mapw._getLayer(5).find('img#aventurier-imgtile-' + av.id).appendTo( mapw._getCell(5,pos[0],pos[1]) );
                        
                        // @deprecated on met à jour le tableau
                        // delete mapw.data("actions")[y][x]['aventuriers'][av.id+':a'];
                        // mapw.data("actions")[y][x]['aventuriers'][av.id+':a'] = av.nom;
                    }
                }
                
                // BESTIAIRES
                // parcour : arrivée(new) - départ(delete) - présent(update)
                if (data.bestiaires.length > 0)
                {
                    var bes_del = mapw.data("_bestiaires").diffo(data.bestiaires, false); // on récupère les bestiaires partis de la carte
                    var bes_upd = mapw.data("_bestiaires").diffo(data.bestiaires, true); // on récupère les bestiaires ayant bougé sur la carte
                    var bes_new = data.bestiaires.diffo(mapw.data("_bestiaires"), false); // on récupère les nouveaux bestiaires de la carte
                 
                    // mise à jour du tableau des bestiaires actuellement sur la carte
                    mapw.data("_bestiaires", data.bestiaires);
                    
                    // on ajoute les nouveaux bestiaires
                    for (var i = 0; i < bes_new.length; i++) {
                        var bes = bes_new[i];
                        var pos = bes.position.explodex(",");
                        var cell = mapw._getCell(5,pos[0],pos[1]);
                        
                        // on ajoute l'image sur la carte
                        var img = '<img src="' + img_dir + '/creatures/monstre.gif"' + ' alt="'+bes.prenom+'" id="bestiaire-imgtile-' + bes.id + '" class="bestiaire-imgtile" style="display:none;" ondblclick="displayFiche("bestiaire",' + bes.id + ')"/>';
                        cell.append(img);
                        cell.children('#bestiaire-imgtile-' + bes.id).fadeIn(500);
                    }
                    
                    // on retire les bestiaires ayant quitté la carte
                    for (var i = 0; i < bes_del.length; i++) {
                        var bes = bes_del[i];
                        mapw._getLayer(5).find('img#bestiaire-imgtile-' + bes.id).fadeOut(500, function() { $(this).remove(); });
                    }

                    // on déplce les bestiaires ayant bougé
                    for (var i = 0; i < bes_upd.length; i++) {
                        var bes = bes_upd[i];
                        var pos = bes.position.explodex(",");
                        mapw._getCell(5, pos[0], pos[1]).append(mapw._getLayer(5).find('img#bestiaire-imgtile-' + bes.id));
                    }
                }
                
                // INVENTAIRES
                // parcour : arrivée(new) - départ(delete) - présent(update)
                if (data.inventaires.length > 0)
                {
                    var inv_del = mapw.data("_inventaires").diffo(data.inventaires, false); // on récupère les inventaires partis de la carte
                    var inv_upd = mapw.data("_inventaires").diffo(data.inventaires, true); // on récupère les inventaires ayant bougé sur la carte
                    var inv_new = data.inventaires.diffo(mapw.data("_inventaires"), false); // on récupère les nouveaux inventaires de la carte
                 
                    // mise à jour du tableau des bestiaires actuellement sur la carte
                    mapw.data("_inventaires", data.inventaires);
                    
                    // on ajoute les nouveaux bestiaires
                    for (var i = 0; i < inv_new.length; i++) {
                        var inv = inv_new[i];
                        var pos = inv.position.explodex(",");
                        var cell = mapw._getCell(4,pos[0],pos[1]);
                        
                        // on ajoute l'image sur la carte
                        var img = '<img src="' + img_dir + '/objets/'+inv.idobjets+'.png"' + ' alt="'+inv.nom+'" id="inventaire-imgtile-' + inv.id + '" class="inventaire-imgtile" style="display:none;" ondblclick="displayFiche("inventaire",' + inv.id + ')"/>';
                        cell.append(img);
                        cell.children('#inventaire-imgtile-' + inv.id).fadeIn(500);
                    }
                    
                    // on retire les bestiaires ayant quitté la carte
                    for (var i = 0; i < inv_del.length; i++) {
                        var inv = inv_del[i];
                        mapw._getLayer(4).find('img#inventaire-imgtile-' + inv.id).fadeOut(500, function() { $(this).remove(); });
                    }

                    // on déplce les bestiaires ayant bougé
                    for (var i = 0; i < inv_upd.length; i++) {
                        var inv = inv_upd[i];
                        var pos = inv.position.explodex(",");
                        mapw._getCell(4, pos[0], pos[1]).append(mapw._getLayer(4).find('img#inventaire-imgtile-' + inv.id));
                    }
                }
                
                // on met à jour le tableau d'information
                //  qui contient la liste des éléments de la carte
                var t = $('.carte-liste-element.carte-liste-element-' + mapw.data("name"));
                
                if (mapw.data("mode") !== 'editor' && t.length > 0) {
                    var c = '<ul>';

                    // listes des aventuriers
                    for (var i = 0; i < mapw.data("_aventuriers").length; i++) {
                        var av = mapw.data("_aventuriers")[i];
                        var pos = av.position.explodex(',');

                        c = c + '<li class="description aventurier">';
                        c = c + '<img align="absmiddle" title="(' + pos[0] + ',' + pos[1] + ')" onmouseover="$(\'#aventurier-imgtile-' + av.id + '\').clignote()"  '
                                + 'src="' + img_dir + 'creatures/aventurier.gif" height="20"> ' + av.nom;
                        c = c + ' <button onclick="displayFiche(\'aventurier\',' + av.id + ')"><img width="16" src="' + img_dir + '/gicon/fiche.png"  /></button>';
                        c = c + ' <button onclick=\'chat_msg_prive("' + av.nom + '")\'><span class="ui-icon ui-icon-comment"></span></button>';
                        if (mapw.data("mode") === 'game')
                            c = c + ' <button onclick="av_attaquer(\'' + av.id + ':a\')"><img width="16" src="' + img_dir + '/gicon/attack.png"  /></button>';
                        c = c + '</li>';
                    }

                    // listes des bestiaires
                    for (var i = 0; i < mapw.data("_bestiaires").length; i++) {
                        var bes = mapw.data("_bestiaires")[i];
                        var pos = bes.position.explodex(',');

                        c = c + '<li class="description bestiaire">';
                        c = c + '<img align="absmiddle" title="(' + pos[0] + ',' + pos[1] + ')" onmouseover="$(\'#bestiaire-imgtile-' + bes.id + '\').clignote()"  '
                                + 'src="' + img_dir + 'creatures/monstre.gif" height="20"> ' + bes.prenom;
                        c = c + ' <button onclick="displayFiche(\'bestiaire\',' + bes.id + ')"><img width="16" src="' + img_dir + '/gicon/fiche.png"  /></button>';
                        if (mapw.data("mode") === 'game')
                            c = c + ' <button onclick="av_attaquer(\'' + bes.id + ':b\')"><img width="16" src="' + img_dir + '/gicon/attack.png"  /></button>';
                        c = c + '</li>';
                    }

                    // listes des inventaires
                    for (var i = 0; i < mapw.data("_inventaires").length; i++) {
                        var inv = mapw.data("_inventaires")[i];
                        var pos = inv.position.explodex(',');


                        c = c + '<li class="description inventaire">';
                        c = c + '<img align="absmiddle" title="(' + pos[0] + ',' + pos[1] + ')" onmouseover="$(\'#inventaire-imgtile-' + inv.id + '\').clignote()"  '
                                + 'src="' + img_dir + 'objets/' + inv.idobjets + '.png" height="20"> ' + inv.nom;
                        c = c + ' <button onclick="displayFiche(\'inventaire\',' + inv.id + ')"><img width="16" src="' + img_dir + '/gicon/fiche.png"  /></button>';
                        if (mapw.data("mode") === 'game' || mapw.data("mode") === 'view')
                            c = c + ' <button onclick="av_ramasser_inv(' + inv.id + ')"><img width="16" src="' + img_dir + '/gicon/take.png"  /></button>';
                        c = c + '</li>';
                    }

                    c = c + '</lu>';
                    
                    t.html(c);
                }
                
            }).fail(function(err) {
                set_error("Erreur lors du chargement des objets et personnages (actualiser la page ?)")
            }).always(function() {
                stop_loading();
            });
	},
        
        setContextMenu: function($trigger, e) {
            var map = $trigger._getMapRoot();
            var pos = $trigger.attr('id').split('-l6d').pop().split('_');
            var x = pos[0];
            var y = pos[1];
            var action = map.data("_actions")[y][x];

            // menu en mode jeu
            if (map.data("mode") === 'game') {

                var o = {
                    callback: function(key, options) {
                        set_error('Choix "' + key + '" non traité !')
                    },
                    items: {
                        //"fouiller": {name: "Fouiller"}
                        //"sep1": "---------",
                        //"quit": {name: "Quit", icon: "quit"}
                    }
                }


                // ajout des aventuriers
                for (var i = 0; i < map.data('_aventuriers').length; i++) {
                    var av = map.data('_aventuriers')[i];
                    if (av.position !== '{' + x + ',' + y + '}')
                        continue;
                    var id = av.id;
                    // si c'est le joueur courant, on passe.
                    if ($('#aventurier-imgtile-' + id).hasClass('aventurier-imgtile-joueur'))
                        continue;

                    var nom = av.nom;
                    var key = av.id + ":a";
                    o.items[key] = {
                        name: nom,
                        "items": {
                            "fiche": {"name": "Fiche", callback: function(k, o) {
                                    displayFiche('aventurier', id);
                                }, icon: "fiche"},
                            "attac": {"name": "Attaquer", callback: function(k, o) {
                                    av_attaquer(key);
                                }, icon: "attack"},
                            "parler": {"name": "Parler", callback: function(k, o) {
                                    chat_msg_prive(nom);
                                }},
                            "askfor": {"name": "Demander...",
                                "items": {"proche": {name: "en proche"},
                                    "ami": {name: "en ami"},
                                    "amour": {name: "en amour"},
                                    "ennemi": {name: "en ennemi"},
                                    "maitre": {name: "un apprentissage"},
                                    "creancier": {name: "de l'argent"}
                                }}
                        }
                    };
                }

                // ajout des bestaire
                for (var i = 0; i < map.data('_bestiaires').length; i++) {
                    var bes = map.data('_bestiaires')[i];
                    if (bes.position !== '{' + x + ',' + y + '}')
                        continue;
                    var id = bes.id;
                    var nom = bes.prenom;
                    var key = bes.id + ":b";
                    o.items[key] = {
                        name: nom,
                        "items": {
                            "fiche": {"name": "Fiche", callback: function(k, o) {
                                    displayFiche('bestiaire', id);
                                }, icon: "fiche"},
                            "attac": {"name": "Attaquer", callback: function(k, o) {
                                    av_attaquer(key);
                                }, icon: "attack"}
                        }
                    };
                }

                // ajout des inventaires
                for (var i = 0; i < map.data('_inventaires').length; i++) {
                    var inv = map.data('_inventaires')[i];
                    if (inv.position !== '{' + x + ',' + y + '}')
                        continue;
                    var id = inv.id;
                    var nom = inv.nom;
                    var key = inv.id + ":i";
                    o.items[key] = {
                        name: nom,
                        "items": {
                            "fiche": {"name": "Fiche", callback: function(k, o) {
                                    displayFiche('inventaire', id);
                                }, icon: "fiche"}
                            , "take": {"name": "Ramasser", callback: function(k, o) {
                                    av_ramasser_inv(id);
                                }, icon: "take"}
                            //, "hide": {"name":"Cacher"}
                        }
                    };
                }

                if (action['PO'] != null) {
                    o.items['PO'] = {"name": "Prendre (" + action['PO'] + " PO)", callback: function(k, o) {
                            Av_Action_PO(x, y, action['PO']);
                        }, icon: "take"};
                }

                o.items["infos"] = {name: "Infos", icon: "aide"};
            }

            // menu en mode aperçu
            else if (map.data("mode") === 'view') {
                var action = map.data("actions")[y][x];

                var o = {
                    callback: function(key, options) {
                        switch (key) {
                            case 'or' :
                                break;

                            default:
                                var k = key.split(',');
                                var x = k[0];
                                var y = k[1];
                                var a = k[2];
                                switch (a) {
                                    case 'PO':
                                        Av_Action_PO(x, y, k[3]);
                                        break;
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
            else if (map.data("mode") === 'editor') {
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
                
        //
        // MAP : FONCTIONS JEU
        //
	beforeMove: function (x, y) {
		var a = this.data("_actions")[y][x]['beforeMove']; 
		if (a==null) {
			return true;
		} else {
			return eval(a);
		}
	},
	
	onMove: function (x, y) {
		var a = this.data("_actions")[y][x]['onMove'];
		if (a==null) {
			return true;
		} else {
			return eval(a);
		}
	},
	
	onOut: function (x, y) {
		var a = this.data("_actions")[y][x]['onOut'];
		if (a==null) {
			return true;
		} else {
			return eval(a);
		}
	},
	
	onClick: function (x, y) {
		var a = this.data("_actions")[y][x]['onClick']; 
		if (a==null) {
			return true;
		} else {
			return eval(a);
		}
	},
       
        //
        // MAP : FONCTION EDITEUR
        //
	save: function(_call)
	{
		var sol = this.getSolString();
		var sol2 = this.getMobilierString(2);
		var mobilier = this.getMobilierString(3);
		//console.log("sol : "+sol)
		//console.log("sol2 : "+sol2)
		//console.log("mobilier : "+mobilier)
		var route = Routing.generate('piece_save',{'id':this.data("id"),'sol':sol, 'sol2':sol2, 'mobilier':mobilier});
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
            this.data("editorCurrentDraw", true);
            this.data("editorDrawInitPot", [initX, initY]);
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
		this.data("editorCurrentDraw",false);
		this.data("editorDrawInitPot", []);
	},
                
        // editeur d'action : traiter lorsque l'editeur ajoute une action à partir de la carte directement
        // @todo : a finir de traduire
        setAction: function(cells, action) {
            var map = this;
            a = action.attr('action');
            switch (a) {
                case 'W' :
                case 'V' :
                case 'I':
                case 'EM':
                case 'ED' :
                case 'Z':
                case 'S':
                case 'F':
                    var data = new Array();
                    cells.each(function() {
                        data.push($(this).attr('id').split('-l6d').pop());
                    });
                    data = a + '-' + data.join('-');
                    loadInLayer(Routing.generate('piece_saveactions', {'id': map.data("id"), 'actions': data}), '#gardien-editeur-sidepanel-libs .ge_lib_wrapper-6')
                    map.build();
                    break;
                case 'PO' :
                    var coord = cells.attr('id').split('-l6d').pop().split('_');
                    var somme = prompt('Quel montant veux-tu déposer en ' + coord[0] + ',' + coord[1] + ' ?');
                    if (somme != null && somme != undefined) {
                        deposerPo(map.data("id"), coord[0], coord[1], somme);
                        loadInLayer(Routing.generate('piece_interfaceediteurpieceactionlibs', {'id':map.data("id")}), '#gardien-editeur-sidepanel-libs .ge_lib_wrapper-6');
                        // loadInLayer('donjon/editeurLibrairieActionLib?idPIECE='+oMap.idPIECE+'&a='+data,'#gardien-editeur-sidepanel-libs .ge_lib_wrapper-6')
                        map.build();
                    }
                    break;

                case 'P' :
                case 'M' :
                case 'MS' :
                case 'D':
                    var coord = cells.attr('id').split('-l6d').pop().split('_');
                    loadInLayer(Routing.generate('piece_actionform', {'id': map.data("id"), 'type': a, 'x': coord[0], 'y': coord[1]}), "#dialogLibNewAction");

                    $("#dialogLibNewAction").dialog({
                        modal: true,
                        closeOnEscape: true,
                        title: "Nouvelle action",
                        width: 500,
                        resizable: false
                    });
                    break;
            }
        },
        
                
        

    }); // fin $.fn

    // alias
    $.extend({gG: $.dGame});
})(jQuery);

// initialisation
$(document).ready(function(){
   $.dGame.init();
});