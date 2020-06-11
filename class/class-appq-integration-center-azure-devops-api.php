<?php

class AzureDevOpsRestApi extends IntegrationCenterRestApi
{


	public function __construct($cp_id)
	{
		$this->api_version = '5.1';

		parent::__construct($cp_id, 'azure-devops', 'Azure Devops');
		$this->basic_configuration = array(
			'/fields/System.Title' => '{Bug.message}',
			'/fields/System.Description' => '{Bug.message}',
			'/fields/System.WorkItemType' => $this->get_issue_type()
		);
	}

	
	/**
	 * Get the data for authorization
	 * @method get_authorization
	 * @date   2019-10-30T14:53:28+010
	 * @author: Davide Bizzi <clochard>
	 * @return string	The authorization header content (Basic with base64 encoded USER:KEY - USER empty , KEY apikey).
	 */
	public function get_authorization()
	{
		return "Basic ".base64_encode(':' .$this->get_token());
	}

	/**
	 * Return the issue type
	 * @method get_issue_type
	 * @date   2019-10-30T15:05:06+010
	 * @author: Davide Bizzi <clochard>
	 * @return string			The issue type
	 */
	public function get_issue_type()
	{
		return 'Microsoft.VSTS.WorkItemTypes.Issue';
	}

	
	/**
	 * Get mapped field data
	 * @method map_fields
	 * @date   2019-10-30T15:20:13+010
	 * @author: Davide Bizzi <clochard>
	 * @param  MvcObject                  $bug The bug to map (MvcObject with additional fields on field property)
	 * @return array                       An array of array with Azure Devops operations
	 */
	public function map_fields($bug)
	{
		$field_mapped = parent::map_fields($bug);
		if (array_key_exists('/fields/System.WorkItemType',$field_mapped)) {
			$this->basic_configuration['/fields/System.WorkItemType'] = $field_mapped['/fields/System.WorkItemType'];
		}
		$data = array();
		foreach ($field_mapped as $key => $value) {
			$data[] = array(
				"path" => $key,
				"op" => "add",
				"from" => null,
				"value" => $value
			);
		}

		return $data;
	}

	/** 
	 * Send the issue
	 * @method send_issue
	 * @date   2019-10-30T15:21:44+010
	 * @author: Davide Bizzi <clochard>
	 * @param  MvcObject                  $bug The bug to upload (MvcObject with additional fields on field property)
	 * @return array 					An associative array {
	 * 										status: bool,		If uploaded successfully
	 * 										message: string		The response of the upload or an error message on error 
	 * 									}
	 */
	public function send_issue($bug)
	{
		global $wpdb;
		$data = $this->map_fields($bug);
		$issue_type = $this->basic_configuration['/fields/System.WorkItemType'];
		$url = $this->get_apiurl() . '/wit/workitems/$' . $issue_type . '?api-version=' . $this->api_version;

		$req = $this->http_post($url, array(
			'Authorization' => $this->get_authorization(),
			'Content-Type' => 'application/json-patch+json'
		), json_encode($data));

		$res = json_decode($req->body);

		if (is_null($res)) {
			return array(
				'status' => false,
				'message' => 'Error on upload bug'
			);
		}

		if (property_exists($res, 'innerException') && property_exists($res, 'message')) {
			return array(
				'status' => false,
				'message' => $res->message
			);
		}

		if (property_exists($res, 'fields'))
		{
			$wpdb->insert($wpdb->prefix . 'appq_integration_center_bugs', array(
				'bug_id' => $bug->id,
				'bugtracker_id' => $res->id,
				'integration' => $this->integration['slug']
			));
			if (property_exists($this->configuration, 'upload_media') && intval($this->configuration->upload_media) > 0)
			{
				$return = array(
					'status' => true,
					'message' => ''
				);
				$media =  $wpdb->get_col($wpdb->prepare('SELECT location FROM ' . $wpdb->prefix . 'appq_evd_bug_media WHERE bug_id = %d', $bug->id));
				foreach ($media as $media_item)
				{
					$res = $this->add_attachment($res->id, $media_item);
					if (!$res['status'])
					{
						$return['status'] = false;
						$return['message'] = $return['message'] . ' <br> '. $res['message'];
					}
				}
				
				if (!$return['status'])
				{
					return $return;
				}
			}

			return array(
				'status' => true,
				'message' => $res
			);
		}

		return array(
			'status' => false,
			'message' => 'Generic error'
		);
	}


	/**
	 * Delete an issue by id on azure
	 * @method delete_issue
	 * @date                2020-06-08T10:35:32+020
	 */
	public function delete_issue($bugtracker_id) {
		global $wpdb;
		if (empty($bugtracker_id)) {
			return array(
				'status' => false,
				'data' => array(
					'auth_error' => false,
					'message' => 'Empty bugtracker_id'
				)
			);
		}
		
		$url = $this->get_apiurl() . '/wit/workitems/' . $bugtracker_id . '?api-version=' . $this->api_version;
		
		
		$req = $this->http_delete($url, array(
			'Authorization' => $this->get_authorization(),
			'Content-Type' => 'application/json-patch+json'
		));
		
		if (empty($req) || !property_exists($req,'status_code')) {
			return array(
				'status' => false,
				'data' => array(
					'auth_error' => false,
					'message' => 'Empty Response from Azure'
				)
			);
		} else {
			$delete_from_db = false;
			$authorized = false;
			if ($req->status_code == 204 || $req->status_code == 200) {
				$delete_from_db = true;
				$authorized = true;
			} elseif ($req->status_code == 403) {
				$delete_from_db = true;
			}
		}

		if ($authorized) {
			$data = array(
				'auth_error' => false,
				'message' => 'Successfully deleted the issue'
			);
		} else {
			$data = array(
				'auth_error' => true,
				'message' => 'You are not authorized to delete issues on this project'
			);
		}
		
		if ($delete_from_db) {
			$wpdb->delete($wpdb->prefix . 'appq_integration_center_bugs',array(
				'bugtracker_id' => $bugtracker_id
			));
			return array(
				'status' => true,
				'data' => $data
			);
		}
		
		
		return array(
			'status' => false,
			'data' => array(
				'auth_error' => false,
				'message' => 'There was an error deleting the issue. The status code was ' .$req->status_code
			)
		);
		
	}

	

	/**
	 * Add bug media to an issue on jira
	 * @method add_attachment
	 * @date   2019-10-30T15:34:39+010
	 * @author: Davide Bizzi <clochard>
	 * @param  string                  $key   The issue key
	 * @param                    $media The url of the media to attach
	 * @return array 					An associative array {
	 * 										status: bool,		If uploaded successfully
	 * 										message: string		The response of the upload or an error message on error 
	 * 									}
	 */
	public function add_attachment( $key, $media)
	{
		$basename = basename($media);
		$filename =  ABSPATH . 'wp-content/plugins/appq-integration-center/tmp/' . $basename;
		file_put_contents(ABSPATH . 'wp-content/plugins/appq-integration-center/tmp/' . $basename, fopen($media, 'r'));

		$headers = array(
			"Content-Type" => "application/octet-stream",
			"Authorization" =>  $this->get_authorization()
		);

		$url = $this->get_apiurl(). '/wit/attachments?filename='.$basename.'&api-version=' .$this->api_version;

		$req = $this->http_post($url, $headers, file_get_contents($filename));
		unlink($filename);

		$req =  json_decode($req->body);

		if (is_null($req))
		{
			return array(
				'status' => false,
				'message' => 'Error on upload media'
			);
		}

		$url = $this->get_apiurl() . '/wit/workitems/' . $key . '?api-version=' . $this->api_version;
		$headers['Content-Type'] = 'application/json-patch+json';

		$req = $this->http_patch($url, $headers, json_encode(array(
			array(
				"op" => "add",
				"path" => "/relations/-",
				"value" => array(
					"rel" => "AttachedFile",
					"url" => $req->url,
				)
			)
		)));

		$req =  json_decode($req->body);

		if (property_exists($req, 'innerException'))
		{
			return array(
				'status' => false,
				'message' => 'Error on linking uploaded media to workitem - ' .$req->message
			);
		}
		if (property_exists($req, 'fields')) {
			return array(
				'status' => true,
				'message' => json_encode($req)
			);
		}

		return array(
			'status' => false,
			'message' => 'Generic media error'
		);
	}

	public function get_issue_by_id($id)
	{
		$id = intval($id);
		$url = $this->get_apiurl() . '/wit/workitems/'. $id .'?api-version=' . $this->api_version;

		$req = $this->http_get($url, array(
			'Authorization' => $this->get_authorization()
		));

		return json_decode($req->body);
	}
}
