<?php

class AzureDevOpsRestApi extends IntegrationCenterRestApi
{


	public function __construct($cp_id)
	{
		$this->api_version = '5.1';
		
		parent::__construct($cp_id, 'azure-devops', 'Azure Devops');
		$this->basic_configuration = array(
			'/fields/System.Title' => '{Bug.message}',
			'/fields/System.Description' => '{Bug.message}'
		);
	}


	public function get_authorization()
	{
		return "Basic ".base64_encode(':' .$this->get_token());
	}

	public function get_issue_type()
	{
		return 'Microsoft.VSTS.WorkItemTypes.Issue';
	}

	public function bug_data_replace($bug, $value)
	{
		$value = parent::bug_data_replace($bug, $value);
		return $value;
	}


	public function map_fields($bug)
	{
		$field_mapped = parent::map_fields($bug);
		$data = array();
		foreach ($field_mapped as $key => $value) {
			$data[] = array(
				"path" =>$key,
				"op" => "add",
				"from" => null,
				"value" =>$value
			);
		}

		return $data;
	}

	public function send_issue($bug)
	{
		global $wpdb;
		$data = $this->map_fields($bug);
		$req = Requests::post($this->get_apiurl() . '/wit/workitems/$' . $this->get_issue_type() . '?api-version=' . $this->api_version, array(
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
			// $wpdb->insert($wpdb->prefix . 'appq_integration_center_bugs', array(
			// 	'bug_id' => $bug->id,
			// 	'integration' => $this->integration['slug']
			// ));
			if (property_exists($this->configuration, 'upload_media') && intval($this->configuration->upload_media) > 0)
			{
				$media =  $wpdb->get_col($wpdb->prepare('SELECT location FROM ' . $wpdb->prefix . 'appq_evd_bug_media WHERE bug_id = %d', $bug->id));
				foreach ($media as $media_item)
				{
					$this->add_attachment($bug, $res->id, $media_item);
				}
			}

			return array(
				'status' => false,
				'message' => $res
			);
		}
		return array(
			'status' => false,
			'message' => 'Generic error'
		);
	}
	
	


	public function add_attachment($bug, $key, $media)
	{
		$basename = basename($media);
		$filename =  ABSPATH . 'wp-content/plugins/appq-integration-center/tmp/' . $basename;
		file_put_contents(ABSPATH . 'wp-content/plugins/appq-integration-center/tmp/' . $basename, fopen($media, 'r'));

		$headers = array(
			"Content-Type: application/octet-stream",
			"Authorization: " . $this->get_authorization()
		);
		
		$ch = curl_init();
		$options = array(
			CURLOPT_URL => $this->get_apiurl(). '/wit/attachments?filename='.$basename.'&api-version=' .$this->api_version,
			CURLOPT_POST => 1,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => file_get_contents($filename),
			CURLOPT_RETURNTRANSFER => true
		);
		curl_setopt_array($ch, $options);
		$req = curl_exec($ch);
		
		$ret = array(
			'status' => false,
			'message' => 'Generic error on attachment ' . $basename
		);
		if(!curl_errno($ch))
		{
			$info = curl_getinfo($ch);
			if ($info['http_code'] == 201) {
				$req =  json_decode($req);
				$req = Requests::patch($this->get_apiurl() . '/wit/workitems/' . $key . '?api-version=' . $this->api_version, array(
					'Authorization' => $this->get_authorization(),
					'Content-Type' => 'application/json-patch+json'
				), json_encode(array(
					array(
				    	"op" => "add",
					    "path" => "/relations/-",
					    "value" => array(
							"rel" => "AttachedFile",
							"url" => $req->url,
						)
					)
				)));
				
				$ret = array(
					'status' => true,
					'message' => json_decode($req->body)
				);
			} else {
				$ret['message'] = $ret['message'] . ' - Error ' .  $info['http_code'];
			}
		}
		else
		{
			$ret = array(
				'status' => false,
				'error' => $errmsg
			);
		}
		curl_close($ch);
		unlink($filename);
	}

	public function get_issue_by_id($id)
	{
		$id = intval($id);
		$req = Requests::get($this->get_apiurl() . '/wit/workitems/'. $id .'?api-version=' . $this->api_version, array(
			'Authorization' => $this->get_authorization()
		));

		return json_decode($req->body);
	}



}
