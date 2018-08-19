<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 28/06/2018
 * Time: 11:19 PM
 */

namespace Lvinkim\Swim\Server;

class HttpShare
{
    const MASTER_PID_FILE = '/tmp/swoon-master.pid';
    const MANAGER_PID_FILE = '/tmp/swoon-manager.pid';

    static private $masterPid;
    static private $managerPid;

    static public $workers = [];

    /**
     * @return mixed
     */
    public static function getMasterPid()
    {
        if (self::$masterPid) {
            return self::$masterPid;
        }

        if (is_file(self::MASTER_PID_FILE)) {
            return file_get_contents(self::MASTER_PID_FILE);
        }

        return 0;
    }

    /**
     * @param mixed $masterPid
     */
    public static function setMasterPid($masterPid): void
    {
        self::$masterPid = $masterPid;
        file_put_contents(self::MASTER_PID_FILE, $masterPid);
    }

    /**
     * @return mixed
     */
    public static function getManagerPid()
    {
        if (self::$managerPid) {
            return self::$managerPid;
        }

        if (is_file(self::MANAGER_PID_FILE)) {
            return file_get_contents(self::MANAGER_PID_FILE);
        }

        return 0;
    }

    /**
     * @param mixed $managerPid
     */
    public static function setManagerPid($managerPid): void
    {
        self::$managerPid = $managerPid;
        file_put_contents(self::MANAGER_PID_FILE, $managerPid);
    }

    public static function removeMasterPid()
    {
        file_put_contents(self::MASTER_PID_FILE, 0);
    }

    public static function removeManagerPid()
    {
        file_put_contents(self::MANAGER_PID_FILE, 0);
    }
}