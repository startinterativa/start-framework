<?php
namespace StartInterativa\StartFramework\Model\Base;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
 
class BasicFields {

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer", name="id")
     */
    var $id;

    /**
     * @var integer
     *
     * @Column(name="updated_time", type="integer", nullable=false)
     */
    var $updated_time = '0';

    /**
     * @var integer
     *
     * @Column(name="create_time", type="integer", nullable=false)
     */
    var $create_time = '0';

}