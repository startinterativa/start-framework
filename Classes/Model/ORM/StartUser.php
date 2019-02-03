<?php
namespace StartInterativa\StartFramework\Model\ORM;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * 
 * @Entity
 * @Table(name="start_user")
 * @Entity(repositoryClass="StartInterativa\StartFramework\Model\Repository\StartUserRepository")
 */
class StartUser
{
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer", name="id")
     */
    var $id;
    
    /**
     * @Column(type="string", name="username")
     */
    var $username;
    
    /**
     * @Column(type="string", name="password")
     */
    var $password;
    
    /**
     * @Column(type="string", name="type")
     */
    var $type;
    
    /**
     * @Column(type="string", name="email")
     */
    var $email;
    
    /**
    * @Column(type="text", name="image")
    */
    var $image;
    
    /**
     * @Column(type="integer", name="lastLogin", options={"default" : 0})
     */
    var $lastLogin = 0;

    /**
     * @Column(type="integer", name="hideLogin", options={"default" : 0})
     */
    var $hideLogin = 0;
    
    /**
     * @Column(type="integer", name="crdate")
     */
    var $crdate;
    
    /**
     * @Column(type="integer", name="deleted", options={"default" : 0})
     */
    var $deleted = 0;
    
}