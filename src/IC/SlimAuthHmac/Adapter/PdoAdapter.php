<?php
/**
 * Authentication adapter
 *
 * @package    SlimAuthHmac
 * @copyright  Copyright (c) 2014 Ilan Cohen <ilanco@gmail.com>
 * @license    https://raw.githubusercontent.com/ilanco/slim-auth-hmac/master/LICENSE   MIT License
 * @link       https://github.com/ilanco/slim-auth-hmac
 */

namespace IC\SlimAuthHmac\Adapter;

use PDO;

class PdoAdapter extends AbstractAdapter
{
    protected $connection = null;

    public function __construct(PDO $connection, $tableName, $identityColumn, $credentialColumn)
    {
        $this->connection = $connection;
    }

    public function authenticate()
    {
        $identity = $this->findIdentity();
    }

    /**
     * Finds identity to authenticate
     *
     * @return array|null Array of identity data, null if no identity found
     */
    private function findIdentity()
    {
        $sql = sprintf(
            'SELECT * FROM %s WHERE %s = :identity',
            $this->getTableName(),
            $this->getIdentityColumn()
        );
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array('identity' => $this->getIdentity()));

        return $stmt->fetch();
    }

    /**
     * Get tableName
     *
     * @return string tableName
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Get identityColumn
     *
     * @return string identityColumn
     */
    public function getIdentityColumn()
    {
        return $this->identityColumn;
    }

    /**
     * Get credentialColumn
     *
     * @return string credentialColumn
     */
    public function getCredentialColumn()
    {
        return $this->credentialColumn;
    }
}
