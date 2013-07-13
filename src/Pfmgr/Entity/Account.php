<?php

namespace Pfmgr\Entity;

use JsonSerializable;

/**
 * @Entity @Table(name="account")
 *
 * @copyright 2013 Tom Ploskina Jr. <tploskina@gmail.com>
 * @author Tom Ploskina Jr. <tploskina@gmail.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 **/
class Account implements JsonSerializable
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
     * @ManyToOne(targetEntity="User", inversedBy="ownedAccounts")
     */
    protected $user;

    /**
     * @OneToMany(targetEntity="AccountTransaction", mappedBy="account", cascade={"persist"})
     * @var AccountTransaction[]
     **/
    protected $ownedAccountTransactions = null;

    /**
     * @ManyToOne(targetEntity="Currency", inversedBy="assignedCurrency")
     */
    protected $currency;

    /**
     * @Column(type="integer") @Version
     * @var integer
     *
     * Used to lock table and prevent race condition on multiple updates
    */
    private $version;

    /**
     * @Column(type="decimal", precision=19, scale=4)
     */
    private $balance = 0;

    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Denomralize the balance and store it in the account table.
     *
     * Add a transaction and aggregate to store balance. This keeps our
     * balance up to date without having to query the transaction table each time.
     * The drawback, however, is that we cannot allow updates to the transaction table
     * until we implment logic to listen and update the balance. This method was adapted
     * from the official Doctrine docs.
     * http://docs.doctrine-project.org/en/latest/cookbook/aggregate-fields.html
     *
     * @param decimal(19,4) the amount of the transaction
     * @return object an instance of Pfmgr\Entity\Transaction
     *
     */
    public function addTransaction($amount, $description, $transactionDate)
    {
        $tx = new AccountTransaction;
        $tx->setAmount($amount);
        $tx->setDescription($description);
        $tx->setTransactionDate($transactionDate);
        $tx->setAccount($this);
        $this->balance += $amount;
        return $tx;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'balance' => $this->getBalance()
        );
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

    public function setOwner($user)
    {
        $user->addOwnedAccount($this);
        $this->user = $user;
    }

    public function setCurrency($currency)
    {
        $currency->addAssignedCurrency($this);
        $this->currency = $currency;
    }

    public function addOwnedAccountTransaction($accountTransaction)
    {
        $this->ownedAccountTransactions[] = $accountTransaction;
    }
}