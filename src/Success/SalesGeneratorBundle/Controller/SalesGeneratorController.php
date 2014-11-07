<?php

namespace Success\SalesGeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\DiExtraBundle\Annotation as DI;

use Success\SalesGeneratorBundle\Entity\Answer,
    Success\SalesGeneratorBundle\Entity\Question;

class SalesGeneratorController extends Controller
{    
    /**
     * @var \Success\SalesGeneratorBundle\Service\SalesGeneratorManager
     * @DI\Inject("success.sales_generator.manager")
     */
    private $salesGeneratorManager;
    
    /**
     * @Route("/audiences", name="audiences")
     * @Template("SuccessSalesGeneratorBundle:SalesGenerator:audiences.html.twig", vars={"audiences"})
     */
    public function chooseAudienceAction()
    {   
//        $this->salesGeneratorManager->fillBase();
//        $this->salesGeneratorManager->audiencesSS();
        $audiences = $this->salesGeneratorManager->getAllAudiences();
        return ['audiences' => $audiences];
    }
    
    /**
     * 
     * @Route("/sales_generator/{question_id}/", name="sales_generator", requirements={"question_id" = "\d+"})
     * @Template("SuccessSalesGeneratorBundle:SalesGenerator:sales_generator.html.twig")
     */
    public function questionAction($question_id)
    {   
        if (strlen((string)$question_id) > 1) {
            
            $question_id = round($question_id / 2.71);
            $question_id = intval(substr((string)$question_id, 1) . substr((string)$question_id, 0, 1));
        }
                
        $question = $this->salesGeneratorManager->getCurrentQuestionWithAnswers($question_id);
        return ['question' => $question];
    }
}
