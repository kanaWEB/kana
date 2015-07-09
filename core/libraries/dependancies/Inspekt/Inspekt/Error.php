<?php

/**
 * Source file for Inspekt_Error.
 *
 * @author Ed Finkler <coj@funkatron.com>
 */

/**
 * Error handling for Inspekt.
 */
class Inspekt_Error
{
    /**
     * Constructor.
     *
     * @return Inspekt_Error
     */
    public function Inspekt_Error()
    {
    }

    /**
     * Raises an error.  In >= PHP5, this will throw an exception. In PHP4,
     * this will trigger a user error.
     *
     * @param string $msg
     * @param int    $type One of the PHP Error Constants (E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE)
     *
     * @link http://www.php.net/manual/en/ref.errorfunc.php#errorfunc.constants
     *
     * @todo support PHP5 exceptions without causing a syntax error.  Probably should use factory pattern and instantiate a different class depending on PHP version
     *
     * @static
     */
    public function raiseError($msg, $type = E_USER_WARNING)
    {
        /*if (version_compare( PHP_VERSION, '5', '<' )) {
            Inspekt_Error::raiseErrorPHP4($msg, $type);
        } else {
            throw new Exception($msg, $type);
        }*/

        self::raiseErrorPHP4($msg, $type);
    }

    /**
     * Triggers a user error for PHP4-compatibility.
     *
     * @param string $msg
     * @param int    $type One of the PHP Error Constants (E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE)
     *
     * @static
     */
    public function raiseErrorPHP4($msg, $type = null)
    {
        if (isset($type)) {
            trigger_error($msg);
        } else {
            trigger_error($msg, $type);
        }
    }
}
