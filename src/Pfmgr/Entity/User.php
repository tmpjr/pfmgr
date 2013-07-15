<?php

namespace Pfmgr\Entity;
use JsonSerializable;

/**
 * @Entity(repositoryClass="\Pfmgr\Repository\User")
 * @Table(name="user")
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 **/
class User implements JsonSerializable
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Column(type="string", length=120, unique=true)
     * @var string
     */
    protected $email;

    /**
     * @Column(type="string", length=150)
     * @var string
     */
    protected $password;

    /**
     * @Column(type="string", length=150)
     * @var string
     */
    protected $roles;

    /**
     * @Column(type="integer", length=1, nullable=true)
     * @var int
     */
    protected $enabled;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $updated;

    /**
     * @OneToMany(targetEntity="Account", mappedBy="user")
     * @var Account[]
     **/
    protected $ownedAccounts = null;

    public function addOwnedAccount($account)
    {
        $this->ownedAccounts[] = $account;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'enabled' => $this->getEnabled(),
            'roles' => $this->getRoles()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setEnabled($enabled = 0)
    {
        $this->enabled = ($enabled === 1) ? 1 : 0;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setCreated(\DateTime $dt)
    {
        $this->created = $dt;
    }

    public function setUpdated(\DateTime $dt)
    {
        $this->updated = $dt;
    }
}