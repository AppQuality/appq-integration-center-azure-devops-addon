<?php 
/*
 * @Author: Davide Bizzi <clochard>
 * @Date:   26/05/2020
 * @Filename: get_url_model.php
 * @Last modified by:   clochard
 * @Last modified time: 2020-06-04T10:36:16+02:00
 */


function appq_ic_azure_devops_get_url_model($bugtracker) {
		$endpoint = preg_replace('/_apis?$/', '_workitems/edit', $bugtracker->endpoint);

    
    return $endpoint . '/{bugtracker_id}';
}
