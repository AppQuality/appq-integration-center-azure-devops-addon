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
			$wpdb->insert($wpdb->prefix . 'appq_integration_center_bugs', array(
				'bug_id' => $bug->id,
				'integration' => $this->integration['slug']
			));

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

	public function get_issue_by_id($id)
	{
		$id = intval($id);
		$req = Requests::get($this->get_apiurl() . '/wit/workitems/'. $id .'?api-version=' . $this->api_version, array(
			'Authorization' => $this->get_authorization()
		));

		return json_decode($req->body);
	}



}
