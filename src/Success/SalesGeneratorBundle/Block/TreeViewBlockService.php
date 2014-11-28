<?php

namespace Success\SalesGeneratorBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TreeViewBlockService extends BaseBlockService
{
    /**
     * @var \Success\SalesGeneratorBundle\Service\SalesGeneratorManager
     */
    private $salesGeneratorManager;
    
    public function setSalesGeneratorManager(\Success\SalesGeneratorBundle\Service\SalesGeneratorManager $manager)
    {
        $this->salesGeneratorManager = $manager;
    }

    public function getName()
    {
        return 'Tree view';
    }
    
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings.url')
                ->assertNotNull([])
                ->assertNotBlank()
            ->end()
            ->with('settings.title')
                ->assertNotNull([])
                ->assertNotBlank()
                ->assertMaxLength(['limit' => 50])
            ->end();
    }
    
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', [
            'keys' => [
                ['url', 'url', ['required' => false]],
                ['title', 'text', ['required' => false]],
            ]
        ]);
    }
    
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'url'      => "/tree",
            'title'    => 'Sales generator tree view',
            'template' => 'SuccessSalesGeneratorBundle:Block:tree_view_block.html.twig',
        ]);
    }
    
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();

        $audiences = $this->salesGeneratorManager->getAllAudiences();

        return $this->renderResponse($blockContext->getTemplate(), [
            'audiences'     => $audiences,
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ], $response);
    }
}