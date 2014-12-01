<?php

namespace Success\SalesGeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
//        $this->salesGeneratorManager->fillAudiences();
//        $this->salesGeneratorManager->fillQuestionsAndAnswers(); // Fills DataBase without relations to next questions
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
    
    /**
     * @Route("/admin/treeview/get-audiences", name="admin_sonata_get_audiences")
     */
    public function treeViewAction()
    {   
        return ['audiences' => $audiences = $this->salesGeneratorManager->getAllAudiences()];
    }
    
    /**
     * @Route("/admin/treeview/get-question", name="admin_sonata_get_question")
     */
    public function getQuestion(\Symfony\Component\HttpFoundation\Request $request)
    {
        $question_id = $request->request->get('question_id');
        
        $question = $this->salesGeneratorManager->getQuestionAsArray($question_id);
        
        return new JsonResponse($question);
    }
}
