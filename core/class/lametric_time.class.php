<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class lametric_time extends eqLogic {
  /*     * *************************Attributs****************************** */

  /*
  * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
  * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
  public static $_widgetPossibility = array();
  */

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration du plugin
  * Exemple : "param1" & "param2" seront cryptés mais pas "param3"
  public static $_encryptConfigKey = array('param1', 'param2');
  */

  /*     * ***********************Methode static*************************** */

  /*
  * Fonction exécutée automatiquement toutes les minutes par Jeedom
  public static function cron() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
  public static function cron5() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
  public static function cron10() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
  public static function cron15() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
  public static function cron30() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les heures par Jeedom
  public static function cronHourly() {}
  */

  /*
  * Fonction exécutée automatiquement tous les jours par Jeedom
  public static function cronDaily() {}
  */

  /*     * *********************Méthodes d'instance************************* */

  // Fonction exécutée automatiquement avant la création de l'équipement
  public function preInsert() {
  }

  // Fonction exécutée automatiquement après la création de l'équipement
  public function postInsert() {
    // ACTION - ENVOYER
    $action_envoyer = $this->getCmd(null, "envoyer");
    if (!is_object($action_envoyer)) {
      $action_envoyer = new lametric_timeCmd();
      $action_envoyer->setName(__("Envoyer", __FILE__));
    }
    $action_envoyer->setEqLogic_id($this->getId());
    $action_envoyer->setLogicalId("envoyer");
    $action_envoyer->setType("action");
    $action_envoyer->setSubType("other");
    $action_envoyer->setIsVisible(1);
    $action_envoyer->save();
  }

  // Fonction exécutée automatiquement avant la mise à jour de l'équipement
  public function preUpdate() {
  }

  // Fonction exécutée automatiquement après la mise à jour de l'équipement
  public function postUpdate() {
  }

  // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
  public function preSave() {
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function postSave() {
  }

  // Fonction exécutée automatiquement avant la suppression de l'équipement
  public function preRemove() {
  }

  // Fonction exécutée automatiquement après la suppression de l'équipement
  public function postRemove() {
  }

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration des équipements
  * Exemple avec le champ "Mot de passe" (password)
  public function decrypt() {
    $this->setConfiguration('password', utils::decrypt($this->getConfiguration('password')));
  }
  public function encrypt() {
    $this->setConfiguration('password', utils::encrypt($this->getConfiguration('password')));
  }
  */

  private function GetSonList(){
    return array(
      array("", ""),
      array("cash", "Argent"),
      array("cat", "Chat"),
      array("dog", "Chien 1"),
      array("dog2", "Chien 2"),
      array("lose1", "Defaite 1"),
      array("lose2", "Defaite 2"),
      array("water1", "Eau 1"),
      array("water2", "Eau 2"),
      array("letter_email", "Email"),
      array("energy", "Energie"),
      array("negative1", "Negatif 1"),
      array("negative2", "Negatif 2"),
      array("negative3", "Negatif 3"),
      array("negative4", "Negatif 4"),
      array("negative5", "Negatif 5"),
      array("notification", "Notification 1"),
      array("notification2", "Notification 2"),
      array("notification3", "Notification 3"),
      array("notification4", "Notification 4"),
      array("open_door", "Ouverture porte"),
      array("positive1", "Positif 1"),
      array("positive2", "Positif 2"),
      array("positive3", "Positif 3"),
      array("positive4", "Positif 4"),
      array("positive5", "Positif 5"),
      array("positive6", "Positif 6"),
      array("statistic", "Statistique"),
      array("knock-knock", "Toc toc"),
      array("thunder", "Tonnerre"),
      array("bicycle", "Vélo"),
      array("wind", "Vent"),
      array("wind_short", "Vent court"),
      array("win", "Victoire 1"),
      array("win2", "Victoire 2"),
      array("car", "Voiture")
    );
  }

  // Permet de modifier l'affichage du widget (également utilisable par les commandes)
  public function toHtml($_version = 'dashboard') {
    $this->emptyCacheWidget(); //vide le cache
    $replace = $this->preToHtml($_version); // remplacement des tag jeedom (ex : #id#, #uid# ...)
    if (!is_array($replace))  {
      return $replace;
    }
    $version = jeedom::versionAlias($_version); // version dashbord ou mobile

    $options = "";
    foreach($this->GetSonList() as $option){
      $options .= "<option value='".$option[0]."'>".$option[1]."</option>";
    }
    $replace['#options#'] = $options;

    $cmd = lametric_timeCmd::byEqLogicIdAndLogicalId($this->getId(),'envoyer'); // recherche id de la commande action/message
    if (is_object($cmd)){
      $replace['#envoyer_id#'] = $cmd->getId();
    }

    $html = $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'LaMetricTimeTemplate', __CLASS__))); // remplacement des tag
    return translate::exec($html, '/plugins/' . __CLASS__ . '/core/template/' . $version . '/LaMetricTimeTemplate.html'); // translate
  }

  /*
  * Permet de déclencher une action avant modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function preConfig_param3( $value ) {
    // do some checks or modify on $value
    return $value;
  }
  */

  /*
  * Permet de déclencher une action après modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function postConfig_param3($value) {
    // no return value
  }
  */

  /*     * **********************Getteur Setteur*************************** */

}

class lametric_timeCmd extends cmd {
  /*     * *************************Attributs****************************** */

  /*
  public static $_widgetPossibility = array();
  */

  /*     * ***********************Methode static*************************** */


  /*     * *********************Methode d'instance************************* */

  /*
  * Permet d'empêcher la suppression des commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
  public function dontRemoveCmd() {
    return true;
  }
  */

  // Exécution d'une commande
  public function execute($_options = array()) {
    $eqlogic = $this->getEqLogic();
    switch ($this->getLogicalId()) {
      case "envoyer":
  	    $message = $_options["message"];

        $icon = $_options["icone"];

        $sound = "";
        if($_options["son"] != ""){
          $sound = ',
            "sound": {
                "category":"notifications",
                "id":"' . $_options["son"] . '",
                "repeat":1
            }';
        }

        $ip  = $eqlogic->getConfiguration('ip', '');
        if($ip == ""){
	         message::add('LaMetric Time', "Vous n'avez pas fourni l'IP de votre LaMetric Time.");
           return;
        }
        $apikey  = $eqlogic->getConfiguration('api_token', '');
        if($apikey == ""){
	         message::add('LaMetric Time', "Vous n'avez pas fourni la clé API de votre LaMetric Time.");
           return;
        }

        // settings
        $header  = array("Content-Type: application/json");
        $url     = "http://" . $ip . ":8080/api/v2/device/notifications";
        $post    = '{
          "priority": "critical",
          "icon_type":"none",
          "model": {
            "frames": [
              {
                "icon":' . $icon . ',
                "text":"' . $message . '"
              }
            ]' . $sound . '
          }
        }';

        // curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERPWD, "dev:" . $apikey);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // execute
        $response = curl_exec($ch);

        // close
        curl_close($ch);
        break;
    }
  }
	
  public function getWidgetTemplateCode($_version = 'dashboard', $_clean = true, $_widgetName = '') {
	  $data = null;
	  if ($_version != 'scenario') return parent::getWidgetTemplateCode($_version, $_clean, $_widgetName);
	  $data = getTemplate('core', 'scenario', 'cmd.sendMsg', 'lametric_time');
	  if (!is_null($data)) {
		  if (version_compare(jeedom::version(),'4.2.0','>=')) {
			  if(!is_array($data)) return array('template' => $data, 'isCoreWidget' => false);
		  } else return $data;
	  }
	  return parent::getWidgetTemplateCode($_version, $_clean, $_widgetName);
  }

  /*     * **********************Getteur Setteur*************************** */

}
