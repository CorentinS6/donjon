function setCheck(form, field, check)
{
	for (var temp in document[form][field+"[]"])
		document[form][field+"[]"][temp].checked = check;
}

function confirm2url(url, txt)
{
	if (confirm(txt))
		window.location.href = url;
}

function OuvrirFenetre(url,nom,details) 
{
	window.open(url,nom,details);
}

var AjaxQuery = null;
function ajax_query(url, id_res, vars, method)
{
	if (AjaxQuery && AjaxQuery.transport && AjaxQuery.transport.readyState != 4)
	{
		AjaxQuery.transport.abort();
	}	
	$('waiting').show();
	AjaxQuery = new Ajax.Request(url, {
		method: method,
		parameters : vars,
		encoding:  'ISO-8859-1', //UTF-8 //*/
		onSuccess: function(transport) {
			$('waiting').hide();
			if (id_res != null)
				$(id_res).update(transport.responseText);
		}
	}
	);

}


function ajax_query_form(url, id_res, form, method)
{
	ajax_query(url, id_res, Form.serializeElements( $(form).getElements() ), method);
	return false;
}

function hide_show(id)
{
	if ($(id).visible())
		$(id).hide();
	else
		$(id).show();
}

function info(item, msg)
{
	item.onmouseout = $('infos').hide();
	$('infos').update(msg);
	$('infos').show();
	
}


// Traiter le montant
function stateChangedAQ()
{ 
	if (xmlHttp.status == 200) {
	if (   xmlHttp.readyState == 4
		|| xmlHttp.readyState == "complete") { 
		
		}
	} 
}

function checkAll(f, fi,checkVal)
{
	var liste = $(f).getInputs('checkbox');
	for(var i in liste)
	{
		if (liste[i].type=="checkbox")
			liste[i].checked = checkVal;
	}
}