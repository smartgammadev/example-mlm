<?php

namespace Success\PricingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Success\MemberBundle\Entity\Member;
use Success\PricingBundle\Entity\ProductPricing;

/**
 * ProductPricingMember
 *
 * @ORM\Table(name="p_product_pricing_member")
 * @ORM\Entity(repositoryClass="Success\PricingBundle\Entity\Repository\ProductPricingMemberRepository")
 */
class ProductPricingMember
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="assign_date", type="datetime")
     */
    private $assignDate;

    
    /**
     * @ORM\ManyToOne(targetEntity="Success\MemberBundle\Entity\Member")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id", nullable=false)
     */
    private $member;
    
    /**
     * @ORM\ManyToOne(targetEntity="Success\PricingBundle\Entity\ProductPricing")
     * @ORM\JoinColumn(name="product_pricing_id", referencedColumnName="id", nullable=false)
     */
    private $productPricing;
    
    /**
     * @var string
     * @ORM\Column(name="price_paid", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $pricePaid;

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set assignDate
     *
     * @param \DateTime $assignDate
     * @return ProductPricingMember
     */
    public function setAssignDate($assignDate)
    {
        $this->assignDate = $assignDate;

        return $this;
    }

    /**
     * Get assignDate
     * @return \DateTime
     */
    public function getAssignDate()
    {
        return $this->assignDate;
    }

    /**
     * Set member
     *
     * @param \Success\MemberBundle\Entity\Member $member
     * @return ProductPricingMember
     */
    public function setMember(Member $member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     * @return \Success\MemberBundle\Entity\Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set pricePaid
     * @param string $pricePaid
     * @return ProductPricingMember
     */
    public function setPricePaid($pricePaid)
    {
        $this->pricePaid = $pricePaid;

        return $this;
    }

    /**
     * Get pricePaid
     * @return string
     */
    public function getPricePaid()
    {
        return $this->pricePaid;
    }

    /**
     * Set productPricing
     * @param \Success\PricingBundle\Entity\ProductPricing $productPricing
     * @return ProductPricingMember
     */
    public function setProductPricing(ProductPricing $productPricing)
    {
        $this->productPricing = $productPricing;

        return $this;
    }

    /**
     * Get productPricing
     * @return \Success\PricingBundle\Entity\ProductPricing
     */
    public function getProductPricing()
    {
        return $this->productPricing;
    }
}
