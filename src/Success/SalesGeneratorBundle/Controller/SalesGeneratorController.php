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
     * @Template("SuccessSalesGeneratorBundle::audiences.html.twig", vars={"audiences"})
     */
    public function chooseAudienceAction()
    {   
//        $this->salesGeneratorManager->audiencesSS();
//        $this->salesGeneratorManager->fillBase(); // Fills DataBase without relations to next questions
        return ['audiences' => $audiences = $this->salesGeneratorManager->getAllAudiences()];
    }
    
    /**
     * 
     * @Route("/audience/{question_id}/", name="sales_generator", requirements={"question_id" = "\d+"})
     * @Template("SuccessSalesGeneratorBundle::sales_generator.html.twig")
     */
    public function questionAction($question_id)
    { 
        $question = $this->salesGeneratorManager->getCurrentQuestionWithAnswers($question_id);
        return ['question' => $question];
    }
}
