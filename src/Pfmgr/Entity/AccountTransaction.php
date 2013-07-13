<?php

namespace Pfmgr\Entity;

/**
 * @Entity @Table(name="account_transaction")
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 **/
class AccountTransaction
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Column(type="decimal", precision=19, scale=4)
     * @var int
     */
    protected $amount;

    /**
     * @Column(name="description", type="string", length=255)
     * @var string
     */
    protected $description;

    /**
     * @Column(type="datetime",name="transaction_date")
     * @var \DateTime
     */
    protected $transactionDate;

    /**
     * @ManyToOne(targetEntity="Account", inversedBy="ownedAccountTransactions")
     */
    protected $account;

    public function setAccount($account)
    {
        $account->addOwnedAccountTransaction($this);
        $this->account = $account;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this; // allow method chaining
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setTransactionDate(\DateTime $transactionDate)
    {
        $this->transactionDate = $transactionDate;
        return $this;
    }

    public function getTransactionDate()
    {
        return $this->transactionDate;
    }
}