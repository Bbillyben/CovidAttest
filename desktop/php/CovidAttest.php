<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('CovidAttest');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
   <div class="col-xs-12 eqLogicThumbnailDisplay">
  <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
  <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction logoPrimary" data-action="add">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter}}</span>
    </div>
      <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
      <i class="fas fa-wrench"></i>
    <br>
    <span>{{Configuration}}</span>
  </div>
  </div>
  <legend><i class="fas fa-table"></i> {{Mes templates}}</legend>
	   <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
<div class="eqLogicThumbnailContainer">
    <?php
// Affiche la liste des équipements
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
	echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
	echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
	echo '<br>';
	echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
	echo '</div>';
}
?>
</div>
</div>

<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
    <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
    <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
  </ul>
  <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
    <div role="tabpanel" class="tab-pane active" id="eqlogictab">
      <br/>
    <form class="form-horizontal">
        <fieldset>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Nom de l'équipement template}}</label>
                <div class="col-sm-3">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement template}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                <div class="col-sm-3">
                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                        <option value="">{{Aucun}}</option>
                        <?php
foreach (jeeObject::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
                   </select>
               </div>
           </div>
	   <div class="form-group">
                <label class="col-sm-3 control-label">{{Catégorie}}</label>
                <div class="col-sm-9">
                 <?php
                    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                    echo '<label class="checkbox-inline">';
                    echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                    echo '</label>';
                    }
                 ?>
               </div>
           </div>
	<div class="form-group">
		<label class="col-sm-3 control-label"></label>
		<div class="col-sm-9">
			<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
			<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
		</div>
	</div>
       <div class="form-group">
        <label class="col-sm-3 control-label">{{Nom de l'utilisateur}}</label>
        <div class="col-sm-3">
            <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_name" placeholder="Dupont"/>
        </div>
       </div>
       <div class="form-group">
           <label class="col-sm-3 control-label">{{Prenom de l'utilisateur}}</label>
        <div class="col-sm-3">
            <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_firstname" placeholder="Camille"/>
        </div>
    </div>
    <div class="form-group">
           <label class="col-sm-3 control-label">{{Date de Naissance}}</label>
        <div class="col-sm-3">
            <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_ddn" placeholder="01/01/1970"/>
        </div>
    </div>
    <div class="form-group">
           <label class="col-sm-3 control-label">{{Ville de naissance}}</label>
        <div class="col-sm-3">
            <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_btown" placeholder="Somewhere"/>
        </div>
    </div>
    <div class="form-group">
                  <label class="col-sm-3 control-label help" data-help="{{si coché, Récupère automatiquement l\'addresse renseignée dans la configuration de jeedom}}">{{Utiliser l'adresse de jeedom}}</label>
                  <div class="col-sm-9">
                   <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="use_jeeadd"/>
                 </div>
             </div>
	<div class="adress_group" style="display: ;">
    <div class="form-group">
           <label class="col-sm-3 control-label">{{Adresse}}</label>
        <div class="col-sm-3">
            <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_adress" placeholder="Somewhere but now"/>
        </div>
    </div>
     <div class="form-group">
           <label class="col-sm-3 control-label">{{Code postal}}</label>
        <div class="col-sm-3">
            <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_zip" placeholder="00666"/>
        </div>
    </div>
     <div class="form-group">
           <label class="col-sm-3 control-label">{{Ville}}</label>
        <div class="col-sm-3">
            <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_ctown" placeholder="Here"/>
        </div>
    </div>
  </div>
    <div class="form-group">
		<label class="col-sm-3 control-label help" >{{Commande d'envoi}}</label>
		<div class="col-sm-3">
			<div class="input-group">
				<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="sendCmd"/>
				<span class="input-group-btn">
					<button type="button" class="btn btn-default cursor listCmdActionMessage tooltips" title="{{Rechercher une commande}}" data-input="sendCmd"><i class="fas fa-list-alt"></i></button>
				</span>
			</div>
		</div>
	</div>
    
    <div class="form-group">
        <label class="col-sm-3 control-label help" data-help="{{choisissez le type d'équipement}}">{{Type Equipement}}</label>
            <div class="col-sm-3">

              <select id="option_confId" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="option_typeEq">
              	<option value='telegram'>Telegram</option>
              	<option value='mail'>Mail</option>
              	<option value='custom'>Custom</option>
              </select>

        </div>
    </div>
    
<div class="send_option_group" > 
    <div class="form-group">
         <label class="col-sm-3 control-label help" data-help="{{utiliser #pdfURL#, et #qrcURL# pour sepcifier les url des fichiers du pdf de l'attestation et du png du QRcode}}">{{Option de la commande}}</label>
        <div class="col-sm-3">
            <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="option_sendcmd" placeholder="ex: file=#qrcURL#,#pdfURL#"/>
        </div>
    </div>
    
              
       
   	<div class="form-group">
           <label class="col-sm-3 control-label help" data-help="{{pour choisir si le titre ou le corps du message sera utilisé pour transmettre les fichiers}}">{{destination}}</label>
           <div class="col-sm-3">
          
            <select id="option_confId" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="option_conf">
                        <option value="title">titre</option>
           				<option value="message">message</option>
            </select>
           
        </div>
    </div>
    
</div>

  <div class="form-group">
           <label class="col-sm-3 control-label">{{Options}}</label>
           <div class="col-sm-9">
            	<label class="checkbox-inline help" data-help="{{si coché, envoi le pdf}}">
           		<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="option_sendPDF"/>{{Envoi du PDF}}
                </label>
                <label class="checkbox-inline help" data-help="{{si coché, envoi le png du QRcode}}">
                <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="option_sendQRC"/>{{Envoi du QRcode}}
                </label>
                <label class="checkbox-inline help" data-help="{{si coché, ajoute une seconde page au pdf avec le QRcode en grand}}">
                <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="option_addpage"/>{{Ajout de la seconde page}}
                </label>
           </div>
  </div>
                  
 <div class="form-group">
                  <label class="col-sm-3 control-label help" data-help="{{Désactiver la suppression auto des fichiers}}">{{Supression auto}}</label>
                  <div class="col-sm-9">
                   <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="auto_remove"/>
                   <span class='warning_autoremove' style="display: none;color:orange;">Attention, vous devrez supprimer manuellement les fichiers par la commande 'supprimer les fichiers'</span>
                 </div>
                
             </div>
 
</fieldset>
</form>
</div>
      <div role="tabpanel" class="tab-pane" id="commandtab">
<a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"><i class="fa fa-plus-circle"></i> {{Commandes}}</a><br/><br/>
<table id="table_cmd" class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th>{{Nom}}</th><th>{{Type}}</th><th>{{Action}}</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</div>
</div>

</div>
</div>

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, nom_du_plugin) -->
<?php include_file('desktop', 'CovidAttest', 'js', 'CovidAttest');?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js');?>