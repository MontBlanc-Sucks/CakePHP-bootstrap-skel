<?php
App::uses('ClassRegistry', 'Utility');
App::uses('AppModel', 'Model');
class Post extends AppModel {}
class Comment extends AppModel {}

class SaveInitialData extends Object {
	public $ext = '.json';
	public $dataDir = 'data';
	public $path;
	public $json_erros;
	public $dataFiles;

	public function __construct($path, $isLast = false) {
		$this->path = $path;
		if (!$this->dataFiles) {
			$folder = new Folder($path . DS . $this->dataDir . DS);
			$this->dataFiles = $folder->find('.*\\' . $this->ext);
			$ext = $this->ext;
			$this->models = array_map(function($name) use($ext) {
				return basename($name, $ext);
			}, $this->dataFiles);
		}
		if ($isLast) {
			$this->save();
		}
	}

	public function save() {
		if (empty($this->models)) {
			return false;
		}
		foreach ($this->models as $tableName) {
			$fileName = $tableName . $this->ext;
			echo "----------------------\n";
			echo $tableName . "\n";
			$file = $this->path . DS . $this->dataDir . DS . $fileName;
			if ($jsonString = file_get_contents($file)) {
				$modelName = Inflector::classify($tableName);
				echo "----------------------\n";
				$json = json_decode($jsonString, true);
				if ($this->isJsonError()) {
					return false;
				}
				$data = $this->combine($modelName, $json);
				$this->{$modelName} = ClassRegistry::init($modelName);
				$result = !!$this->{$modelName}->saveAll($data);
				echo $modelName . ' : ' . ($result ? 'true' : 'false') . ' [' . count($data) . "]\n";
			}
		} 
	}

	private function check() {
		$db = ConnectionManager::getDataSource($this->{$this->models[0]}->useDbConfig);
		$sources = $db->listSources();
		return count($sources) === $this->dataFileCount;
	}

	private function combine($modelName, $json) {
		$data = array_map(function ($row) use ($modelName) {
			// datetime
			if (strstr('T', $row['created']) === FALSE) {
				$row['created'] = date('Y-m-d H:i:s', strtotime($row['created']));
			}
			if (strstr('T', $row['modified']) === FALSE) {
				$row['modified'] = date('Y-m-d H:i:s', strtotime($row['modified']));
			}
			return array($modelName => $row);
		}, $json);
		return $data;
	}

	private function isJsonError() {
		if (empty($this->json_errors)) {
			$constants = get_defined_constants(true);
			$this->json_errors = array();
			foreach ($constants["json"] as $name => $value) {
				if (!strncmp($name, "JSON_ERROR_", 11)) {
					$this->json_errors[$value] = $name;
				}
			}
		}
		$no = json_last_error();
		$err = $this->json_errors[$no];
		if ($no == JSON_ERROR_NONE) {
			return false;
		}
		echo $err . "\n";
		return true;
	}
}

class AppSchema extends CakeSchema {

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
		if(!empty($event['create'])){
			$saveInitialData = new SaveInitialData($this->path, $event['create'] == 'comments');
		}
	}

	var $posts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		'body' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $comments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'collate' => NULL, 'comment' => ''),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		'mail' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		'body' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

}
