//
Array.prototype.in_array = function(val)
{
   for (var i in this)
      if (this[i] == val)
         return true;
   return false;
};

// vérification d'un formulaire
//    obj       : objet formulaire
//    except    : tableau de nom des champs à ignorer
function checkForm(obj, except)
{
  var except = (except == undefined) ? new Array() : except;
  for ( var i in obj.elements)
  {
    var field = obj.elements[i];
    var nam   = field.name;
    var typ   = field.type;
    if (!except.in_array(nam))
    {
      switch (field.type)
      {
        // champ text
        case 'text' : case 'textarea' : case 'file' :
             if (field.value.length==0)
             {
               window.alert('Le champ '+( field.title.length > 0 ? field.title : field.name )+' doit être rempli !');
               field.focus();
               return false;
             }
             break;

        // checkbox
        case 'checkbox' :
             if (!field.checked)
             {
               window.alert('Le champ '+( field.title.length > 0 ? field.title : field.name )+' doit être coché !');
               field.focus();
               return false;
             }
             break;

        // checkbox
        case 'radio' :
             var n=false;
             for (var o in field)
                 if(o.checked)
                   n = true;
             if(!n)
             {
               window.alert('Une des cases '+( field.title.length > 0 ? field.title : field.name )+' doit être cochée !');
               field.focus();
               return false;
             }
             break;

        // select
        case 'select' :
             var n=false;
             for (var o in field)
                 if(o.checked)
                   n = true;
             if(!n)
             {
               window.alert('Une des options '+( field.title.length > 0 ? field.title : field.name )+' doit être cochée !');
               field.focus();
               return false;
             }
             break;

        // button,reset,submit,image => on passe
        default : break;
      }
    }
  }
  return true;
};

// vérification de la validité d'une adresse e-mail
function checkMail(m)
{
  return (/^[a-z0-9-_.]+@([a-z0-9]([a-z0-9-]){0,62}\.){1,127}(AC|AD|AE|AERO|AF|AG|AI|AL|AM|AN|AO|AQ|AR|ARPA|AS|AT|AU|AW|AZ|BA|BB|BD|BE|BF|BG|BH|BI|BIZ|BJ|BM|BN|BO|BR|BS|BT|BV|BW|BY|BZ|CA|CAT|CC|CD|CF|CG|CH|CI|CK|CL|CM|CN|CO|COM|COOP|CR|CU|CV|CX|CY|CZ|DE|DJ|DK|DM|DO|DZ|EC|EDU|EE|EG|ER|ES|ET|EU|FI|FJ|FK|FM|FO|FR|GA|GB|GD|GE|GF|GG|GH|GI|GL|GM|GN|GOV|GP|GQ|GR|GS|GT|GU|GW|GY|HK|HM|HN|HR|HT|HU|ID|IE|IL|IM|IN|INFO|INT|IO|IQ|IR|IS|IT|JE|JM|JO|JOBS|JP|KE|KG|KH|KI|KM|KN|KR|KW|KY|KZ|LA|LB|LC|LI|LK|LR|LS|LT|LU|LV|LY|MA|MC|MD|MG|MH|MIL|MK|ML|MM|MN|MO|MOBI|MP|MQ|MR|MS|MT|MU|MUSEUM|MV|MW|MX|MY|MZ|NA|NAME|NC|NE|NET|NF|NG|NI|NL|NO|NP|NR|NU|NZ|OM|ORG|PA|PE|PF|PG|PH|PK|PL|PM|PN|PR|PRO|PS|PT|PW|PY|QA|RE|RO|RU|RW|SA|SB|SC|SD|SE|SG|SH|SI|SJ|SK|SL|SM|SN|SO|SR|ST|SU|SV|SY|SZ|TC|TD|TF|TG|TH|TJ|TK|TL|TM|TN|TO|TP|TR|TRAVEL|TT|TV|TW|TZ|UA|UG|UK|UM|US|UY|UZ|VA|VC|VE|VG|VI|VN|VU|WF|WS|YE|YT|YU|ZA|ZM|ZW)$/i).test(m);
};