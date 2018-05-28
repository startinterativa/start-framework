<?php
namespace StartInterativa\StartFramework\Model\ORM;
/**
 * 
 * @Entity
 * @Table(name="start_log")
 */
class StartLog
{
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer", name="id")
     */
    var $id;
    
    /**
     * @Column(type="string", name="action")
     */
    var $action;
    
    /**
     * @Column(type="string", name="message")
     */
    var $message;
    
    /**
     * @Column(type="integer", name="status")
     */
    var $status;
    
    /**
     * @Column(type="string", name="user")
     */
    var $user;
    
    /**
    * @Column(type="integer", name="datetime")
    */
    var $datetime;
    
    /**
     * @Column(type="integer", name="tablename")
     */
    var $tablename;
    
    /**
     * @Column(type="string", name="key")
     */
    var $key;
    
}