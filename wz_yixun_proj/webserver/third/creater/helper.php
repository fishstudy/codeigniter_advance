<?php
function select_fields($param, $eh_basedb){
    return fields($param, $eh_basedb, array());
}
function insert_fields($param, $eh_basedb){
    return fields($param, $eh_basedb, array('is_deleted', 'createTime'));
}
function update_fields($param, $eh_basedb){
    return fields($param, $eh_basedb, array('createTime'));
}
function  fields($param, $eh_basedb, $filter_columns=array()){
    $dsn = sprintf('%s;dbname=%s', $eh_basedb['hostname'], $param['database']);
    $user = $eh_basedb['username'];
    $password = $eh_basedb['password'];
    $dbh = new PDO($dsn, $user, $password);
    $sql = sprintf('desc %s', $param['table']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $fields = array();
    foreach ($results as  $result){
        if (in_array($result['Field'], $filter_columns)) continue;
        $fields[] = $result['Field'];
    }
    $field_str = sprintf("'%s'", implode('\', \'', $fields));
    return $field_str;
}
function  fields_types($param, $eh_basedb, $filter_columns=array()){
    $dsn = sprintf('%s;dbname=%s', $eh_basedb['hostname'], $param['database']);
    $user = $eh_basedb['username'];
    $password = $eh_basedb['password'];
    $dbh = new PDO($dsn, $user, $password);
    $sql = sprintf('desc %s', $param['table']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $fields = array();
    foreach ($results as  $result){
        if (in_array($result['Field'], $filter_columns)) continue;
        $fields[$result['Field']] =  $result;
        $fields[$result['Field']]['format'] = field_format($result['Type']);
    }
   // $field_str = sprintf("'%s'", implode('\', \'', $fields));
    return $fields;
}
function field_format($type){
    $formats = array(
            'tinyint'=>'%d', 
            'smallint'=>'%d',  
            'mediumint'=>'%d',  
            'int'=>'%d',  
            'bigint'=>'%d', 
            'float'=>'%4.2f', 
            'double'=>'%4.2f', 
            'decimal'=>'%4.2f', 
            'char'=>'%s',  
            'varchar'=>'%s',  
            'tinytext'=>'%s',  
            'text'=>'%s',   
            'mediumtext'=>'%s',     
            'longtext'=>'%s',   
            'date'=>'%s',        
            'time'=>'%s',        
            'datetime'=>'%s',   
            'timestamp'=>'%s',          
            'enum' =>'%s',
            );
    preg_match_all('/[a-zA-z]+/', $type, $matches);
    return $formats[$matches[0][0]];
}
function get_primary_fields($param, $eh_basedb){
    $dsn      = sprintf('%s;dbname=%s', $eh_basedb['hostname'], $param['database']);
    $user     = $eh_basedb['username'];
    $password = $eh_basedb['password'];
    $dbh      = new PDO($dsn, $user, $password);
    $sql      = sprintf("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
                     WHERE table_name='%s' AND TABLE_SCHEMA='%s' AND CONSTRAINT_NAME = 'PRIMARY'", $param['table'], $param['database']);
    $stmt     = $dbh->prepare($sql);
    $stmt->execute();
    $results  = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $fields   = array();
    foreach ($results as  $result){
        //if ($result['Field'] == 'id') continue;
        $fields[] = $result['COLUMN_NAME'];
    }
    if (empty($fields)) return '';
    $field_str = sprintf("'%s'", implode('\', \'', $fields));
    return $field_str;
}
function get_unique_fields($param, $eh_basedb){
    $dsn      = sprintf('%s;dbname=%s', $eh_basedb['hostname'], $param['database']);
    $user     = $eh_basedb['username'];
    $password = $eh_basedb['password'];
    $dbh      = new PDO($dsn, $user, $password);
    $sql      = sprintf("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
            WHERE table_name='%s' AND TABLE_SCHEMA='%s'", $param['table'], $param['database']);
    $stmt     = $dbh->prepare($sql);
    $stmt->execute();
    $results  = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $fields   = array();
    foreach ($results as  $result){
        //if ($result['Field'] == 'id') continue;
        $fields[] = $result['COLUMN_NAME'];
    }
    if (empty($fields)) return '';
    $field_str = sprintf("'%s'", implode('\', \'', $fields));
    return $field_str;
}
function list_tables($eh_basedb){
    $dsn = sprintf('%s;dbname=%s', $eh_basedb['hostname'], $param['database']);
    $user = $eh_basedb['username'];
    $password = $eh_basedb['password'];

    try {
        $dbh = new PDO($dsn, $user, $password);
        $stmt = $dbh->prepare('set names utf8');
        $stmt->execute();
        $sql = sprintf('show tables');
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $fields = array();
        foreach ($results as  $result){
            //if (in_array($result['Field'], $filter_columns)) continue;
            $fields[] = $result['Tables_in_'.$eh_basedb['database']];
        }
        return $fields ;
        //$field_str = sprintf("'%s'", implode('\', \'', $fields));
        //$str = file_get_contents('model_new.tpl');
        //$search = array('[[name]]', '[[field]]');
        //$replace = array($model, $field_str);
        //$new_str = str_replace($search, $replace, $str);
        //file_put_contents('model_'.strtolower($model).'.php', $new_str);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}
function get_index_fields($param, $eh_basedb){
    $dsn      = sprintf('%s;dbname=%s', $eh_basedb['hostname'], $param['database']);
    $user     = $eh_basedb['username'];
    $password = $eh_basedb['password'];
    $dbh      = new PDO($dsn, $user, $password);
    $sql      = sprintf('show index from %s', $param['table']);
    $stmt     = $dbh->prepare($sql);
    $stmt->execute();
    $results  = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    $fields   = array();
    foreach ($results as  $result){
        if ($result['Key_name']== 'PRIMARY') continue;
        //if ($result['Field'] == 'id') continue;
        $fields[$result['Key_name']][$result['Seq_in_index']] = $result['Column_name'];
    }
    //if (empty($fields)) return '';
    //$field_str = sprintf("'%s'", implode('\', \'', $fields));
    return $fields;
}
