<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="quote")
 * @ORM\Entity(repositoryClass="App\Repository\QuoteRepository")
 */
class Quote {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, name="policy_number")
     * @Assert\NotBlank()
     */
    private $policyNumber;

    /**
     * @ORM\Column(type="integer", name="age")
     * @Assert\NotBlank()
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=10, name="postcode")
     * @Assert\NotBlank()
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=10, name="reg_no")
     * @Assert\NotBlank()
     */
    private $regNo;

    /**
     * @ORM\Column(type="string", length=10, name="abi_code")
     * @Assert\NotBlank()
     */
    private $abiCode;


    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, name="premium")
     * @Assert\NotBlank()
     */
    private $premium;


    //------ Establish setters and getters -----------//
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPolicyNumber()
    {
        return $this->policyNumber;
    }

    /**
     * @param mixed $policyNumber
     */
    public function setPolicyNumber($policyNumber)
    {
        $this->policyNumber = $policyNumber;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->policyNumber;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param mixed $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * @return mixed
     */
    public function getRegNo()
    {
        return $this->regNo;
    }

    /**
     * @param mixed $regNo
     */
    public function setRegNo($regNo)
    {
        $this->regNo = $regNo;
    }

    /**
     * @return mixed
     */
    public function getAbiCode()
    {
        return $this->abiCode;
    }

    /**
     * @param mixed $abiCode
     */
    public function setAbiCode($abiCode)
    {
        $this->abiCode = $abiCode;
    }

    /**
     * @return mixed
     */
    public function getPremium()
    {
        return $this->premium;
    }

    /**
     * @param mixed $premium
     */
    public function setPremium($premium)
    {
        $this->premium = $premium;
    }

}