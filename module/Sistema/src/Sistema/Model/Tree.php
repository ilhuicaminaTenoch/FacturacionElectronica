<?php
namespace Sistema\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

/**
 *
 * @author jose.moreno
 *        
 */
class Tree extends TableGateway
{

    private $dbAdapter;

    public function __construct (Adapter $adapter = null, $databaseSchema = null, 
            ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter = $adapter;
        return parent::__construct('trees', $this->dbAdapter, $databaseSchema, 
                $selectResultPrototype);
    }

    public function raiz ()
    {    
        $query = '';
        $query .= 'SELECT node.`name`, (COUNT(parent.`name`) - 1) AS depth, node.idTree as category_id, (node.rght - node.lft) AS ultimo';
        $query .= ' FROM trees AS node';
        $query .= ' CROSS JOIN trees AS parent';
        $query .= ' WHERE node.lft BETWEEN parent.lft AND parent.rght';
        $query .= ' GROUP BY node.`name`';
        $query .= ' ORDER BY node.lft';
        
        $ejecutaQuery = $this->dbAdapter->query($query, 
                Adapter::QUERY_MODE_EXECUTE);
        $filas = $ejecutaQuery->toArray();
        return $filas;
    }

    public function renderTree ($tree, $currDepth = -1)
    {
        $currNode = array_shift($tree);
        
        $result = '';   
        
        if ($currNode['depth'] > $currDepth) {
            $result .= '<ul>';
        }
        
        if ($currNode['depth'] < $currDepth) {
            $result .= str_repeat('</ul>', $currDepth - $currNode['depth']);
        }
        
        if ($currNode['ultimo'] != 1) {
            $result .= '<li><span tabindex="1">' . $currNode['name'] . '</span>';
        } else {
            $result .= '<li><a href="#">' . $currNode['name'] . '</a>';
        }
        
        if (! empty($tree)) {
            $result .= $this->renderTree($tree, $currNode['depth']);
        } else {
            $result .= str_repeat('</li></ul>', $currNode['depth'] + 1);
        }
        return $result;
        
        
        
    }

    public function createArray ($results)
    {
        $return = $results[0];
        array_shift($results);
        $rgt = 0;
        if ($return['lft'] + 1 == $return['rght'])
            $return['leaf'] = true;
        else {
            foreach ($results as $key => $result) {
                if ($result['lft'] > $return['rght']) // not a child
                    break;
                if ($rgt > $result['lft']) // not a top-level child
                    continue;
                $return['sub'][] = $this->createArray(array_values($results));
                foreach ($results as $child_key => $child) {
                    if ($child['rght'] < $result['rght'])
                        unset($results[$child_key]);
                }
                $rgt = $result['rght'];
                unset($results[$key]);
            }
        }
        unset($return['lft'], $return['rght']);
        return $return;
    } 

    
}

?>