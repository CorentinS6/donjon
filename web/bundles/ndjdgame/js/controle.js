// Fonction de controle d'un champ
// Prend en parametres :
//  - l'objet champ de formulaire
//  - le message d'erreur
//  - une expression reguliere (facultative)
function controle(champ, msg, exp) {
	
    // Ensemble de cases à cocher en remplacement d'un select multiple ?
    if (champ.length && champ[0].type == "checkbox") {
        var checked = false ;
        for (var i=0; i<champ.length; i++) {
            if (champ[i].checked) {
                checked = true ;
                break ;
            }
        }
        if (!checked) {
            alert(msg) ;       // Affichage de l'erreur
            champ[0].focus() ; // Focus sur l'element en erreur
            return false ;     // Erreur detectee, retour faux
        }
        else {
            // Tout va bien
            return true ;
        }
    }
    // Ensemble de boutons radio ?
    if (champ.length && champ[0].type == "radio") {
        var checked = false ;
        for (var i=0; i<champ.length; i++) {
            if (champ[i].checked) {
                checked = true ;
                break ;
            }
        }
        if (!checked) {
            alert(msg) ;       // Affichage de l'erreur
            champ[0].focus() ; // Focus sur l'element en erreur
            return false ;     // Erreur detectee, retour faux
        }
        else {
            // Tout va bien
            return true ;
        }
    }

    var incomplet = false ;
    // Selon le type de champ
    switch (champ.type) {
        case 'text':     // Ligne de texte
        case 'textarea': // Zone de texte
        case 'password': // Mot de passe
            // Avons-nous une expression reguliere ?
            if (typeof exp == "undefined") {
                // Non
                if (champ.value == '')
                    incomplet = true ;
            }
            else {
                // Oui
                if (!exp.test(champ.value))
                    incomplet = true ;
            }
            break ;
        case 'select-one':      // Liste a choix unique
            if (champ.selectedIndex == 0)
                incomplet = true ;
            break ;
        case 'select-multiple': // Liste a choix multiple
            if (champ.selectedIndex == -1)
                incomplet = true ;
            break ;
        case 'checkbox':        // Case à cocher
            incomplet = !champ.checked ;
            break ;
    }

    if (incomplet) {
        alert(msg) ;       // Affichage de l'erreur
        champ.focus() ;    // Focus sur l'element en erreur
        return false ;     // Erreur detectee, retour faux
    }

    return true ;
}
