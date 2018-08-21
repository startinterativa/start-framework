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
     * @Column(type="string", name="type")
     */
    var $type;

    /**
     * @Column(type="string", name="action")
     */
    var $action;
    
    /**
     * @Column(type="text", name="message")
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
     * @Column(type="string", name="tablename")
     */
    var $tablename;
    
    /**
     * @Column(type="integer", name="foreign_id")
     */
    var $foreign_id;
    
}