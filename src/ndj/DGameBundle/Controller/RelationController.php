<?php

namespace ndj\DGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RelationController extends Controller
{
	
	

    
    public function relation_new_nameseak()
    {
		$term = trim(strip_tags($_GET['term']));
    	$res = db::get()->query('SELECT idAVENTURIER as id, NOM as value FROM AVENTURIER WHERE NOM LIKE "%'.$term.'%"')->fetchAll();
		$this->out .= json_encode($res);
    }
    
    public function relation_new_form($_return=false)
    {
    	$out = '';
    	$out .= <<<TMLFORMRELATION
<script>
$(function() {
	$( "#relationform-nom-aventurier" ).autocomplete({
		source: dir+"_ajax_query.php/aventurier/relation_new_nameseak",
        minLength: 2,
        select: function( event, ui ) {
        	if (ui.item) {
				$('#relationform-id-aventurier').val(ui.item.id);
				$('#relationform-nom-aventurier').val(ui.item.value);
			}
		}
	});
});
</script>
    	<form onsubmit="return void(0);">
    		<div class="form_relation form_newrelation_1">
    		Recherche un aventurier : <input type="text" id="relationform-nom-aventurier" name="NOM" value="" />
    		<input type="hidden" id="relationform-id-aventurier"  name="idAVENTURIER" value="" />
    		<br /><input type="button" onclick="$(this).parent().hide();$('.form_newrelation_2').show();" value="Suite"/>
    		</div>
    		<div class="form_relation form_newrelation_2 hide">
    		Pour : <input type="radio" name="NOM2" value="" />
    		<br /><input type="button" onclick="$(this).parent().hide();$('.form_newrelation_3').show();" value="Suite"/>
    		
    		</div>
    		<div class="form_relation form_newrelation_3 hide">
    		<input type="submit" value="ok">
    		</div>
    	</form>
    	
TMLFORMRELATION;
    	
    	if ($_return) {
    		return $out;
    	} else {
    		$this->out = $out;
    	}
    }
    
    public function relation_del()
    {
    	if (isset($_GET['idRealtion'])) {
			$r = new relation($_GET['idRelation']);
			$r->effacer();
    	}
    	if (isset($_GET['d'])) $this->interfaceRelations();
    }
}
