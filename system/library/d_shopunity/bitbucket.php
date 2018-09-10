<?php 
/**
 * Bitbucket POST Deployment Class
 * @author  James Collings <james@jclabs.co.uk>
 * @version 0.0.3
 */
namespace d_shopunity;

class Bitbucket{
  
  private $user = ''; // Bitbucket username
  private $pass = ''; // Bitbucket password 
  private $owner = ''; // Bitbucket owener (usualy username but could be company) 
  private $repo = '';  // repository name
  private $branch = 'master';
  private $deploy = './'; // directory deploy repository
  private $download_name = 'download.zip'; // name of downloaded zip file
  private $debug = false;  // false = hide output
  private $process = 'deploy'; // deploy or update
  private $access_token = ''; // deploy or update
 
  // files to ignore in directory
  private $ignore_files = array('README.md', '.gitignore', '.', '..');
 
  // default array of files to be committed
  private $files = array('modified' => array(), 'added' => array(), 'removed' => array());
 
  function __construct($user = '', $pass = '', $repo = '', $deploy = '', $branch = '', $owner = ''){
    $result = array();

    if($user){
        $this->user = $user;
    }

    if($pass){
        $this->pass = $pass;
    }

    if($repo){
        $this->repo = $repo;
    }
    
    if($deploy){
        $this->deploy = $deploy;
    }

    if($branch){
        $this->branch = $branch;
    }
    $this->owner = $this->user;
    if($owner){
        $this->owner = $owner;
    }

    $json = file_get_contents('php://input');
    if(empty($json)){
      $json = isset($_POST['payload']) ? $_POST['payload'] : false;
    }

    if($json){
      $this->log($json);

      $data = json_decode($json); // decode json into php object
      $this->repo = $data->repository->name;
      $this->owner = $data->repository->owner->username;
      $this->user = $data->actor->username;
      
      

      if(isset($data->access_token)){
        $this->log($data->access_token);
        $this->access_token = $data->access_token;
      }

      $result['repo'] = $this->repo;
      $result['branch'] = $this->branch;
      $result['owner'] = $this->owner;
      $result['user'] = $this->user;
      $result['access_token'] = $this->access_token;
      
      // process all commits
      
        // if no commits have been posted, deploy latest node
        $this->process = 'deploy';
 
        // download repo
        if(!$this->get_repo($this->branch)){
          $this->log('Download of Repo Failed');
          $result['error'] = 'Download of Repo Failed';
          //return;
        }

        if(empty($result['error'])){
          $result['step_1'] = 'get_repo complete';
   
          // unzip repo download
          if(!$this->unzip_repo()){
            $this->log('Unzip Failed');
            $result['error'] = 'Unzip Failed';
            //return;
          }
        }
        
        if(empty($result['error'])){
          $result['step_2'] = 'unzip_repo complete';
   
          $node = $this->get_node_from_dir();
          $message = 'Bitbucket zip retrieved, complete deploy';
          if(!$node){
            $this->log('Node could not be set, no unziped repo');
            $result['error'] = 'Node could not be set, no unziped repo';
            //return; 
          }
        }

        if(empty($result['error'])){
          $result['step_3'] = 'get_node_from_dir complete';
   
          // append changes to destination
          $this->parse_changes($node, $message);
   
          // delete zip file
          unlink($this->download_name);
        }
    }else{
      // no $_POST['payload']
      $this->log('No Payload' . file_get_contents('php://input'));
      $result['error'] = 'No Payload';
    }

    echo json_encode($result);
  }
 
  /**
   * Extract the downloaded repo
   * @return boolean
   */
  function unzip_repo(){
    // init zip archive helper
    $zip = new \ZipArchive;
 
    $res = $zip->open($this->download_name);
    if ($res === TRUE) {
      // extract files to base directory
        $zip->extractTo('./');
        $zip->close();
        return true;
    }
    return false;
  }
 
  /**
   * Download the repository from bitbucket
   * @param  string $node 
   * @return boolean
   */
  function get_repo($node = ''){
 
    // create the zip folder
    $fp = fopen($this->download_name, 'w');
 
    // set download url of repository for the relating node
 
    if($this->access_token){
      $ch = curl_init("https://bitbucket.org/$this->owner/$this->repo/get/$node.zip?access_token=".$this->access_token);
    }else{
      $ch = curl_init("https://bitbucket.org/$this->owner/$this->repo/get/$node.zip");
      // http authentication to access the download
      curl_setopt($ch, CURLOPT_USERPWD, "$this->user:$this->pass");
    }
    
    $this->log('downloading: '."https://bitbucket.org/$this->owner/$this->repo/get/$node.zip?access_token=".$this->access_token);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); //was true
 
    // disable ssl verification if your server doesn't have it
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
    // save the transfered zip folder
    curl_setopt($ch, CURLOPT_FILE, $fp);
 
    // run the curl command
    $result = curl_exec($ch); //returns true / false
 
    // close curl and file functions
    curl_close($ch);
    fclose($fp);
    return $result;
  }
 
  /**
   * Apply the repository changes add, edit, delete
   * @param  string $node    
   * @param  string $message 
   * @return void
   */
  function parse_changes($node = '', $message = ''){
    $src = "./$this->owner-$this->repo-$node/";
 
    if(!is_dir($this->deploy))
      $this->process = 'deploy';
 
    $this->log('Process: '.$this->process);
    $this->log('Commit Message: '.$message);
 
    $dest = $this->deploy;
    $real_src = realpath($src);
 
    if(!is_dir($real_src)){
      $this->log('Unable to read directory');     
      return;
    }
 
    $output = array();
 
    $objects = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator($real_src), 
      \RecursiveIteratorIterator::SELF_FIRST);
 
    foreach($objects as $name => $object){
 
      // check to see if file is in ignore list
      if(in_array($object->getBasename(), $this->ignore_files))
        continue;
 
      // remove the first '/' if there is one
      $tmp_name = str_replace($real_src, '', $name);
      if($tmp_name[0] == '/')
        $tmp_name = substr($tmp_name,1);
 
      switch($this->process){
        case 'update':
          // only update changed files
          if(in_array($tmp_name, $this->files['added'])){
            $this->add_file($src . $tmp_name, $dest . $tmp_name);
          }
          if(in_array($tmp_name, $this->files['modified'])){
            $this->modify_file($src . $tmp_name, $dest . $tmp_name);
          }
        break;
        case 'deploy':
          $this->add_file($src . $tmp_name, $dest . $tmp_name);
        break;
      }
    }
 
    // delete all files marked for deleting
    if(!empty($this->files['removed'])){
      foreach($this->files['removed'] as $f){
        $this->removed($dest . $f);
      }
    }
 
    $this->delete($src);
  }
 
  /**
   * Delete folder recursivly
   * @param  string $path 
   * @return void
   */
  private function delete($path) {
      $objects = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($path), 
        \RecursiveIteratorIterator::CHILD_FIRST);
 
      foreach ($objects as $object) {
          if (in_array($object->getBasename(), array('.', '..'))) {
              continue;
          } elseif ($object->isDir()) {
              rmdir($object->getPathname());
          } elseif ($object->isFile() || $object->isLink()) {
              unlink($object->getPathname());
          }
      }
      rmdir($path);
  }
 
  /**
   * Retrieve node from extracted folder name
   * @return string
   */
  private function get_node_from_dir(){
    $files = scandir('./');
    foreach($files as $f){
      if(is_dir($f)){
        // check to see if it starts with 
        $starts_with = "$this->owner-$this->repo-";
        if(strpos($f, $starts_with) !== false){
          return substr($f, strlen($starts_with));
        }  
      }
    }
    return false;
  }
 
  /**
   * Write log file
   * @param  string $message 
   * @return void
   */
  private function log($message = ''){
    if(!$this->debug)
      return false;
 
    $message = date('d-m-Y H:i:s') . ' : ' . $message . "\n";
    file_put_contents('./log.txt', $message, FILE_APPEND);
  }
 
  /**
   * Add new file
   * @param string $src  
   * @param string $dest 
   * @return  void
   */
  private function add_file($src, $dest){
    $this->log('add_file src: '. $src . ' => '.$dest);
    if(!is_dir(dirname($dest)))
      @mkdir(dirname($dest), 0755, true);
    @copy($src, $dest);
  }
 
  /**
   * Replace file with new copy
   * @param  string $src  
   * @param  string $dest 
   * @return void
   */
  private function modify_file($src, $dest){
    $this->log('modify_file src: '. $src . ' => '.$dest);
    @copy($src, $dest);
  }
 
  /**
   * Delete file from directory
   * @param  string $file
   * @return void
   */
  private function removed($file){
    $this->log('remove_file file: '. $file);
 
    if(is_file($file))
      @unlink($file);
  }
 
}
?>