var mode = 'aventurier';
/********************
 *		GENERAL		*
 ********************/
function Av_Reload_Interface()
{
    loadInLayer(Routing.generate('gameaventurier_interfacemain'), '#aventurier-body-mainpanel');
}

function av_ressusciter(o)
{
    $.get(Routing.generate('aventurier_ressusciter')).done(function(data) {
        updateVie();
        Av_AllerTaverne();
    });
}

function quitterDonjon()
{
    if (confirm('Veux-tu vraiment quitter le Donjon ?'))
    {
        Av_mapDestroy();
        $.get(Routing.generate('aventurier_quitterdonjon')).done(function(data) {
            //alert(data);
            Av_Reload_Interface();
            set_background('taverne_01');
            $('.av-mode-donjon').fadeOut();
        });
    }
    return false;
}

function AllerAuDonjon(idD)
{
    if (confirm('Veux-tu vraiment partir à \'aventure (seul(e)) dans ce Donjon ?'))
    {
        $.get(Routing.generate('aventurier_debutdonjon', {'id': idD})).done(function(data) {
            //alert(data);
            Av_Reload_Interface();
            set_background(null);
            $('.av-mode-donjon').fadeIn();
        });

    }
}

var __av_away_from_taverne = false;
function Av_AllerTaverne()
{
    if (!__av_away_from_taverne)
        return;
    __av_away_from_taverne = false;
    loadInLayer(Routing.generate('gameaventurier_interfacemain'), "#aventurier-body-mainpanel");
    set_background('taverne_01');
}

function Av_AllerMarche()
{
    __av_away_from_taverne = true;
    loadInLayer(Routing.generate('commerce_index'), "#aventurier-body-mainpanel");
    set_background('marche_forge');
}

/********************
 *		PANNEAU		*
 ********************/
var __av_pano_slide_en_cours = false;
function aventurier_showpan(c)
{
    if (!__av_pano_slide_en_cours) {
        __av_pano_slide_en_cours = true;
        var delay = 250;
        if ($('#aventurier-sidepanel-' + c).is(':hidden'))
        {
            if ($('.aventurier-body-sidepanel-button.selected').length > 0)
            {
                // on ferme tout
                $('.aventurier-body-sidepanel').hide("slide", {direction: "right"}, delay);
                $('.aventurier-body-sidepanel-button.selected').removeClass('selected');
                // on ouvre le panel
                $('#aventurier-sidepanel-' + c).delay(delay).show("slide", {direction: "right"}, delay);
                $("#aventurier-sidepanel-" + c + "-button").addClass('selected');
                setTimeout('__av_pano_slide_en_cours = false;', delay * 2);
            }
            else
            {
                $('#aventurier-sidepanel-' + c).show("slide", {direction: "right"}, delay);
                $("#aventurier-sidepanel-" + c + "-button").addClass('selected');
                setTimeout('__av_pano_slide_en_cours = false;', delay);
            }
        }
        else
        {
            $('#aventurier-sidepanel-' + c).hide("slide", {direction: "right"}, delay);
            $("#aventurier-sidepanel-" + c + "-button.selected").removeClass('selected');
            setTimeout('__av_pano_slide_en_cours = false;', delay);
        }
    }
}


$(document).ready(function() {
	// fiche
	$("#aventurier-sidepanel-dialog-infocarac").dialog({
	   	autoOpen:false,
	   	modal:true,
		title:"Points et niveaux",
	   	height:400,
	   	maxHeight:400
	});
});

// inventaire
function av_inventaire_preparerliste() {
        $( ".aventurier-inventaire-connected" ).sortable({
            connectWith: ".aventurier-inventaire-connected",
            placeholder: "ui-state-highlight",
            forceHelperSize:true,
            forcePlaceholderSize:true,
            helper: "clone",
            cursor:"move",
            receive: function ( event, ui )
            {
            	position = $(this).parent().attr("id").replace(/aventurier-sidepanel-inventaire-/,"");
				id = ui.item.attr("id").split("#");
				// si on jette            	
            	if (position == 'JET') {
            		Av_drop(id[1]);     		
            		return;
            	}
				// si on vends            	
            	if (position == 'V') {  		
                	setVal("INVENTAIRE",id[1],"POSITION","{"+position+"}");
            		return;
            	}
            	// si il y a déjà un pbjet d'équipé
            	if ($(this).children().length>1 && position!='I')
            	{
            		$( ui.sender ).sortable( "cancel" );
            		return;
            	}
            	// si incompatibilité dans l'équipement
            	if (
            		// equipement tete
            		(position == 'ET' && !ui.item.hasClass('invcat-CASQUE') ) ||
            		// éqiupement cou 
            		(position == 'EC' && !ui.item.hasClass('invcat-COLLIER') ) ||
            		// équipement arme gauche/droite
            		( (position == 'EAG' || position == 'EAD') &&
            			!(	ui.item.hasClass('invcat-ARME') || ui.item.hasClass('invcat-ARME_DE_JET') || ui.item.hasClass('invcat-BOUCLIER')
            				|| ui.item.hasClass('invcat-PETIT_MATERIEL'))
            			) || 
            		// bracelet gauche/droite
            		( (position == 'EMG' || position == 'EMD') &&
            			!(	ui.item.hasClass('invcat-BRACELET') || ui.item.hasClass('invcat-BAGUE') )
            			) || 
            		// équipement armure (corps)
            		(position == 'ECA' && !(ui.item.hasClass('invcat-VETEMENT') || ui.item.hasClass('invcat-ARMURE')) ) ||
            		// éqiupement ceinture
            		(position == 'ECC' && !ui.item.hasClass('invcat-CEINTURE') ) ||
            		// éqiupement pieds
            		(position == 'EP' && !ui.item.hasClass('invcat-CHAUSSURE') )
            	)
            	{
            		$( ui.sender ).sortable( "cancel" );
            		return;
            		
            	}
            	
            	id = ui.item.attr("id").split("#");
            	setVal("INVENTAIRE",id[1],"POSITION","{"+position+"}");
            }
        }).disableSelection();
}

function Av_Reload_Inventaire() {
    loadInLayer(Routing.generate("gameaventurier_interfaceinventaire"), "#aventurier-sidepanel-inventaire", function() {
        av_inventaire_preparerliste();
    });
}
function Av_drop(idi) {
    $.get(Routing.generate('inventaire_drop', {'id': idi})).done(function(data) {
        Av_Reload_Inventaire();
        var map = $.dGame.getMapByName("carte_aventurier");
        $.dGame.jsobserver.queue({code: "piece_" + map.data("id") + ".reloadElements"});
        //Av_mapForceReload();
    });
}


function Av_Reload_Fiche() {
    loadInLayer(Routing.generate("gameaventurier_interfacefiche"), "#aventurier-sidepanel-fiche");
}


$(function(){ av_inventaire_preparerliste(); });

/********************
 *		CARTE		*
 ********************/

var __Av_reloadMapTimer = 30;
var __Av_reloadMapTimeOut = null;

function Av_timer_Map(t)
{
    //console.log('Av_timer_Map('+t+')')
    clearTimeout(__Av_reloadMapTimeOut);
    if (t == undefined || t == null) {
        t = __Av_reloadMapTimer;
    } else if (t == 0) {
        t = __Av_reloadMapTimer;
        if (mapA != undefined)
            mapA.build();
    }
    //$('#chat-mainwrapper').append('<div>time : '+t+'</div>');
    $('#aventurier-timebeformapreload').html(t);
    __Av_reloadMapTimeOut = setTimeout('Av_timer_Map(' + (t - 1) + ')', 1000);
}
function Av_mapForceReload()
{
    alert('Av_mapForceReload() deprecated : use jso now !');
    //clearTimeout(__Av_reloadMapTimeOut);
    //Av_timer_Map(0);
}



/********************
 *	DEPLACEMENT		*
 ********************/
var __av_deplacement_autorise = true;
function Av_deplacer(d)
{
    if (__av_deplacement_autorise) {
        __av_deplacement_autorise = false;
        var map = $.dGame.getMapByName("carte_aventurier");
        if (!map) {
            console.error("objet map introuvable : déplacement impossible !")
            return;
        }
        var x = 0;
        var y = 0;
        if (d == 'n') {
            y = -1;
        } else if (d == 's') {
            y = 1;
        } else if (d == 'e') {
            x = 1;
        } else if (d == 'w') {
            x = -1;
        }
        var cell = map.find('.aventurier-imgtile-joueur').parent();
        // id au format $('#'+this.MapId+'-l'+l+'d'+x+'_'+y);
        var pos = cell.attr('id').split('-l5d').pop().split("_");
        var pos2 = new Array();
        pos2[0] = parseInt(pos[0]) + x;
        pos2[1] = parseInt(pos[1]) + y;
        cell2 = map._getCell(5, pos2[0], pos2[1]);

        // si la cellule existe et qu'elle n'est pas occupée et qu'aucune action n'empêche le déplacement
        if (cell2.length == 1 && $.trim(cell2.html()) == '' && map.beforeMove(pos2[0], pos2[1]))
        {
            $.get(Routing.generate('aventurier_deplacement', {'x': pos2[0], 'y': pos2[1]}))
                    //dir+'_ajax_query.php/aventurier/moveTo?x='+pos2[0]+'&y='+pos2[1],
                    .done(
                    function(data) {
                        setTimeout('__av_deplacement_autorise = true', 500);
                        if (data == 'ok') {
                            map.onOut(pos[0], pos[1]);
                            map.onMove(pos2[0], pos2[1]);
                            // updateAffichageDonneeNoVal('PDEP');
                            // $.dGame.jsobserver.gtrigger("piece_" + map.data("id") + ".reloadElements");
                            $.dGame.jsobserver.queue({code:'aventurier.pdep'});
                            $.dGame.jsobserver.queue({code:"piece_" + map.data("id") + ".reloadElements"}, true);
                            // Av_mapForceReload();
                        } else {
                            set_error(data);
                        }
                    });
        }
        else
        {
            __av_deplacement_autorise = true;
            //set_error('Cette case est inaccessible !');
        }

    }
}

function Av_mapDestroy() {
    clearTimeout(__Av_reloadMapTimeOut);
    if (mapA)
        mapA.destroy();
    //delete mapA;
}

//
//	ACTIONS
//

//	Ramasser l'or
function Av_Action_PO(x, y, po)
{
    $.get(Routing.generate('aventurier_action_po', {'po': po, 'x': x, 'y': y})).done(function(data) {
        if (data != '') {
            $.dGame.jsobserver.queue({code: 'aventurier.argent'});
            updateAffichageDonnee('data-aventurier-ARGENT', data);
            mapA.build();
        } else {
            set_error("Erreur : impossible de prendre l'or !");
        }
    });
}

// déclencheur
function Av_Action_D()
{
    $.get(Routing.generate('aventurier_action_d')).done(function(data) {
        if (data == '0') {
            set_error('Impossible : pas de déclencheur ici !');
        } else if (data == 'vide') {
            //
            set_info('C\'est vide !');
        } else {
            data = data.split(',');
            for (var i in data) {
                var n = 'data-aventurier-' + data[i].toString();
                if ($('.' + n).length > 0) // protection bug avec recursion infinie
                    updateAffichageDonneeNoVal(data[i].toString());
            }
        }
    });
}

// Passage secret
function Av_Action_P(idPIECE, x, y) {
    Av_mapDestroy();
    loadInLayer(Routing.generate('aventurier_action_p', {'idpiece': idPIECE, 'x': x, 'y': y}), '#aventurier-body-mainpanel');
    return false;
}

// Prendre l'escalier montant
function Av_Action_EM()
{
    Av_mapDestroy();
    //console.log("EM");
    loadInLayer(Routing.generate('aventurier_action_e', {'t': 'M'}), '#aventurier-body-mainpanel');
    return false;
}
// Prendre l'escalier descendant
function Av_Action_ED()
{
    Av_mapDestroy();
    console.log("ED");
    loadInLayer(Routing.generate('aventurier_action_e', {'t': 'D'}), '#aventurier-body-mainpanel');
    return false;
}
// Sortie du donjon
function Av_Action_S()
{
    Av_mapDestroy();
    loadInLayer(Routing.generate('aventurier_action_s'), '#aventurier-body-mainpanel');
    return false;
}
// Fin du donjon
function Av_Action_F()
{
    Av_mapDestroy();
    $.get(Routing.generate('aventurier_findonjon')).done(function(data) {
        //console.log(data);
        Av_Reload_Interface();
        set_background('taverne_01');
        $('.av-mode-donjon').fadeOut();
        updateXp();
        updateVie();
        $.dGame.jsobserver.queue({code: 'aventurier.pact'});
        $.dGame.jsobserver.queue({code: 'aventurier.pdep'});
        $.dGame.jsobserver.queue({code: 'aventurier.argent'});
        //updateAffichageDonneeNoVal('PA');
        //updateAffichageDonneeNoVal('PD');
        //updateAffichageDonneeNoVal('ARGENT');
    });
    return false;
}

//
// interactions
//
function av_ramasser_inv(idi)
{
    $.get(Routing.generate('inventaire_ramasser', {'id': idi})).done(function(data) {
        if (data == 1) {
            Av_Reload_Inventaire();
            var map = $.dGame.getMapByName("carte_aventurier");
            $.dGame.jsobserver.queue({code: "piece_" + map.data("id") + ".reloadElements"});
        } else {
            set_error("Impossible de prendre cet objet !");
        }
    });
}

function av_attaquer(k) {
    var map = $.dGame.getMapByName("carte_aventurier");

    $.get(Routing.generate('aventurier_attaquer', {'k': k})).done(function(data) {
        if (data == 'ok') {
            $.dGame.jsobserver.queue({code: 'aventurier.pact'});
            $.dGame.jsobserver.queue({code: 'aventurier.pvie'});
            $.dGame.jsobserver.queue({code: 'aventurier.experience'});
            $.dGame.jsobserver.queue({code: "piece_" + map.data("id") + ".reloadElements"}, true);
            updateXp();
            updateVie();
        } else {
            set_error(data);
        }

    });
}

function updateVie() {
    getDonnee('PVIE', function(data) {
        data = parseInt(data);
        $(".progressbar-PV").progressbar("option", {value: data});
        //updateAffichageDonnee('data-aventurier-PV', data);
    });
}

function updateXp() {
    $.get(Routing.generate('aventurier_getexperience')).done(function(data) {
        data = data.split(',');
        var level = parseInt(data[0]);
        var xpb = parseInt(data[1]);
        var xp = parseInt(data[2]);
        var xpmax = parseInt(data[3]);
        var xpreel = parseInt(data[4]);
        $(".progressbar-XP").progressbar("option", {max: xpmax});
        $(".progressbar-XP").progressbar("option", {value: xp});
        updateAffichageDonnee('data-aventurier-EXPERIENCE', xpreel);
        updateAffichageDonnee('data-aventurier-NIVEAU', level);
        //updateAffichageDonneeNoVal('EXPERIENCE_NEXT_LEVEL');
    });
}


function av_add_points(carac) {
    $.get(Routing.generate('aventurier_attribuerpoint', {'c': carac})).done(function(data) {
        if (typeof(data) == 'string' && isNaN(data)) {
            set_error(data);
        } else {
            Av_Reload_Fiche();
            /*updateAffichageDonnee('data-aventurier-POINTS',data);
             updateAffichageDonneeNoVal(carac);
             if (parseInt($.trim(data))==0)
             $('.av-add-points').hide();
             else
             $('.av-add-points').show();
             */
        }
    });
}


//
// enregistrement d'une nouvelle organisation
// params : 
// - formu : le formulaire de saisie
// - mode : dialog ou http
function submit_orga_crea_form(formu, mode)
{
    if (mode == 'dialog') {
        set_error(' @todo ');
        //uri = dir + '_ajax_query.php/aventurier/orga_crea_valid?';
        uri = Routing.generate('organisation_creervalid');
        for (var i = 0; i < formu.length; i++) {
            uri = uri + formu[i].name + "=" + formu[i].value + "&";
        }

        $.get(uri).done(function(data) {
            if (data == 'ok') {
                set_dialog({title: "Groupe"}, "Le groupe a été créé avec succés !", "txt");
            } else {
                alert("Erreur lors de la création du groupe !")
            }
        });
        return false;
    } else {
        formu.action = mode;
        return true;
    }
}

/********************
 * ORGANISATION	    *
 ********************/

/**
 * @param {form} f
 * @returns {Boolean}
 */
function organisation_submit(f)
{
    var data = $(f).serialize();
    console.log("send : " + data);
    start_loading();
    $.post(Routing.generate('organisation_creer'), data, function(results) {
        stop_loading();
        console.log(results);
    });
    return false;

}

/**
 * Ouvre la fiche détaillée de l'organisation
 * @param {int} oid
 * @param {string} nom
 */
function organisation_panel(oid, nom)
{
    if ($("#dialog-inline-organiastion").length > 0) {
        $("#dialog-inline-organiastion").dialog("close");
    }

    $('body').append('<div id="dialog-inline-organiastion" class="hide">Chargement... !</div>');

    $("#dialog-inline-organiastion").dialog({
        title: nom,
        closeOnEscape: true,
        width: 800,
        height: 600,
        maxWidth: $(document).width() - 100,
        maxHeight: $(document).height() - 100,
        resizable: true,
        movable: true,
        close: function(event, ui) {
            $(this).dialog("destroy");
            $("#dialog-inline-organiastion").remove();
        }
    });

    loadInLayer(Routing.generate('organisation_fichedetaillee', {'id': oid}), '#dialog-inline-organiastion');

}

function organisation_mbsetetat(uri_cmd, uri_success, id_success)
{
    $.get(uri_cmd)
            .done(function(data) {
        set_info(data);
        loadInLayer(uri_success, id_success);
    })
            .fail(function(err) {
        set_error($(err.responseText).find('h1').html());
    });
}

function organisation_mbsetdroit(ido, ida, droit, valid)
{
    var etat = (valid) ? 1 : 0;
    $.get(Routing.generate('organisationapp_setdroit', {orga: ido, id: ida, droit: droit, etat: etat}))
            .fail(function(err) {
        set_error($(err.responseText).find('h1').html());
        if (valid)
            $('#orgambdroitcb-o' + ido + '-a' + ida + '-d' + droit).removeAttr('checked');
        else
            $('#orgambdroitcb-o' + ido + '-a' + ida + '-d' + droit).attr('checked', 'checked');
    });
}

function organisation_mbsetcacher(ido, cacher)
{
    var c = (cacher) ? 1 : 0;
    $.get(Routing.generate('organisationapp_setcacher', {orga: ido, cacher: c}))
            .fail(function(err) {
        set_error($(err.responseText).find('h1').html());
    });
}



    $(document).bind('keydown', 'Shift+f', function(evt) {  aventurier_showpan('fiche');   return false;  });
    $(document).bind('keydown', 'Shift+i', function(evt) {  aventurier_showpan('inventaire');   return false;  });
    $(document).bind('keydown', 'Shift+r', function(evt) {  aventurier_showpan('relations');   return false;  });
    $(document).bind('keydown', 'Shift+s', function(evt) {  aventurier_showpan('stats');   return false;  });
    
    $(document).bind('keydown', 'Shift+up', function(evt) {  Av_deplacer('n');  return false;  });
    $(document).bind('keydown', 'Shift+left', function(evt) {  Av_deplacer('w');  return false;  });
    $(document).bind('keydown', 'Shift+right', function(evt) {  Av_deplacer('e');  return false;  });
    $(document).bind('keydown', 'Shift+down', function(evt) {  Av_deplacer('s');  return false;  });