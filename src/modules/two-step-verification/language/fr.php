<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '20/07/2023, 07:15';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['confirm_password'] = 'Entrez le mot de passe pour continuer';
$lang_module['confirm_password_info'] = 'Pour implémenter cette fonctionnalité, vous devez confirmer votre mot de passe, entrez votre mot de passe dans la case ci-dessous et cliquez sur Confirmer';
$lang_module['confirm'] = 'Confirmer';
$lang_module['secretkey'] = 'Secret code';
$lang_module['wrong_confirm'] = 'Code est incorrecte, s\'il vous plaît ré-entrer';
$lang_module['cfg_step1'] = 'Étape 1: Scannez le code QR';
$lang_module['cfg_step1_manual'] = 'Scannez le QR-code avec une application qui prend en charge l\'authentification en deux étapes sur votre téléphone (par exemple, Google Authenticator). Si le QR-code ne peut pas être scanné,';
$lang_module['cfg_step1_manual1'] = 'cliquez ici';
$lang_module['cfg_step1_manual2'] = 'pour saisir manuellement la clé de configuration';
$lang_module['cfg_step1_note'] = 'Remarque: Vous devez garder cette clé secrète';
$lang_module['cfg_step2_info'] = 'Si l\'opération ci-dessus réussit, l\'application affichera une chaîne à 6 chiffres. Veuillez saisir cette chaîne dans la case ci-dessous pour confirmer.';
$lang_module['cfg_step2_info2'] = 'Code à 6 chiffres';
$lang_module['cfg_step2'] = 'Étape 2: Entrez le code de l\'application';
$lang_module['title_2step'] = 'L\'authentification à deux étapes';
$lang_module['status_2step'] = 'L\'authentification à deux étapes est';
$lang_module['active_2step'] = 'Allumé';
$lang_module['deactive_2step'] = 'Éteint';
$lang_module['backupcode_2step'] = 'Vous avez <strong>%d</strong> dans les codes de sauvegarde inutilisés';
$lang_module['backupcode_2step_view'] = 'Voir les codes de sauvegarde';
$lang_module['backupcode_2step_note'] = 'Remarque : veuillez stocker les codes de sauvegarde avec soin ! Si vous perdez votre téléphone, vous pouvez les utiliser pour vérifier l\'accès au compte. Si vous oubliez vos codes et perdez votre téléphone, vous ne pourrez pas vous connecter à votre compte.';
$lang_module['turnoff2step'] = 'Désactivez l\'authentification en deux étapes';
$lang_module['turnon2step'] = 'Activer l\'authentification en deux étapes';
$lang_module['creat_other_code'] = 'Régénérer les codes de sauvegarde';
$lang_module['change_2step_notvalid'] = 'Votre compte n\'a pas de mot de passe, donc l\'authentification en deux étapes ne peut pas être modifiée. Veuillez créer un mot de passe, puis revenir à cette page.<br />Veuillez <a href="%s">cliquer ici</a> pour créer un mot de passe';
$lang_module['deactive_mess'] = 'Voulez-vous vraiment désactiver l\'authentification en deux étapes?';
$lang_module['setup_2step'] = 'Configurer l\'authentification en deux étapes';
$lang_module['forcedrelogin'] = 'Reconnexion forcée partout';
$lang_module['forcedrelogin_note'] = 'Vous êtes déconnecté de votre compte. Veuillez vous reconnecter';
$lang_module['setup_key'] = 'Clé de configuration';
