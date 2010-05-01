<?php

class GettableBehavior extends ModelBehavior {

	function setup(&$Model, $settings = array()) {
	}
	
	function get(&$Model, $identifier=null, $fields=true) {
		if (is_null($identifier)) {
			$identifier = $Model->id;
		}

		if ($fields === true) {
			$fields = array_keys($Model->schema());
		}

		if (is_array($identifier)) {
			if (!isset($identifier[$Model->alias])) {
				$identifier = array($Model->alias => $identifier);
			}

			$fieldsPresent = array_keys($identifier[$Model->alias]);

			$diff = array_diff($fields, $fieldsPresent);
			if (empty($diff)) {
				//	We have all fields we want, return data
				return $identifier;
			}

			$identifier = $identifier[$Model->alias][$Model->primaryKey];
		}

		$data = null;
		if (is_numeric($identifier) || is_string($identifier)) {
			$data = $Model->find(
				'first',
				array(
					'fields' => $fields,
					'conditions' => array($Model->alias . '.' . $Model->primaryKey => $identifier),
					'recursive' => -1,
				)
			);
		}

		return $data;
	}
}
?>
