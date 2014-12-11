<?php

namespace Success\EventBundle\Tests\Services;

use Gamma\PhpUnit\Tester\Test\ServiceTest;
//use LaMelle\ImageBundle\Mocks\Repository\ImageRepositoryMock;

class SalesGeneratorManagerTest extends ServiceTest
{
    const ANSWER_REPO = 'SuccessSalesGeneratorBundle:Answer';
    const AUDIENCE_REPO = 'SuccessSalesGeneratorBundle:Audience';
    const QUESTION_REPO = 'SuccessSalesGeneratorBundle:Question';
    const QUESTINS_STRING = 'Будьте добры, расскажите подробней, что за компанию Вы ищете ? Какие у Вас к ней требования?||А какую именно компанию Вы ищете ? Какие у Вас к ней требования?||А какую именно компанию Вы желали бы найти ? Какие у Вас к ней требования?||Скажите, а что за компанию Вы бы хотели найти? Какие у Вас к ней требования?||А какую компанию Вы желали бы найти ? Какие у Вас к ней требования?||Будьте любезны, расскажите, пожалуйста, а какую компанию Вы желали бы найти ? Какими особенностями она должна обладать?||Будьте любезны, расскажите, пожалуйста, а какую компанию Вы бы хотели найти? Какие особенности должны быть у нее?||А какую компанию Вы бы хотели найти? Какими особенностями она должна обладать?||А какую компанию Вы хотели бы найти ? Какие у Вас к ней требования?||А какую компанию Вы бы хотели найти? Какие особенности должны быть у нее?||Скажите, а что за компанию Вы хотели бы найти ? Какие у Вас к ней требования?||Будьте добры, расскажите подробней, что за компанию Вы желали бы найти ? Какие требования Вы предъявляете к этой компании?||А какую именно компанию Вы хотели бы найти ? Какими особенностями она должна обладать?||Скажите, а что за компанию Вы желали бы найти ? Какие у Вас к ней требования?||Будьте добры, расскажите подробней, что за компанию Вы бы хотели найти? Какими особенностями она должна обладать?||А какую именно компанию Вы ищете ? Какие требования Вы предъявляете к этой компании?||Будьте любезны, расскажите, пожалуйста, а какую компанию Вы бы хотели найти? Какие у Вас к ней требования?'; 
    
    /**
     * Target class name for the instance creation
     * 
     * @var mixed $instance
     */
    protected $targetClassName = 'Success\SalesGeneratorBundle\Service\SalesGeneratorManager';
    
    /**
     * Selector to pass container to constructor of class
     * 
     * @var bool
     */
    protected $isConstructContainer = false;
    
    /**
     * List of mocking repositories when $isMockEmulation = true;
     * 
     * @var array
     */  
    protected $emulatedRepositoriesList = array(
       // 'Success\EventBundle\Mocks\Repository\EventRepositoryMockContainer',
    );
  
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();    
        $this->instance->setEntityManager($this->container->get('doctrine.orm.entity_manager'));
    }   
    
    /**
     * @covers \Success\SalesGeneratorBundle\Service\SalesGeneratorManager::getAllAudiences
     */
    public function testGetAllAudiences()
    {
        $result = $this->instance->getAllAudiences();
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\SalesGeneratorBundle\Entity\Audience', $result[0]);
    }
    
    /**
     * @covers \Success\SalesGeneratorBundle\Service\SalesGeneratorManager::getAllQuestionsWithAnswers()
     */
    public function testGetCurrentQuestionWithAnswers()
    {
        $result = $this->instance->getAllQuestionsWithAnswers(1);
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\SalesGeneratorBundle\Entity\Question', $result[0]);
    }
    
    /**
     * @covers \Success\SalesGeneratorBundle\Service\SalesGeneratorManager::getQuestionAsArray($question_id)
     */
    public function testGetQuestionAsArray()
    {
        $result = $this->instance->getQuestionAsArray(1);
        
        $this->assertNotNull($result);
        $this->assertArrayHasKey('answers', $result);
        $this->assertNotNull($result['answers']);
        $this->assertNotNull($result['answers'][0]);        
        $this->assertArrayHasKey('id', $result['answers'][0]);
        $this->assertArrayHasKey('text', $result['answers'][0]);
        $this->assertArrayHasKey('nextQuestion', $result['answers'][0]);
    }
    
    /**
     * @covers \Success\SalesGeneratorBundle\Service\SalesGeneratorManager::getAllQuestionsWithAnswers()
     */
    public function testGetAllQuestionsWithAnswers()
    {
        $result = $this->instance->getAllQuestionsWithAnswers();
        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\SalesGeneratorBundle\Entity\Question', $result[1]);
    }
        
    /**
     * @covers \Success\SalesGeneratorBundle\Service\SalesGeneratorManager::randomizeQuestion($questionsString)
     */
    public function testRandomizeQuestion()
    {
        $result = $this->instance->randomizeQuestion(self::QUESTINS_STRING);
        $this->assertNotNull($result);
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
}
