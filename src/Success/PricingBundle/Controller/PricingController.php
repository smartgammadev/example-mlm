<?php

namespace Success\PricingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI;
use Success\MemberBundle\Entity\Member;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Success\TreasureBundle\Exception\NotEnoughAmountException;
use Success\TreasureBundle\Entity\AccountOperation;

class PricingController extends Controller
{

    /**
     * @var $productPricingManager \Success\PricingBundle\Service\ProductPricingManager
     * @DI\Inject("success.pricing.product_pricing_manager")
     */
    private $productPricingManager;

    /**
     * @var $productPricingManager \Success\PricingBundle\Service\ReferalPricingManager
     * @DI\Inject("success.pricing.referal_pricing_manager")
     */
    private $referalPricingManager;

    /**
     * @var $accountManager \Success\TreasureBundle\Service\AccountManager
     * @DI\Inject("success.treasure.account_manager")
     */
    private $accountManager;
    
    /**
     * @Route("/buy-product", name="member_buy_product")
     * @Method({"POST"})
     * @Template()
     */
    public function buyProductAction(Request $request)
    {
        $member = $this->getUser();
        $response = new Response();
        
        if (!$member instanceof Member) {
            $response->setStatusCode(403);
            return $response;
        }
        if (!$request->isXmlHttpRequest()) {
            $response->setStatusCode(403);
            return $response;
        }
        $productPricingId = $secret = $request->request->get('id');
        $productPricing = $this->productPricingManager->getActiveById($productPricingId);
        try {
            $productPricingMember = $this->productPricingManager->processForMember($productPricing, $member);
        } catch (NotEnoughAmountException $ex) {
            $response->setStatusCode(403);
            $response->setContent('0');
            return $response;
        }
        $this->referalPricingManager->processReferalPricingForMember($member, $productPricing->getProductPrice());
        $response->setStatusCode(200);
        $response->setContent(sprintf('Приобретен пакет "%s"', $productPricing->getProductName()));
        return $response;
        
    }

    /**
     * @Route("/add-payment", name="member_add_paymnet")
     * @Method({"POST"})
     * @Template()
     */
    public function addPaymentAction(Request $request)
    {
        $member = $this->getUser();
        $response = new Response();
        
        if (!$member instanceof Member) {
            $response->setStatusCode(403);
            $response->setContent('0');
            return $response;
        }
        if (!$request->isXmlHttpRequest()) {
            $response->setStatusCode(403);
            $response->setContent('0');
            return $response;
        }
        $accountOperation = $this->accountManager->doAccountOperation($member, 100, 'refund');
        if ($accountOperation instanceof AccountOperation) {
            $response->setStatusCode(200);
            $response->setContent('Ваш счет пополнен');
            return $response;
        }
    }
}
