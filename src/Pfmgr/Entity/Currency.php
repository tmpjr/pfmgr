<?php

namespace Pfmgr\Entity;
use JsonSerializable;

/**
 * @Entity @Table(name="currency")
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 **/
class Currency implements JsonSerializable
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Column(type="string", length=120)
     * @var string
     */
    protected $name;

    /**
     * @Column(type="string", length=8)
     * @var string
     */
    protected $symbol;

    /**
     * @Column(type="string", length=3)
     * @var string
     */
    protected $code;

    /**
     * @OneToMany(targetEntity="Account", mappedBy="currency")
     * @var Account[]
     **/
    protected $assignedCurrency = null;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'symbol' => $this->getSymbol(),
            'code' => $this->getCode()
        );
    }

    public function addAssignedCurrency($currency)
    {
        $this->assignedCurrency[] = $currency;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }
}