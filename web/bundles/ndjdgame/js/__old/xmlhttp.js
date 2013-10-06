// Recuperer un objet XmlHttpRequest selon le navigateur
function GetXmlHttpObject() {
    // L'objet XmlHttpRequest
    var xmlHttp = null ;

    // Moteur Gecko ?
    if (window.XMLHttpRequest) {
        // Oui
        xmlHttp = new XMLHttpRequest() ;
    }
    else {
        // Non, IE alors ?
        if (window.ActiveXObject) {
            try { // essaie de charger l'objet pour IE
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP") ;
            }
            catch (e) {
                try { // essaie de charger l'objet pour une autre version IE
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP") ;
                }
                catch (e) {
                    window.alert("Votre navigateur ne prend pas en charge l'objet XMLHTTPRequest.") ;
                    xmlHttp = null ;
                }
            }
        }
    }
    return xmlHttp ;
}
