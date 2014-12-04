<?php

namespace Success\EventBundle\Tests\Services;

use Gamma\PhpUnit\Tester\Test\ServiceTest;
use LaMelle\ImageBundle\Mocks\Repository\ImageRepositoryMock;

class EventManagerTest extends ServiceTest
{
    /**
     * Target class name for the instance creation
     * 
     * @var mixed $instance
     */
    protected $targetClassName = 'Success\EventBundle\Service\EventManager';
    
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
        
//        if ($this->isMockEmulation) {
/*
            $this->fotoliaImage = new \LaMelle\FotoliaBundle\Entity\Image;
            $this->pantherImage = new \LaMelle\PanthermediaBundle\Entity\Image;
            $this->images = array(new \LaMelle\PanthermediaBundle\Entity\Image, new \LaMelle\FotoliaBundle\Entity\Image);
            
            $imageProcessorMockContainer = new \LaMelle\ImageBundle\Mocks\Service\ImageProcessorMockContainer();
            
             @var $imageProcessorMock \LaMelle\ImageBundle\Interfaces\Services\ImageProcessorInterface 
            $imageProcessorMock = $imageProcessorMockContainer ->getServiceMock();
            
            $imageFilterManagerMock = $this->getMock('\Avalanche\Bundle\ImagineBundle\Imagine\Filter\FilterManager', array('getFilter', 'apply', 'save'), array(), '', false);
            
            $this->instance->setImageProcessors(array('panther' => $imageProcessorMock, 'fotolia' => $imageProcessorMock));
            $this->instance->setImageFilterManager($imageFilterManagerMock);
 * */

 //       } else {
            /*
            $this->instance->setImageProcessors(array('panther' => $this->container->get("lamelle.panthermedia.image"), 'fotolia' => $this->container->get("lamelle.fotolia.image")));
            $this->instance->setImageFilterManager($this->container->get("imagine.filter.manager"));
            
            // let's fill the test by real images
            $em = $this->container->get('doctrine.orm.entity_manager');
            $pantherRepo = $em->getRepository('LaMellePanthermediaBundle:Image');
            $fotoliaRepo = $em->getRepository('LaMelleFotoliaBundle:Image');
            
            $this->fotoliaImage = $fotoliaRepo->findOneByFotoliaId(self::SOME_IMAGE_FOTOLIA_ID);
            // ConfigurationListener actions
            \LaMelle\FotoliaBundle\Entity\Image::setWebFiles($this->container->getParameter('la_melle_fotolia.config.web_files'));
            \LaMelle\FotoliaBundle\Entity\Image::setWebPath($this->container->getParameter('la_melle_fotolia.config.web_path'));
            \LaMelle\FormBundle\Entity\FotoliaFile::setWebPath($this->container->getParameter('la_melle_fotolia.config.web_path'));
            \LaMelle\FormBundle\Entity\FotoliaFile::setWebFiles($this->container->getParameter('la_melle_fotolia.config.web_files'));
           
            $this->pantherImage = $pantherRepo->findOneByMediaId(self::DEFAULT_IMAGE_PANTHER_ID);
            // ConfigurationListener actions
            \LaMelle\PanthermediaBundle\Entity\Image::setImagePrice($this->container->getParameter('lamelle.panther.config.price'));
            \LaMelle\PanthermediaBundle\Entity\Image::setRawFiles($this->container->getParameter('lamelle.panther.config.raw_files'));
            \LaMelle\PanthermediaBundle\Entity\Image::setWebFiles($this->container->getParameter('lamelle.panther.config.web_files'));
            \LaMelle\PanthermediaBundle\Entity\Image::setWebPath($this->container->getParameter('lamelle.panther.config.web_path'));
            \LaMelle\PanthermediaBundle\Entity\SourceFile::setWebPath($this->container->getParameter('lamelle.panther.config.web_path'));
            \LaMelle\PanthermediaBundle\Entity\SourceFile::setWebFiles($this->container->getParameter('lamelle.panther.config.web_files')); 
             * 
             */
            $this->instance->setEntityManager($this->container->get('doctrine.orm.entity_manager'));
            $this->instance->setNotificationManager($this->container->get('success.notification.notification_manager'));
            $this->instance->setPlaceholderManager($this->container->get('success.placeholder.placeholder_manager'));
            $this->instance->setSettingsManager($this->container->get('success.settings.settings_manager'));          
        //} 
    }   
    
    /**
     * @covers Success\EventBundle\Service\EventManager::getEventsByDateRange
     */
    public function testGetEventsByDateRange()
    {
        $result = $this->instance->getEventsByDateRange(new \DateTime(), new \DateTime());

        $this->assertNotNull($result);
        $this->assertInstanceOf('Success\EventBundle\Entity\Baseevent', $result[0]);
    }

    /**
     * Test get default image id
     * 
     * @covers LaMelle\ImageBundle\Services\ImageManager::getDefaultImageId
     */
    public function testGetDefaultImageId()
    {
        $result = $this->instance->getDefaultImageId();
        
        $this->assertNotNull($result);
    }  
    
    /**
     * Test set default image id
     * 
     * @covers LaMelle\ImageBundle\Services\ImageManager::setDefaultImageId
     */
    public function testSetDefaultImageId()
    {
        $result = $this->instance->setDefaultImageId(self::DEFAULT_IMAGE_ID);
        
        $this->assertNotNull($result);
    }

    /**
     * @covers LaMelle\ImageBundle\Services\ImageManager::createPreviewImagesByImage
     */
    public function testCreatePreviewImagesByImage()
    {   
        $result = $this->instance->createPreviewImagesByImage($this->pantherImage);
        $this->assertNotNull($result);
        $this->assertInstanceOf('LaMelle\ImageBundle\Entity\Image', $result);
        
        $result = $this->instance->createPreviewImagesByImage($this->fotoliaImage);
        $this->assertNotNull($result);
        $this->assertInstanceOf('LaMelle\ImageBundle\Entity\Image', $result);
    } 
    
    /**
     * @covers LaMelle\ImageBundle\Services\ImageManager::getLandingImageByKeywordStats
     */
    public function testGetLandingImageByKeywordStats()
    {   
        $keywordStats = new \LaMelle\LandingPageBundle\Entity\KeywordStats;
        
        // test with keywordStats without image assigned
        $result = $this->instance->getLandingImageByKeywordStats($keywordStats);
        $this->assertNotNull($result);
        $this->assertInstanceOf('LaMelle\ImageBundle\Entity\Image', $result);
        
        // test with keywordStats without image assigned
        $image = new \LaMelle\PanthermediaBundle\Entity\Image;
        $keywordStats->setImage($image);
        
        $result = $this->instance->getLandingImageByKeywordStats($keywordStats);
        $this->assertNotNull($result);
        $this->assertInstanceOf('LaMelle\ImageBundle\Entity\Image', $result);        
    }

    /**
     * covers LaMelle\ImageBundle\Services\ImageManager::clearImagesResultCache
    public function testClearImagesResultCache()
    {          
        // test with keywordStats without image assigned
        $result = $this->instance->clearImagesResultCache($this->images, 'fakePath');
        $this->assertTrue($result);    
    }    
    */ 
}
