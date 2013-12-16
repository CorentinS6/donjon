//modeDeJeu = window.location.href.split('index.php/').pop().substring(0,1);


// Pano chargement
var _loading_counter = 0;
function start_loading()
{
    _loading_counter++;
    if (_loading_counter == 1)
    {
        $('body').append('<div id="loading" class="ui-widget-content smaller2 ui-corner-all o90 hide"><img src="' + img_dir + 'interface/wait3.gif" align="absmiddle" /> Chargement...</div>');
        $("#loading").fadeIn('fast');
    }
}
function stop_loading()
{
    _loading_counter--;
    if (_loading_counter == 0)
    {
        $("#loading").fadeOut('fast', function() {
            $(this).remove();
        });
    }
}

function appendInLayer(cmd, layer, _call)
{
    start_loading();
    $.get(cmd)
            .done(function(data) {
        $(layer).append(data);
        if (_call != undefined && _call != null) {
            _call();
        }
    })
            .fail(function(err) {
        set_erro("Une erreur s'est produite lors du chargement : " + $(err.responseText).find('h1').html());
    })
            .always(function() {
        stop_loading();
    });
}

function loadInLayer(cmd, layer, _call)
{
    start_loading();
    $.get(cmd)
            .done(function(data) {
        $(layer).html(data);
        if (_call != undefined && _call != null) {
            _call();
        }
    })
            .fail(function(err) {
        $(layer).html("Une erreur s'est produite lors du chargement : " + $(err.responseText).find('h1').html());
    })
            .always(function() {
        stop_loading();
    });
}

function set_background(code_fond, f)
{
    if (f == null || f == undefined)
        f = '#aventurier-body';
    if (code_fond == null) {
        $(f).css('backgroundImage', 'none');
    }

    uri = img_dir + 'interface/fonds/' + code_fond + '.jpg';

    $('body').append('<img class="__Temp_Aventurer_BackgroundLoader" style="display:none;" />');
    $('.__Temp_Aventurer_BackgroundLoader').attr('src', uri).load(function() {
        $(f).delay(100).css('backgroundImage', 'url("' + uri + '")');
        $(this).delay(200).remove();
    });
}

function initMarche()
{
    var $panels = $('#slider .scrollContainer > div');
    var $container = $('#slider .scrollContainer');

    // if false, we'll float all the panels left and fix the width 
    // of the container
    var horizontal = true;
    var w = $('#slider .scroll').css('width');
    var h = $('#slider .scroll').css('height');

    // float the panels left if we're going horizontal
    if (horizontal) {
        $panels.css({
            'float': 'left',
            'width': w,
            'height': h,
            'maxHeight': h,
            'overflow': 'auto',
            'position': 'relative' // IE fix to ensure overflow is hidden
        });

        // calculate a new width for the container (so it holds all panels)
        $container.css('width', $panels[0].offsetWidth * $panels.length);
    }

    // collect the scroll object, at the same time apply the hidden overflow
    // to remove the default scrollbars that will appear
    var $scroll = $('#slider .scroll').css('overflow', 'hidden');

    // apply our left + right buttons
    $scroll
            .before('<img class="scrollButtons left" src="images/scroll_left.png" />')
            .after('<img class="scrollButtons right" src="images/scroll_right.png" />');

    // handle nav selection
    function selectNav() {
        $(this)
                .parents('ul:first')
                .find('a')
                .removeClass('selected')
                .end()
                .end()
                .addClass('selected');
    }

    $('#slider .navigation').find('a').click(selectNav);

    // go find the navigation link that has this target and select the nav
    function trigger(data) {
        var el = $('#slider .navigation').find('a[href$="' + data.id + '"]').get(0);
        selectNav.call(el);
        if (data != null && data.id != undefined)
        {
            var code = data.id.substring(6).toLowerCase();
            set_background('marche_' + code);
            set_background('marche_' + code, '#economat-main-panel');
        }

    }

    if (window.location.hash) {
        trigger({id: window.location.hash.substr(1)});
    } else {
        $('ul.navigation a:first').click();
    }

    // offset is used to move to *exactly* the right place, since I'm using
    // padding on my example, I need to subtract the amount of padding to
    // the offset.  Try removing this to get a good idea of the effect
    var offset = parseInt((horizontal ?
            $container.css('paddingTop') :
            $container.css('paddingLeft'))
            || 0) * -1;


    var scrollOptions = {
        target: $scroll, // the element that has the overflow

        // can be a selector which will be relative to the target
        items: $panels,
        navigation: '.navigation a',
        // selectors are NOT relative to document, i.e. make sure they're unique
        prev: 'img.left',
        next: 'img.right',
        // allow the scroll effect to run both directions
        axis: 'xy',
        onAfter: trigger, // our final callback

        offset: offset,
        // duration of the sliding effect
        duration: 500,
        // easing - can be used with the easing plugin: 
        // http://gsgd.co.uk/sandbox/jquery/easing/
        easing: 'swing'
    };

    // apply serialScroll to the slider - we chose this plugin because it 
    // supports// the indexed next and previous scroll along with hooking 
    // in to our navigation.
    $('#slider').serialScroll(scrollOptions);

    // now apply localScroll to hook any other arbitrary links to trigger 
    // the effect
    $.localScroll(scrollOptions);

    // finally, if the URL has a hash, move the slider in to position, 
    // setting the duration to 1 because I don't want it to scroll in the
    // very first page load.  We don't always need this, but it ensures
    // the positioning is absolutely spot on when the pages loads.
    scrollOptions.duration = 1;
    $.localScroll.hash(scrollOptions);

}


/**
 * @deprecated ?
 */
function getDonnee(type, _call) {
    $.get(Routing.generate('gamecommon_getdonnee', {'data': type}), _call);
    /*if (modeDeJeu=='d')
     $.get(dir+'_ajax_query.php/donjon/getDonnee?data='+type, __call);
     else
     $.get(dir+'_ajax_query.php/aventurier/getDonnee?data='+type, __call);*/
}

function updateAffichageDonnee(type, val) {
    $('.' + type).html(val);
    // action spécifique	
    switch (type) {
        case 'data-aventurier-POINTS':
            if (val > 0)
                $(".av-add-points").show();
            else
                $(".av-add-points").hide();
            break;
    }
}

/**
 * @param type
 */
function updateAffichageDonneeNoVal(type)
{
    var mode = (modeDeJeu == 'd') ? 'donjon' : 'aventurier';
    $.get(Routing.generate('gamecommon_getdonnee', {'data': type}), function(data) {
        //console.log(data)
        updateAffichageDonnee('data-' + mode + '-' + type, data);
    });
}

/**
 * @deprecated ?
 */
function setVal(OBJ, idO, VAR, VAL)
{

    $.get(Routing.generate('gamecommon_setval', {'obj': OBJ, 'id': idO, 'var': VAR, 'val': VAL}), function(data) {
        if (data != '' && data != VAL) {
            alert(data);
        }
    });
}

$(document).ready(function() {
    $.tablesorter.addParser({
        id: "PO",
        is: function(s) {
            // return false so this parser is not auto detected 
            return false;
        },
        format: function(s) {
            // format your data for normalization 
            return parseFloat(s.replace(/ PO/, ""));
        },
        // set type, either numeric or text 
        type: "numeric"
    });
});

function displayFiche(type, idf) {
    set_dialog({
        closeOnEscape: true,
        title: "Fiche",
        modal: false,
        width: 400,
        height: 300,
        close: function(event, ui) {
            $(this).html('Chargement...');
        }
    },
    Routing.generate(type + '_displayfiche', {'id': idf})
            );
}

// Top Menu box
$(document).ready(function() {
    $("#topMenuBox .links").click(function() {
        $('#topMenuBox .content').slideToggle(200)
    });
    
});


//////////
// CHAT	//
//////////
var _chat_history_cursor = -1; 		// cursor de l'historique
var _chat_history_max = 100;		// -1 = infinie, sinon un nombre à partir de 0
var _chat_history = new Array();	// tableau de l'historique
var _chat_waitforsend = 0;			// attendre avant l'envoie du message
var _chat_timeout = null;			// timout
var __chat_showmsg = [1, 1, 1];		// afficher/cacher les types de message (system, public, prive)
var __chat_showmsgtype = ["system", "public", "prive"];

function chat_timer() {
    clearTimeout(_chat_timeout);
    chat_reload();
    _chat_timeout = setTimeout('chat_timer()', 2000);
}

function reload_chat() {
    chat_reload();
}

function chat_reload()
{
    return;
    if (_chat_waitforsend > 0) {
        setTimeout('chat_reload()', 100);
        return;
    }
    _chat_waitforsend++;
    var url = Routing.generate('chatmsg_reload', {lastmsg: $("#chat-input-lastmsg").val()});
    $.get(url)
            .done(function(data) {
        //console.log(data);
        // rajouter à la fin + scroll
        $('#chat-mainwrapper').append(data).scrollTo({top: '100%', left: '0'});

        // caché si besoin
        for (var i in __chat_showmsg) {
            if (__chat_showmsg[i] == 0) {
                $('#chat-mainwrapper .chat-line-' + __chat_showmsgtype[i]).hide();
            }
        }

    })
            .fail(function(err) {
        set_error("Erreur dans le rechargement du chat !")
    })
            .always(function() {
        _chat_waitforsend--;
    });
}

function chat_history_reset_cursor()
{
    _chat_history_cursor = -1;
}

function chat_history_prev()
{
    i = _chat_history_cursor - 1;
    if (i >= 0 && _chat_history[i] != undefined)
    {
        _chat_history_cursor = i;
        return _chat_history[i];
    }
    else
    {
        return null;
    }
}

function chat_history_for()
{
    i = _chat_history_cursor + 1;
    if (i >= 0 && _chat_history[i] != undefined)
    {
        _chat_history_cursor = i;
        return _chat_history[i];
    }
    else
    {
        return null;
    }
}

function chat_history_add(cmd)
{
    chat_history_reset_cursor();
    _chat_history.unshift(cmd);
    if (_chat_history_max >= 0 && _chat_history.length > _chat_history_max)
    {
        _chat_history.pop();
    }
}

function chat_send_message()
{
    return;
    var inputItem = $('.chat-inputtext');
    if ($.trim(inputItem.val()) != '') {
        inputItem.prop('disabled', 'disabled');

        if (_chat_waitforsend > 0) {
            setTimeout('chat_send_message()', 100);
            return;
        }
        _chat_waitforsend++;
        inputItem.css('backgroundImage', 'url("' + img_dir + '/interface/wait3.gif"');

        // console.log(Routing.generate('chatmsg_send',{command:inputItem.val()}))

        $.get(Routing.generate('chatmsg_send', {command: inputItem.val()}))
                .done(function(data) {
            $('#chat-mainwrapper').append(data).scrollTo({top: '100%', left: '0'});
            chat_history_add(inputItem.val());
            inputItem.val('');
            chat_reload();
        })
                .fail(function(err) {
            set_error("Erreur lors de l'envoie du message !")
        })
                .always(function() {
            inputItem.removeProp('disabled');
            inputItem.css('backgroundImage', 'none');
            _chat_waitforsend--;
        })
    }
}

function __chat_togglemessage(type)
{
    __chat_showmsg[type] = (__chat_showmsg[type] == 1) ? 0 : 1;
    if (__chat_showmsg[type] == 1) {
        $('#chat-mainwrapper .chat-line-' + __chat_showmsgtype[type]).show();
    } else {
        $('#chat-mainwrapper .chat-line-' + __chat_showmsgtype[type]).hide();
    }
}

$(document).ready(function() {

    chat_timer();

    $('.chat-inputtext').keydown(function(e) {
        if (e.keyCode == 13) {
            chat_send_message();
        } else if (e.keyCode == 38) { // up = ancien
            var v = chat_history_for();
            if (v != null && v.length > 0 && v != '')
                this.value = v;
        } else if (e.keyCode == 40) { // down = récent
            var v = chat_history_prev();
            if (v != null && v.length > 0 && v != '')
                this.value = v;
        }
    });
});

function toggleChat() {
    return;
    $("#chat-main").slideToggle(250);
    $('#chat-mainwrapper').scrollTo({top: '100%', left: '0'});
}

function chat_msg_prive(n) {
    var inp = '@"' + n + '" ';
    $('.chat-inputtext').val(inp);
    if (!$("#chat-main").is(':visible'))
        toggleChat();
}

//
// DIALOG A LA VOLEE
//	type = cmd, txt
//
function set_dialog(_o, cmd, typ) {
    if (typ == undefined || typ == null)
        typ = 'cmd';

    if ($("#dialog-inline").length > 0) {
        $("#dialog-inline").dialog("close");
    }
    if (typ == 'cmd') {
        $('body').append('<div id="dialog-inline" class="hide"><img src="' + img_dir + 'interface/wait3.gif" /> Chargement... !</div>');
        loadInLayer(cmd, '#dialog-inline');
    } else if (typ == 'txt') {
        $('body').append('<div id="dialog-inline" class="hide">' + cmd + '</div>');
    }

    var opt = {
        title: 'Dialog',
        closeOnEscape: true,
        width: 500,
        height: "auto",
        maxWidth: $(document).width() - 100,
        maxHeight: $(document).height() - 100,
        close: function(event, ui) {
            $(this).dialog("destroy");
            $("#dialog-inline").remove();
        }
    };
    for (var i in _o) {
        opt[i] = _o[i];
    }

    $("#dialog-inline").dialog(opt);
}


function set_layer(_o, cmd, typ) {
    if (typ == undefined || typ == null)
        typ = 'cmd';

    if (typ == 'cmd') {
        $('body').append('<div id="layer-inline" class="hide"><img src="' + img_dir + 'interface/wait3.gif" /> Chargement... !</div>');
        loadInLayer(cmd, '#layer-inline');
    } else if (typ == 'txt') {
        $('body').append('<div id="layer-inline" class="hide">' + cmd + '</div>');
    }

    var opt = {
        closeOnEscape: true,
        width: 500,
        height: "auto",
        maxWidth: $(document).width() - 100,
        maxHeight: $(document).height() - 100,
        resizable: false,
        modal: true,
        close: function(event, ui) {
            $(this).dialog("destroy");
            $("#layer-inline").remove();
        }
    };
    for (var i in _o) {
        opt[i] = _o[i];
    }

    $("#layer-inline").dialog(opt).siblings('.ui-dialog-titlebar').remove()
}

//
// ERROR DIAL
//
var __error_counter = 0;
var __error_time = 10000;
function set_error(msg, textStatus) //msg = jqXHR, textStatus, errorThrown
{
    if(typeof(msg)!=='string') {
        var msg2 = $(msg.responseText).find('h1').html();
        if (typeof(msg2) === 'undefined') {
            msg = "Erreur : " + msg;
        } else {
            msg = msg2;
        }
    }
    var n = __error_counter++;
    var q = $('#error-message-queue');
    if (q.length == 0) {
        $('body').append('<div id="error-message-queue"></div>');
        q = $('#error-message-queue');
    }
    q.append('<div id="error-message-' + n + '" class="ui-widget hide error-message o80"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p class="smaller"><span class="ui-icon ui-icon-close pointer" style="float: right; margin-right: .3em;" onclick="$(this).parent().parent().fadeOut().remove()"></span><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + msg + '</p></div></div>');
    $('#error-message-' + n).fadeIn(100)
            .delay(__error_time)
            //.fadeOut(500)
            .fadeOut(500, function() {
        $(this).remove();
    });

}

function set_info(msg)
{
    var n = __error_counter++;
    var q = $('#error-message-queue');
    if (q.length === 0) {
        $('body').append('<div id="error-message-queue"></div>');
        q = $('#error-message-queue');
    }
    q.append('<div id="error-message-' + n + '" class="ui-widget hide error-message o80"><div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"><p class="smaller"><span class="ui-icon ui-icon-close pointer" style="float: right; margin-right: .3em;" onclick="$(this).parent().parent().fadeOut().remove()"></span><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>' + msg + '</p></div></div>');
    $('#error-message-' + n).fadeIn(100)
            .delay(__error_time)
            //.fadeOut(500)
            .fadeOut(500, function() {
        $(this).remove();
    })
}

$.fn.clignote = function() {
    return this.each(function() {
        $(this).animate({opacity: 0.1}, {duration: 250, easing: "linear"})
                .delay(100)
                .animate({opacity: 1}, {duration: 250, easing: "linear"});
    });
    //fadeOut(250).delay(100).fadeIn(250).fadeOut(250).delay(100).fadeIn(250);
};

/**
 * @deprecated use $(selector).clignote() now
 */
function clignote(sel) {
    $(sel).clignote();
}


function listing_evenement(j, cpt, layer) {
    n = parseInt($(cpt).html());
    $(cpt).html(n + 20);
    appendInLayer(Routing.generate('evenement_listing', {'user': j, 'offset': n}), layer);
}

function sub_checklogin(l) {
    if (l.length > 3)
        loadInLayer('user/sub_check_login?l=' + l, 'sub_check_login');
}
function sub_checkmail(l) {
    var txt = checkMail(l) ?
            '<span class="color-green">Votre adresse e-mail est valide.</span>'
            : '<span class="color-red">Votre adresse e-mail est invalide !</span>';
    $('#sub_check_courriel').html(txt);
}
function sub_checkpwd(l, n) {
    autre = (n == 1) ? 2 : 1;
    autre_val = $("input[name='Mdp" + autre + "']").val();
    if (l.length > 4 && l == autre_val) {
        $('#sub_check_mdp').html('<span class="color-green">Mot de passe ok !</span>');
    } else {
        $('#sub_check_mdp').html('<span class="color-red">Vos deux mot de passe doivent correspondrent.<br />Un mot de passe doit faire au moins 5 caractères !</span>');
    }
}

function checkMail(m) {
    return (/^[a-z0-9-_.]+@([a-z0-9]([a-z0-9-]){0,62}\.){1,127}(AC|AD|AE|AERO|AF|AG|AI|AL|AM|AN|AO|AQ|AR|ARPA|AS|AT|AU|AW|AZ|BA|BB|BD|BE|BF|BG|BH|BI|BIZ|BJ|BM|BN|BO|BR|BS|BT|BV|BW|BY|BZ|CA|CAT|CC|CD|CF|CG|CH|CI|CK|CL|CM|CN|CO|COM|COOP|CR|CU|CV|CX|CY|CZ|DE|DJ|DK|DM|DO|DZ|EC|EDU|EE|EG|ER|ES|ET|EU|FI|FJ|FK|FM|FO|FR|GA|GB|GD|GE|GF|GG|GH|GI|GL|GM|GN|GOV|GP|GQ|GR|GS|GT|GU|GW|GY|HK|HM|HN|HR|HT|HU|ID|IE|IL|IM|IN|INFO|INT|IO|IQ|IR|IS|IT|JE|JM|JO|JOBS|JP|KE|KG|KH|KI|KM|KN|KR|KW|KY|KZ|LA|LB|LC|LI|LK|LR|LS|LT|LU|LV|LY|MA|MC|MD|MG|MH|MIL|MK|ML|MM|MN|MO|MOBI|MP|MQ|MR|MS|MT|MU|MUSEUM|MV|MW|MX|MY|MZ|NA|NAME|NC|NE|NET|NF|NG|NI|NL|NO|NP|NR|NU|NZ|OM|ORG|PA|PE|PF|PG|PH|PK|PL|PM|PN|PR|PRO|PS|PT|PW|PY|QA|RE|RO|RU|RW|SA|SB|SC|SD|SE|SG|SH|SI|SJ|SK|SL|SM|SN|SO|SR|ST|SU|SV|SY|SZ|TC|TD|TF|TG|TH|TJ|TK|TL|TM|TN|TO|TP|TR|TRAVEL|TT|TV|TW|TZ|UA|UG|UK|UM|US|UY|UZ|VA|VC|VE|VG|VI|VN|VU|WF|WS|YE|YT|YU|ZA|ZM|ZW)$/i).test(m);
}

function resetPwd(f) {
    set_dialog({title: "Mot de passe"}, 'user/mdpoublie_search?q=' + f.email.value);
    return false;
}

Array.prototype.unset = function(val) {
    var index = this.indexOf(val)
    if (index > -1) {
        this.splice(index, 1)
    }
}



    $(document).bind('keydown', 'Shift+c', function(evt) {  toggleChat();   return false;  });
    $(document).bind('keydown', 'Shift+m', function(evt) {  $('#topMenuBox .content').slideToggle(200);  return false;  });