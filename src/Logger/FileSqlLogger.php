<?php

namespace App\Logger;

use Doctrine\DBAL\Logging\SQLLogger;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileSqlLogger implements SQLLogger
{
    
    /**
     * @var Filesystem
     */
    protected $filesystem;
    private $filename;
    
    public function __construct(ParameterBagInterface $params,Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->filename = $params->get('kernel.project_dir').'/sql/log_'.$params->get('kernel.environment').'.sql';
    }
    
    /**
     * Logs a SQL statement somewhere.
     *
     * @param string              $sql    The SQL to be executed.
     * @param mixed[]|null        $params The SQL parameters.
     * @param int[]|string[]|null $types  The SQL parameter types.
     *
     * @return void
     */
    public function startQuery($sql, ?array $params = null, ?array $types = null)
    {
        if ($this->supports($sql)) {
            //dd($sql,$params,$types);
            $full_sql = $this->getFullSQL($sql,$params,$types);
            $this->filesystem->appendToFile($this->filename,$full_sql.";\r\n");
        }
    }
    
    /**
     * Marks the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {
        return;
    }
    
    private function getFullSQL($sql,$params,$types):string
    {
        if (empty($params)) {
            return $sql;
        }
        $fullSql='';
        for($i=0;$i<strlen($sql);$i++){
            if($sql[$i]=='?'){
                $param=array_shift($params);
                $type=array_shift($types);
                if ($param === null) {
                    $fullSql.= 'NULL';
                    continue;
                }
                switch ($type){
                    case 'text':
                    case 'string':
                        $fullSql.= '"'.addslashes($param).'"';
                        break;
                    case 'integer':
                    case 'float':
                        $fullSql.= $param;
                        break;
                    case 'datetime':
                        $fullSql.= "'".$param->format('Y-m-d H:i:s')."'";
                        break;
                    default:
                        $fullSql.= $param;
                }
                
            }  else {
                $fullSql.=$sql[$i];
            }
        }
        return $fullSql;
    }
    
    private function supports($sql)
    {
        return strpos($sql, 'UPDATE') === 0 || strpos($sql, 'INSERT') === 0 || strpos($sql, 'REPLACE') === 0 || strpos($sql, 'DELETE') === 0;
    }
}