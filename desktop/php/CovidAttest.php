<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
// Déclaration des variables obligatoires
$plugin = plugin::byId('CovidAttest');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<!-- Page d'accueil du plugin -->
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<!-- Boutons de gestion du plugin -->
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
		<legend><i class="fas fa-table"></i> {{Mes Attestations}}</legend>
		<!-- Champ de recherche -->
		<div class="input-group" style="margin:5px;">
			<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic"/>
			<div class="input-group-btn">
				<a id="bt_resetSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i></a>
			</div>
		</div>
		<!-- Liste des équipements du plugin -->
		<div class="eqLogicThumbnailContainer">
			<?php
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
	</div> <!-- /.eqLogicThumbnailDisplay -->

	<!-- Page de présentation de l'équipement -->
	<div class="col-xs-12 eqLogic" style="display: none;">
		<!-- barre de gestion de l'équipement -->
		<div class="input-group pull-right" style="display:inline-flex;">
			<span class="input-group-btn">
				<!-- Les balises <a></a> sont volontairement fermées à la ligne suivante pour éviter les espaces entre les boutons. Ne pas modifier -->
				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
				</a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs">  {{Dupliquer}}</span>
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
		</div>
		<!-- Onglets -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i><span class="hidden-xs"> {{Équipement}}</span></a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-list"></i><span class="hidden-xs"> {{Commandes}}</span></a></li>
		</ul>
		<div class="tab-content">
			<!-- Onglet de configuration de l'équipement -->
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<!-- Partie gauche de l'onglet "Equipements" -->
				<!-- Paramètres généraux de l'équipement -->
				<form class="form-horizontal">
					<fieldset>
						<div class="col-lg-7">
							<legend><i class="fas fa-wrench"></i> {{Général}}</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
								<div class="col-sm-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;"/>
									<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" >{{Objet parent}}</label>
								<div class="col-sm-7">
									<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php
										$options = '';
										foreach ((jeeObject::buildTree(null, false)) as $object) {
											$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
										}
										echo $options;
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
								<label class="col-sm-3 control-label">{{Options}}</label>
								<div class="col-sm-7">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
								</div>
							</div>
							<br>

							<legend><i class="fas fa-user	"></i> {{Identification}}</legend>
							
							<div class="form-group">
								 <label class="col-sm-3 control-label">{{Nom de l'utilisateur}}</label>
								 <div class="col-sm-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_name" placeholder="Dupont"/>
								 </div>
							  </div>
							  <div class="form-group">
								 <label class="col-sm-3 control-label">{{Prénom de l'utilisateur}}</label>
								 <div class="col-sm-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_firstname" placeholder="Camille"/>
								 </div>
							  </div>
							  <div class="form-group">
								 <label class="col-sm-3 control-label">{{Date de Naissance}}</label>
								 <div class="col-sm-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_ddn" placeholder="01/01/1970"/>
								 </div>
							  </div>
							  <div class="form-group">
								 <label class="col-sm-3 control-label">{{Ville de naissance}}</label>
								 <div class="col-sm-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_btown" placeholder="Somewhere"/>
								 </div>
							  </div>
							  <legend><i class="fas maison-house112"></i> {{Adresse}}</legend>
							  <div class="form-group">
								 <label class="col-sm-3 control-label help" data-help="{{si cochée, récupère automatiquement l'addresse renseignée dans la configuration de jeedom}}">{{Utiliser l'adresse de jeedom}}</label>
								 <div class="col-sm-7">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="use_jeeadd"/>
								 </div>
							  </div>
							  <div class="adress_group" style="display: ;">
								 <div class="form-group">
									<label class="col-sm-3 control-label">{{Adresse}}</label>
									<div class="col-sm-7">
									   <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_adress" placeholder="Somewhere but now"/>
									</div>
								 </div>
								 <div class="form-group">
									<label class="col-sm-3 control-label">{{Code postal}}</label>
									<div class="col-sm-7">
									   <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_zip" placeholder="00666"/>
									</div>
								 </div>
								 <div class="form-group">
									<label class="col-sm-3 control-label">{{Ville}}</label>
									<div class="col-sm-7">
									   <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_ctown" placeholder="Here"/>
									</div>
								 </div>
							  </div>
							  <div class="form-group">
								 <label class="col-sm-3 control-label help" data-help="{{pour utiliser une ville différente en signature}}">{{Ville signature}}</label>
								 <div class="col-sm-7">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="use_user_ctown_sign"/>
								 </div>
							  </div>
							  <div class="form-group user_ctown_sign">
								 <label class="col-sm-3 control-label">{{Ville - signature}}</label>
								 <div class="col-sm-7">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user_ctown_sign" placeholder="Here"/>
								 </div>
							  </div>
							  <legend><i class="fas fa-envelope"></i> {{Envoi}}</legend>

							  <div class="form-group">
								 <label class="col-sm-3 control-label help" >{{Commande d'envoi}}</label>
								 
								 <div class="col-sm-7">
									<div class="input-group CA-cmd-el">
									   <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="sendCmd"/>
									   <span class="input-group-btn">
									   <button type="button" class="btn btn-default cursor listCmdActionMessage tooltips" title="{{Rechercher une commande}}" data-input="sendCmd"><i class="fas fa-list-alt"></i></button>
									   </span>
									</div>
									<div class="input-group CA-scenar-el" style="width:100%;">
									   <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="scenarCMD">
									   <?php
										  // Affiche la liste des scénario
										  $scenarios=scenario::all(); //scenario::allOrderedByGroupObjectName();
										  foreach ($scenarios as $scenario) {
										   echo "<option value='".$scenario->getId()."'>".$scenario->getHumanName()."</option>";
										  }
                              
										  ?>    
									   </select>
									</div>
								 </div>
								 <div class="form-group">
								 <label class="col-sm-3 control-label" ></label>
								 <div class="col-sm-7">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="use_scenar"/>
									<label class="control-label help" data-help="Pour choisir un scénario à démarrer par la commande, avec les tags :<br> <ul><li>#pdfURL#, #pngURL#, #qrcURL# pour les chemin des fichier- pdf, pdf Image et QRCode</li><li>#eqID#, #eqNAME# pour l'équipement qui a lancé la scénario,</li><li>#cmdID#, #cmdNAME# pour la commande lancée </li></ul>(cf doc)">{{commande scénario}}</label>						 
								 </div>
							  </div>
								 </div>
							  <div class="form-group CA-cmd-el">
								 <label class="col-sm-3 control-label help" data-help="{{choisissez le type d'équipement}}">{{Type Equipement}}</label>
								 <div class="col-sm-7">
									<select id="option_confId" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="option_typeEq">
									   <option value='telegram'>Telegram</option>
									   <option value='mail'>Mail</option>
									   <option value='pushover'>Pushover</option>
									   <option value='custom'>Custom</option>
									</select>
								 </div>
							  </div>
							  <div class="send_option_group CA-cmd-el" >
								 <div class="form-group">
									<label class="col-sm-3 control-label help" data-help="{{utilisez #pdfURL# (attestation pdf),#pngURL# (attestation format png) et  #qrcURL# (png du qr code) pour spécifier les urls des fichiers du pdf de l'attestation et du png du QRcode}}">{{Option de la commande}}</label>
									<div class="col-sm-7">
									   <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="option_sendcmd" placeholder="ex: file=#qrcURL#,#pdfURL#,#pngURL#"/>
									</div>
								 </div>
								 <div class="form-group">
									<label class="col-sm-3 control-label help" data-help="{{pour choisir si le titre ou le corps du message sera utilisé pour transmettre les fichiers, ou transmis par un array avec les chemin des fichiers}}">{{destination}}</label>
									<div class="col-sm-7">
									   <select id="option_confId" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="option_conf">
										  <option value="title">titre</option>
										  <option value="message">message</option>
										  <option value="files_array">Files (array)</option>
										  <option value="files_string">Files (string)</option>
									   </select>
									</div>
								 </div>
							  </div>
							  <div class="form-group">
								 <label class="col-sm-3 control-label">{{Options}}</label>
								 <div class="col-sm-7">
									<label class="checkbox-inline help" data-help="{{si cochée, envoi le pdf}}">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="option_sendPDF"/>{{Envoi du PDF}}
									</label>
									<label class="checkbox-inline help" data-help="{{si cochée, envoi le png de l'attestation}}">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="option_sendPNG"/>{{Envoi au format image}}
									</label>
									<label class="checkbox-inline help" data-help="{{si cochée, envoi le png du QRcode}}">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="option_sendQRC"/>{{Envoi du QRcode}}
									</label>
									<label class="checkbox-inline help" data-help="{{si cochée, ajoute une seconde page au pdf avec le QRcode grand format}}">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="option_addpage"/>{{Ajout de la seconde page}}
									</label>
								 </div>
							  </div>
							  <div class="form-group">
								 <label class="col-sm-3 control-label help" data-help="{{Désactiver la suppression auto des fichiers, nécessitera une action manuelle pour la suppression}}">{{Désactiver la Suppression auto}}</label>
								 <div class="col-sm-7">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="auto_remove"/>
									<span class='warning_autoremove' style="display: none;color:orange;">Attention, vous devrez supprimer manuellement les fichiers par la commande 'supprimer les fichiers'</span>
								 </div>
							  </div>
							
						</div>

						<!-- Partie droite de l'onglet "Équipement" -->
						<!-- Affiche l'icône du plugin par défaut mais vous pouvez y afficher les informations de votre choix -->
						<div class="col-lg-5">
							<legend><i class="fas fa-info"></i> {{Informations}}</legend>
							<div class="form-group">
								<div class="text-center">
									<img name="icon_visu" src="<?= $plugin->getPathImgIcon(); ?>" style="max-width:160px;"/>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
				<hr>
			</div><!-- /.tabpanel #eqlogictab-->

			<!-- Onglet des commandes de l'équipement -->
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<a class="btn btn-default btn-sm pull-right cmdAction" data-action="add" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter une commande}}</a>
				<br/><br/>
				<div class="table-responsive">
					<table id="table_cmd" class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>{{Id}}</th>
								<th>{{Nom}}</th>
								<th>{{Type}}</th>
								<th>{{Options}}</th>
								<!-- <th>{{Paramètres}}</th> -->
								<th>{{Action}}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div><!-- /.tabpanel #commandtab-->

		</div><!-- /.tab-content -->
	</div><!-- /.eqLogic -->
</div><!-- /.row row-overflow -->

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, nom_du_plugin) -->
<?php include_file('desktop', 'CovidAttest', 'js', 'CovidAttest');?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js');?>

