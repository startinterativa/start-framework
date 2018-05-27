<?php
namespace StartInterativa\StartFramework\Model\ORM;
/**
 * 
 * @Entity
 * @Table(name="start_user")
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
    * @Column(type="string", name="image")
    */
    var $datetime;
    
    /**
     * @Column(type="integer", name="deleted")
     */
    var $deleted;
    
}